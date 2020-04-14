<?php

namespace Application\Service\CompetenceTheme;

use Application\Entity\Db\CompetenceTheme;
use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class CompetenceThemeService {
//    use EntityManagerAwareTrait;
//    use UserServiceAwareTrait;
    use GestionEntiteHistorisationTrait;

    /** ENTITY MANAGMENT **********************************************************************************************/

    /**
     * @param CompetenceTheme $theme
     * @return CompetenceTheme
     */
    public function create($theme)
    {
        $this->createFromTrait($theme);
        return $theme;
    }

    /**
     * @param CompetenceTheme $theme
     * @return CompetenceTheme
     */
    public function update($theme)
    {
        $this->updateFromTrait($theme);
        return $theme;
    }

    /**
     * @param CompetenceTheme $theme
     * @return CompetenceTheme
     */
    public function historise($theme)
    {
        $this->historiserFromTrait($theme);
        return $theme;
    }

    /**
     * @param CompetenceTheme $theme
     * @return CompetenceTheme
     */
    public function restore($theme)
    {
        $this->restoreFromTrait($theme);
        return $theme;
    }

    /**
     * @param CompetenceTheme $theme
     * @return CompetenceTheme
     */
    public function delete($theme)
    {
        $this->deleteFromTrait($theme);
        return $theme;
    }

    /** REQUETE *******************************************************************************************************/

    /**
     * @param string $champ
     * @param string $order
     * @return CompetenceTheme[]
     */
    public function getCompetencesThemes($champ = 'libelle', $order = 'ASC')
    {
        $qb = $this->getEntityManager()->getRepository(CompetenceTheme::class)->createQueryBuilder('theme')
            ->addSelect('competence')->leftJoin('theme.competences', 'competence')
            ->orderBy('theme.'.$champ, $order)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $champ
     * @param string $order
     * @return array
     */
    public function getCompetencesThemesAsOptions($champ = 'libelle', $order = 'ASC')
    {
        $types = $this->getCompetencesThemes($champ, $order);
        $options = [];
        foreach ($types as $type) {
            $options[$type->getId()] = $type->getLibelle();
        }
        return $options;
    }

    /**
     * @param integer $id
     * @return CompetenceTheme
     */
    public function getCompetenceTheme($id)
    {
        $qb = $this->getEntityManager()->getRepository(CompetenceTheme::class)->createQueryBuilder('theme')
            ->andWhere('theme.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch(NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs CompetenceTheme partagent le même id [".$id."]", $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return CompetenceTheme
     */
    public function getRequestedCompetenceTheme($controller, $paramName = 'competence-theme')
    {
        $id = $controller->params()->fromRoute($paramName);
        $theme = $this->getCompetenceTheme($id);
        return $theme;
    }


}
