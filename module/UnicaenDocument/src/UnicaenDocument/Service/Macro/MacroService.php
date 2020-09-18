<?php

namespace UnicaenDocument\Service\Macro;

use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenDocument\Entity\Db\Macro;
use Zend\Mvc\Controller\AbstractActionController;

class MacroService {
    use GestionEntiteHistorisationTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Macro $macro
     * @return Macro
     */
    public function create(Macro $macro)
    {
        $this->createFromTrait($macro);
        return $macro;
    }

    /**
     * @param Macro $macro
     * @return Macro
     */
    public function update(Macro $macro)
    {
        $this->updateFromTrait($macro);
        return $macro;
    }

    /**
     * @param Macro $macro
     * @return Macro
     */
    public function historise(Macro $macro)
    {
        $this->historiserFromTrait($macro);
        return $macro;
    }

    /**
     * @param Macro $macro
     * @return Macro
     */
    public function restore(Macro $macro)
    {
        $this->restoreFromTrait($macro);
        return $macro;
    }

    /**
     * @param Macro $macro
     * @return Macro
     */
    public function delete(Macro $macro)
    {
        $this->deleteFromTrait($macro);
        return $macro;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(Macro::class)->createQueryBuilder('macro')
            ->addSelect('createur')->join('macro.histoCreateur', 'createur')
            ->addSelect('modificateur')->join('macro.histoModificateur', 'modificateur')
        ;

        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return Macro[]
     */
    public function getMacros(string $champ = 'code', string $ordre = 'ASC')
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('macro.' . $champ, $ordre)
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return Macro
     */
    public function getMacro(int $id) {
        $qb = $this->createQueryBuilder()
            ->andWhere('macro.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Macro partagent le même id [".$id."]");
        }
        return $result;
    }

    /**
     * @param string $code
     * @return Macro
     */
    public function getMacroByCode(string $code) {
        $qb = $this->createQueryBuilder()
            ->andWhere('macro.code = :code')
            ->setParameter('code', $code)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Macro partagent le même code [".$code."]");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return Macro
     */
    public function getRequestedMacro(AbstractActionController $controller, $param = 'macro')
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getMacro($id);
        return $result;
    }

    /** FONCTION MACRO ************************************************************************************************/

    /**
     * @param string $code
     * @param array $variables
     * @return string
     */
    public function getTexte(string $code, array $variables)
    {
        $macro = $this->getMacroByCode($code);

        if ($macro !== null) {
            if (isset($variables[$macro->getVariable()]) === true) {
                if (method_exists($variables[$macro->getVariable()], $macro->getMethode()) === true) {
                    $texte = $variables[$macro->getVariable()]->{$macro->getMethode()}();
                    return $texte;
                }
                return "<span style='color:darkred;'> Méthode [".$macro->getMethode()."] non trouvée </span>";
            }
            return "<span style='color:darkred;'> Variable [".$macro->getVariable()."] non trouvée </span>";
        }
        return "<span style='color:darkred;'> Macro [".$code."] non trouvée </span>";
    }

    /**
     * @return string
     */
    public function generateJSON()
    {
        $macros = $this->getMacros();

        $result = "let macros = [\n";
        foreach ($macros as $macro) {
            if ($macro->estNonHistorise()) {
                $result .= "    { title:'" . $macro->getCode() . "', description:'" . strip_tags(str_replace("'","\'",$macro->getDescription())) . "', content:'VAR[" . $macro->getCode() . "]' },\n";
            }
        }
        $result .= "];\n";

        return $result;
    }

}