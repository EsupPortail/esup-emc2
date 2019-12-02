<?php

namespace Application\Service\CompetenceTheme;

use Application\Entity\Db\CompetenceTheme;
use Doctrine\ORM\ORMException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Utilisateur\Service\User\UserServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class CompetenceThemeService {
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /** ENTITY MANAGMENT **********************************************************************************************/

    /**
     * @param CompetenceTheme $theme
     * @return CompetenceTheme
     */
    public function create($theme)
    {
        $theme->updateCreation($this->getUserService());
        $theme->updateModification($this->getUserService());

        try {
            $this->getEntityManager()->persist($theme);
            $this->getEntityManager()->flush($theme);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $theme;
    }

    /**
     * @param CompetenceTheme $theme
     * @return CompetenceTheme
     */
    public function update($theme)
    {
        $theme->updateModification($this->getUserService());

        try {
            $this->getEntityManager()->flush($theme);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $theme;
    }

    /**
     * @param CompetenceTheme $theme
     * @return CompetenceTheme
     */
    public function historise($theme)
    {
        $theme->updateDestructeur($this->getUserService());

        try {
            $this->getEntityManager()->flush($theme);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $theme;
    }

    /**
     * @param CompetenceTheme $theme
     * @return CompetenceTheme
     */
    public function restore($theme)
    {
        $theme->setHistoDestruction(null);
        $theme->setHistoDestructeur(null);

        try {
            $this->getEntityManager()->flush($theme);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $theme;
    }

    /**
     * @param CompetenceTheme $theme
     * @return CompetenceTheme
     */
    public function delete($theme)
    {
        try {
            $this->getEntityManager()->remove($theme);
            $this->getEntityManager()->flush($theme);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
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
        } catch(ORMException $e) {
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
