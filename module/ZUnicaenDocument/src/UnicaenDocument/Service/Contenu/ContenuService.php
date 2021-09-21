<?php

namespace UnicaenDocument\Service\Contenu;

use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenDocument\Entity\Db\Content;
use UnicaenDocument\Service\Macro\MacroServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class ContenuService {
    use MacroServiceAwareTrait;
    use GestionEntiteHistorisationTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Content $content
     * @return Content
     */
    public function create(Content $content) : Content
    {
        $this->createFromTrait($content);
        return $content;
    }

    /**
     * @param Content $content
     * @return Content
     */
    public function update(Content $content) : Content
    {
        $this->updateFromTrait($content);
        return $content;
    }

    /**
     * @param Content $content
     * @return Content
     */
    public function historise(Content $content) : Content
    {
        $this->historiserFromTrait($content);
        return $content;
    }

    /**
     * @param Content $content
     * @return Content
     */
    public function restore(Content $content) : Content
    {
        $this->restoreFromTrait($content);
        return $content;
    }

    /**
     * @param Content $content
     * @return Content
     */
    public function delete(Content $content) : Content
    {
        $this->deleteFromTrait($content);
        return $content;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(Content::class)->createQueryBuilder('contenu')
        ;

        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return Content[]
     */
    public function getContenus(string $champ = 'code', string $ordre = 'ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('contenu.' . $champ, $ordre)
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param int|null $id
     * @return Content|null
     */
    public function getContenu(?int $id) : ?Content
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('contenu.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Content partagent le même id [".$id."]");
        }
        return $result;
    }

    /**
     * @param string $code
     * @return Content|null
     */
    public function getContenuByCode(string $code) : ?Content
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('contenu.code = :code')
            ->setParameter('code', $code)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Content partagent le même code [".$code."]");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return Content|null
     */
    public function getRequestedContenu(AbstractActionController $controller, string $param='contenu') : ?Content
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getContenu($id);

        return $result;
    }

    /** TRAITEMENTS DES MACROS ****************************************************************************************/

    /**
     * @param string $texteInitial
     * @param array $variables
     * @return string
     */
    private function replaceMacros(string $texteInitial, array $variables) : string
    {
        $matches = [];
        preg_match_all('/VAR\[[a-zA-Z0-9_]*#[a-zA-Z0-9_]*\]/', $texteInitial, $matches);

        $patterns = array_unique($matches[0]);
        $replacements = [];
        foreach ($patterns as $pattern) {
            $replacements[] = $this->getMacroService()->getTexte($pattern, $variables);
        }
        $text = str_replace($patterns, $replacements, $texteInitial);

        return $text;
    }

    /**
     * @param Content $contenu
     * @param array $variables
     * @return string
     */
    public function generateComplement(Content $contenu, array $variables) : string
    {
        return $this->replaceMacros($contenu->getComplement(), $variables);
    }

    /**
     * @param Content $contenu
     * @param array $variables
     * @return string
     */
    public function generateContenu(Content $contenu, array $variables) : string
    {
        $texte = "<style>";
        $texte .= $contenu->getCss();
        $texte .= "</style>";
        $texte .= $this->replaceMacros($contenu->getContent(), $variables);
        return $texte;
    }

    /**
     * @param Content $contenu
     * @param array $variables
     * @return string
     */
    public function generateTitre(Content $contenu, array $variables) : string
    {
        return $this->replaceMacros($contenu->getComplement(), $variables);
    }

}