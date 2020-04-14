<?php

namespace Application\Service\Competence;

use Application\Entity\Db\Competence;
use Application\Entity\Db\CompetenceType;
use Application\Service\CompetenceTheme\CompetenceThemeServiceAwareTrait;
use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class CompetenceService {
//    use EntityManagerAwareTrait;
//    use UserServiceAwareTrait;
    use GestionEntiteHistorisationTrait;
    use CompetenceThemeServiceAwareTrait;

    /** GESTION DE L'ENTITÉ *******************************************************************************************/

    /**
     * @param Competence $competence
     * @return Competence
     */
    public function create($competence)
    {
        $this->createFromTrait($competence);
        return $competence;
    }

    /**
     * @param Competence $competence
     * @return Competence
     */
    public function update($competence)
    {
       $this->updateFromTrait($competence);
        return $competence;
    }

    /**
     * @param Competence $competence
     * @return Competence
     */
    public function historise($competence)
    {
        $this->updateFromTrait($competence);
        return $competence;
    }

    /**
     * @param Competence $competence
     * @return Competence
     */
    public function restore($competence)
    {
       $this->restoreFromTrait($competence);
        return $competence;
    }

    /**
     * @param Competence $competence
     * @return Competence
     */
    public function delete($competence)
    {
        $this->deleteFromTrait($competence);
        return $competence;
    }

    /** REQUETES ******************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilderForCompetence()
    {
        $qb = $this->getEntityManager()->getRepository(Competence::class)->createQueryBuilder('competence')
            ->addSelect('type')->leftJoin('competence.type', 'type')
            ->addSelect('theme')->leftJoin('competence.theme', 'theme')
            ->addSelect('createur')->join('competence.histoCreateur', 'createur')
            ->addSelect('modificateur')->join('competence.histoModificateur', 'modificateur')
            ->addSelect('destructeur')->leftJoin('competence.histoDestructeur', 'destructeur')
        ;
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $order
     * @return Competence[]
     */
    public function getCompetences($champ = 'libelle', $order = 'ASC')
    {
        $qb = $this->createQueryBuilderForCompetence()
            ->orderBy('competence.'.$champ, $order)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $champ
     * @param string $order
     * @return Competence[]
     */
    public function getCompetencesSansTheme($champ = 'libelle', $order = 'ASC')
    {
        $qb = $this->createQueryBuilderForCompetence()
            ->andWhere('competence.theme IS NULL')
            ->orderBy('competence.'.$champ, $order)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param CompetenceType $type
     * @param string $champ
     * @param string $order
     * @return Competence[]
     */
    public function getCompetencesByType($type, $champ = 'libelle', $order = 'ASC')
    {
        $qb = $this->createQueryBuilderForCompetence()
            ->andWhere('type.id = :typeId')
            ->setParameter('typeId', $type->getId())
            ->orderBy('competence.'.$champ, $order)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $champ
     * @param string $order
     * @return Competence[]
     */
    public function getCompetencesSansType($champ = 'libelle', $order = 'ASC')
    {
        $qb = $this->createQueryBuilderForCompetence()
            ->andWhere('competence.type IS NULL')
            ->orderBy('competence.'.$champ, $order)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }


    /**
     * @param Competence $competence
     * @return array
     */
    private function competenceOptionify(Competence $competence) {
        $this_option = [
            'value' =>  $competence->getId(),
            'attributes' => [
                'data-content' => ($competence->getType())?"<span class='badge ".$competence->getType()->getLibelle()."'>".$competence->getType()->getLibelle()."</span> &nbsp;". $competence->getLibelle():"",
            ],
            'label' => $competence->getLibelle(),
        ];
        return $this_option;
    }

    /**
     * @param bool $historiser
     * @param string $champ
     * @param string $order
     * @return array
     */
    public function getCompetencesAsGroupOptions($historiser = false, $champ = 'libelle', $order = 'ASC')
    {
        $themes = $this->getCompetenceThemeService()->getCompetencesThemes();
        $sanstheme = $this->getCompetencesSansTheme($champ, $order);
        $options = [];

        foreach ($themes as $theme) {
            $optionsoptions = [];
            $competences = $theme->getCompetences();
            usort($competences, function (Competence $a, Competence $b) { return $a->getLibelle() > $b->getLibelle();});
            foreach ($competences as $competence) {
                $optionsoptions[] = $this->competenceOptionify($competence);
            }
            $array = [
                'label' => $theme->getLibelle(),
                'options' => $optionsoptions,
            ];
            $options[] = $array;
        }

        if (!empty($sanstheme)) {
            $optionsoptions = [];
            foreach ($sanstheme as $competence) {
                $optionsoptions[] = $this->competenceOptionify($competence);
            }
            $array = [
                'label' => "Sans thème",
                'options' => $optionsoptions,
            ];
            $options[] = $array;
        }
        return $options;
    }

    /**
     * @param integer $id
     * @return Competence
     */
    public function getCompetence($id)
    {
        $qb = $this->createQueryBuilderForCompetence()
            ->andWhere('competence.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch(ORMException $e) {
            throw new RuntimeException("Plusieurs Competence partagent le même id [".$id."]", 0, $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return Competence
     */
    public function getRequestedCompetence($controller, $paramName = 'competence')
    {
        $id = $controller->params()->fromRoute($paramName);
        $competence = $this->getCompetence($id);
        return $competence;
    }

}