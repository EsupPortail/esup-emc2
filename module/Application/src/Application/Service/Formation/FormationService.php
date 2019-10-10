<?php

namespace Application\Service\Formation;

use Application\Entity\Db\Formation;
use Application\Entity\Db\FormationTheme;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Utilisateur\Service\User\UserServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class FormationService {

    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /**
     * @param string $ordre nom de champ présent dans l'entité
     * @return Formation[]
     */
    public function getFormations($ordre = null)
    {
        $qb = $this->getEntityManager()->getRepository(Formation::class)->createQueryBuilder('formation')
            ->addSelect('theme')->leftJoin('formation.theme', 'theme')
            ->andWhere('formation.histoDestruction IS NULL')
        ;
        if ($ordre) $qb = $qb->orderBy('formation.' . $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $champ
     * @param string $order
     * @return Formation[]
     */
    private function getFormationsSansThemes($champ = 'libelle', $order = 'ASC')
    {
        $qb = $this->getEntityManager()->getRepository(Formation::class)->createQueryBuilder('formation')
            ->andWhere('formation.theme IS NULL')
            ->andWhere('formation.histoDestruction IS NULL')
            ->orderBy('formation.'.$champ, $order)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }
    /**
     * @param int $id
     * @return Formation
     */
    public function getFormation($id)
    {
        $qb = $this->getEntityManager()->getRepository(Formation::class)->createQueryBuilder('formation')
            ->andWhere('formation.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException('Plusieurs formations portent le même identifiant ['.$id.']', $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return Formation
     */
    public function getRequestedFormation($controller, $paramName = 'formation')
    {
        $id = $controller->params()->fromRoute($paramName);
        $activite = $this->getFormation($id);
        return $activite;
    }

    /**
     * @param Formation $formation
     * @return Formation
     */
    public function create($formation)
    {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch (Exception $e) {
            throw new RuntimeException("Un problème s'est produit lors de la récupération des informations d'historisation", $e);
        }
        $formation->setHistoCreation($date);
        $formation->setHistoCreateur($user);
        $formation->setHistoModification($date);
        $formation->setHistoModificateur($user);

        $this->getEntityManager()->persist($formation);
        try {
            $this->getEntityManager()->flush($formation);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException('Un problème est survenu lors de la création en BD', $e);
        }
        return $formation;
    }

    /**
     * @param Formation $formation
     * @return Formation
     */
    public function update($formation)
    {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch (Exception $e) {
            throw new RuntimeException("Un problème s'est produit lors de la récupération des informations d'historisation", $e);
        }
        $formation->setHistoModification($date);
        $formation->setHistoModificateur($user);

        try {
            $this->getEntityManager()->flush($formation);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException('Un problème est survenu lors de la mise à jour en BD', $e);
        }
        return $formation;
    }

    /**
     * @param Formation $formation
     * @return Formation
     */
    public function historise($formation)
    {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch (Exception $e) {
            throw new RuntimeException("Un problème s'est produit lors de la récupération des informations d'historisation", $e);
        }
        $formation->setHistoDestruction($date);
        $formation->setHistoDestructeur($user);

        try {
            $this->getEntityManager()->flush($formation);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException('Un problème est survenu lors de la mise à jour en BD', $e);
        }
        return $formation;
    }

    /**
     * @param Formation $formation
     * @return Formation
     */
    public function restore($formation)
    {
        $formation->setHistoDestruction(null);
        $formation->setHistoDestructeur(null);

        try {
            $this->getEntityManager()->flush($formation);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException('Un problème est survenu lors de la mise à jour en BD', $e);
        }
        return $formation;
    }

    /**
     * @param Formation $formation
     */
    public function delete($formation)
    {
        $this->getEntityManager()->remove($formation);
        try {
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
            throw new RuntimeException('Un problème est survenu lors de la suppression en BD', $e);
        }
    }

    /**
     * @return array
     */
    public function getFormationsAsOptions() {
        $formations = $this->getFormations('libelle');

        $result = [];
        foreach ($formations as $formation) {
            $result[$formation->getId()] = $formation->getLibelle();
        }
        return $result;
    }

    /** FORMATION THEME ***********************************************************************************************/
    /** TODO Quid en faire un service à part entière */

    /**
     * @param bool $historiser
     * @param string $champ
     * @param string $order
     * @return FormationTheme[]
     */
    public function getFormationsThemes($historiser = false, $champ = 'libelle', $order = 'ASC')
    {
        $qb = $this->getEntityManager()->getRepository(FormationTheme::class)->createQueryBuilder('theme')
            ->addSelect('formation')->leftJoin('theme.formations', 'formation')
            ->orderBy('theme.'.$champ, $order);

        if ($historiser === false) {
            $qb = $qb->andWhere('theme.histoDestruction IS NULL');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }
    /**
     * @param bool $historiser
     * @param string $champ
     * @param string $order
     * @return array
     */
    public function getFormationsThemesAsOptions($historiser = false, $champ = 'libelle', $order = 'ASC') {
        $themes = $this->getFormationsThemes($historiser, $champ, $order);

        $array = [];
        foreach ($themes as $theme) {
            $array[$theme->getId()] = $theme->getLibelle();
        }
        return $array;
    }

    /**
     * @param bool $historiser
     * @param string $champ
     * @param string $order
     * @return array
     */
    public function getFormationsThemesAsGroupOptions($historiser = false, $champ = 'libelle', $order = 'ASC')
    {
        $themes = $this->getFormationsThemes();
        $sanstheme = $this->getFormationsSansThemes();
        $options = [];

        foreach ($themes as $theme) {
            $optionsoptions = [];
            foreach ($theme->getFormations() as $formation) {
                $optionsoptions[$formation->getId()] = $formation->getLibelle();
            }
            asort($optionsoptions);
            $array = [
                'label' => $theme->getLibelle(),
                'options' => $optionsoptions,
            ];
            $options[] = $array;
        }

        if (!empty($sanstheme)) {
            $optionsoptions = [];
            foreach ($sanstheme as $formation) {
                $optionsoptions[$formation->getId()] = $formation->getLibelle();
            }
            asort($optionsoptions);
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
     * @return FormationTheme
     */
    public function getFormationTheme($id)
    {
        $qb = $this->getEntityManager()->getRepository(FormationTheme::class)->createQueryBuilder('theme')
            ->andWhere('theme.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (ORMException $e) {
            throw new RuntimeException('Plusieurs FormationTheme partagent le même identifiant ['.$id.'].', $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return FormationTheme
     */
    public function getRequestedFormationTheme($controller, $paramName = "formation-theme")
    {
        $id = $controller->params()->fromRoute($paramName);
        $theme = $this->getFormationTheme($id);
        return $theme;
    }


    /**
     * @param FormationTheme $theme
     * @return FormationTheme
     */
    public function createTheme($theme)
    {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch (Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération des données d'historisation.", $e);
        }
        $theme->setHistoCreation($date);
        $theme->setHistoCreateur($user);
        $theme->setHistoModification($date);
        $theme->setHistoModificateur($user);

        try {
            $this->getEntityManager()->persist($theme);
            $this->getEntityManager()->flush($theme);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en BD.", $e);
        }
        return $theme;
    }

    /**
     * @param FormationTheme $theme
     * @return FormationTheme
     */
    public function updateTheme($theme)
    {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch (Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération des données d'historisation.", $e);
        }
        $theme->setHistoModification($date);
        $theme->setHistoModificateur($user);

        try {
            $this->getEntityManager()->flush($theme);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en BD.", $e);
        }
        return $theme;
    }

    /**
     * @param FormationTheme $theme
     * @return FormationTheme
     */
    public function historizeTheme($theme)
    {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch (Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération des données d'historisation.", $e);
        }
        $theme->setHistoDestruction($date);
        $theme->setHistoDestructeur($user);

        try {
            $this->getEntityManager()->flush($theme);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en BD.", $e);
        }
        return $theme;
    }

    /**
     * @param FormationTheme $theme
     * @return FormationTheme
     */
    public function restoreTheme($theme)
    {
        $theme->setHistoDestruction(null);
        $theme->setHistoDestructeur(null);

        try {
            $this->getEntityManager()->flush($theme);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en BD.", $e);
        }
        return $theme;
    }

    /**
     * @param FormationTheme $theme
     * @return FormationTheme
     */
    public function deleteTheme($theme)
    {
        try {
            $this->getEntityManager()->remove($theme);
            $this->getEntityManager()->flush($theme);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en BD.", $e);
        }
        return $theme;
    }
}