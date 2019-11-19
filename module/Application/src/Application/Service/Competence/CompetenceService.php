<?php

namespace Application\Service\Competence;

use Application\Entity\Db\Competence;
use Application\Entity\Db\CompetenceTheme;
use Application\Entity\Db\CompetenceType;
use DateTime;
use Doctrine\ORM\ORMException;
use Exception;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Utilisateur\Service\User\UserServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class CompetenceService {
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /** COMPETENCE ****************************************************************************************************/

    /**
     * @param string $champ
     * @param string $order
     * @return Competence[]
     */
    public function getCompetences($champ = 'libelle', $order = 'ASC')
    {
        $qb = $this->getEntityManager()->getRepository(Competence::class)->createQueryBuilder('competence')
            ->addSelect('type')->leftJoin('competence.type', 'type')
            ->addSelect('theme')->leftJoin('competence.theme', 'theme')
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
        $qb = $this->getEntityManager()->getRepository(Competence::class)->createQueryBuilder('competence')
            ->addSelect('type')->leftJoin('competence.type', 'type')
            ->addSelect('theme')->leftJoin('competence.theme', 'theme')
            ->andWhere('competence.theme IS NULL')
            ->orderBy('competence.'.$champ, $order)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $champ
     * @param string $order
     * @return array
     */
    public function getCompetencesAsOptions($champ = 'libelle', $order = 'ASC')
    {
        $types = $this->getCompetences($champ, $order);
        $options = [];
        foreach ($types as $type) {
            $options[$type->getId()] = $type->getLibelle();
        }
        return $options;
    }

    /**
     * @param bool $historiser
     * @param string $champ
     * @param string $order
     * @return array
     */
    public function getCompetencesAsGroupOptions($historiser = false, $champ = 'libelle', $order = 'ASC')
    {
        $themes = $this->getCompetencesThemes();
        $sanstheme = $this->getCompetencesSansTheme();
        usort($sanstheme, function ($a, $b) { return $a->getLibelle() > $b->getLibelle();});
        $options = [];

        foreach ($themes as $theme) {
            $optionsoptions = [];
            $competences = $theme->getCompetences();
            usort($competences, function ($a, $b) { return $a->getLibelle() > $b->getLibelle();});
            foreach ($competences as $competence) {
                $this_option = [
                    'value' =>  $competence->getId(),
                    'attributes' => [
                          'data-content' => ($competence->getType())?"<span class='badge ".$competence->getType()->getLibelle()."'>".$competence->getType()->getLibelle()."</span> &nbsp;". $competence->getLibelle():"",
                    ],
                    'label' => $competence->getLibelle(),
                ];
                $optionsoptions[] = $this_option;
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
                //TODO sort competences here
                $this_option = [
                    'value' =>  $competence->getId(),
                    'attributes' => [
                        'data-content' => ($competence->getType())?"<span class='badge ".$competence->getType()->getLibelle()."'>".$competence->getType()->getLibelle()."</span> &nbsp;". $competence->getLibelle():"",
                    ],
                    'label' => $competence->getLibelle(),
                ];
                $optionsoptions[] = $this_option;
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
        $qb = $this->getEntityManager()->getRepository(Competence::class)->createQueryBuilder('competence')
            ->andWhere('competence.id = :id')
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
     * @return Competence
     */
    public function getRequestedCompetence($controller, $paramName = 'competence')
    {
        $id = $controller->params()->fromRoute($paramName);
        $competence = $this->getCompetence($id);
        return $competence;
    }

    /**
     * @param Competence $competence
     * @return Competence
     */
    public function create($competence)
    {
        try {
            $user = $this->getUserService()->getConnectedUser();
            $date = new DateTime();
        } catch (Exception $e) {
            throw new RuntimeException("Un problème est survenue lors de la récupération des données d'historisation.", $e);
        }

        $competence->setHistoCreation($date);
        $competence->setHistoCreateur($user);
        $competence->setHistoModification($date);
        $competence->setHistoModificateur($user);

        try {
            $this->getEntityManager()->persist($competence);
            $this->getEntityManager()->flush($competence);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $competence;
    }

    /**
     * @param Competence $competence
     * @return Competence
     */
    public function update($competence)
    {
        try {
            $user = $this->getUserService()->getConnectedUser();
            $date = new DateTime();
        } catch (Exception $e) {
            throw new RuntimeException("Un problème est survenue lors de la récupération des données d'historisation.", $e);
        }

        $competence->setHistoModification($date);
        $competence->setHistoModificateur($user);

        try {
            $this->getEntityManager()->flush($competence);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $competence;
    }

    /**
     * @param Competence $competence
     * @return Competence
     */
    public function historise($competence)
    {
        try {
            $user = $this->getUserService()->getConnectedUser();
            $date = new DateTime();
        } catch (Exception $e) {
            throw new RuntimeException("Un problème est survenue lors de la récupération des données d'historisation.", $e);
        }

        $competence->setHistoDestruction($date);
        $competence->setHistoDestructeur($user);

        try {
            $this->getEntityManager()->flush($competence);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $competence;
    }

    /**
     * @param Competence $competence
     * @return Competence
     */
    public function restore($competence)
    {
        $competence->setHistoDestruction(null);
        $competence->setHistoDestructeur(null);

        try {
            $this->getEntityManager()->flush($competence);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $competence;
    }

    /**
     * @param Competence $competence
     * @return Competence
     */
    public function delete($competence)
    {
        try {
            $this->getEntityManager()->remove($competence);
            $this->getEntityManager()->flush($competence);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $competence;
    }

    /** COMPETENCE THEME **********************************************************************************************/

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

    /**
     * @param CompetenceTheme $theme
     * @return CompetenceTheme
     */
    public function createTheme($theme)
    {
        try {
            $user = $this->getUserService()->getConnectedUser();
            $date = new DateTime();
        } catch (Exception $e) {
            throw new RuntimeException("Un problème est survenue lors de la récupération des données d'historisation.", $e);
        }

        $theme->setHistoCreation($date);
        $theme->setHistoCreateur($user);
        $theme->setHistoModification($date);
        $theme->setHistoModificateur($user);

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
    public function updateTheme($theme)
    {
        try {
            $user = $this->getUserService()->getConnectedUser();
            $date = new DateTime();
        } catch (Exception $e) {
            throw new RuntimeException("Un problème est survenue lors de la récupération des données d'historisation.", $e);
        }

        $theme->setHistoModification($date);
        $theme->setHistoModificateur($user);

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
    public function historiseTheme($theme)
    {
        try {
            $user = $this->getUserService()->getConnectedUser();
            $date = new DateTime();
        } catch (Exception $e) {
            throw new RuntimeException("Un problème est survenue lors de la récupération des données d'historisation.", $e);
        }

        $theme->setHistoDestruction($date);
        $theme->setHistoDestructeur($user);

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
    public function restoreTheme($theme)
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
    public function deleteTheme($theme)
    {
        try {
            $this->getEntityManager()->remove($theme);
            $this->getEntityManager()->flush($theme);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $theme;
    }

    /** COMPETENCE TYPE ***********************************************************************************************/

    /**
     * @param string $champ
     * @param string $order
     * @return CompetenceType[]
     */
    public function getCompetencesTypes($champ = 'libelle', $order = 'ASC')
    {
        $qb = $this->getEntityManager()->getRepository(CompetenceType::class)->createQueryBuilder('type')
            ->addSelect('competence')->leftJoin('type.competences', 'competence')
            ->orderBy('type.'.$champ, $order)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $champ
     * @param string $order
     * @return array
     */
    public function getCompetencesTypesAsOptions($champ = 'libelle', $order = 'ASC')
    {
        $types = $this->getCompetencesTypes($champ, $order);
        $options = [];
        foreach ($types as $type) {
            $options[$type->getId()] = $type->getLibelle();
        }
        return $options;
    }

    /**
     * @param integer $id
     * @return CompetenceType
     */
    public function getCompetenceType($id)
    {
        $qb = $this->getEntityManager()->getRepository(CompetenceType::class)->createQueryBuilder('type')
            ->andWhere('type.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch(ORMException $e) {
            throw new RuntimeException("Plusieurs CompetenceType partagent le même id [".$id."]", $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return CompetenceType
     */
    public function getRequestedCompetenceType($controller, $paramName = 'competence-type')
    {
        $id = $controller->params()->fromRoute($paramName);
        $type = $this->getCompetenceType($id);
        return $type;
    }

    /**
     * @param CompetenceType $type
     * @return CompetenceType
     */
    public function createType($type)
    {
        try {
            $user = $this->getUserService()->getConnectedUser();
            $date = new DateTime();
        } catch (Exception $e) {
            throw new RuntimeException("Un problème est survenue lors de la récupération des données d'historisation.", $e);
        }

        $type->setHistoCreation($date);
        $type->setHistoCreateur($user);
        $type->setHistoModification($date);
        $type->setHistoModificateur($user);

        try {
            $this->getEntityManager()->persist($type);
            $this->getEntityManager()->flush($type);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $type;
    }

    /**
     * @param CompetenceType $type
     * @return CompetenceType
     */
    public function updateType($type)
    {
        try {
            $user = $this->getUserService()->getConnectedUser();
            $date = new DateTime();
        } catch (Exception $e) {
            throw new RuntimeException("Un problème est survenue lors de la récupération des données d'historisation.", $e);
        }

        $type->setHistoModification($date);
        $type->setHistoModificateur($user);

        try {
            $this->getEntityManager()->flush($type);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $type;
    }

    /**
     * @param CompetenceType $type
     * @return CompetenceType
     */
    public function historiseType($type)
    {
        try {
            $user = $this->getUserService()->getConnectedUser();
            $date = new DateTime();
        } catch (Exception $e) {
            throw new RuntimeException("Un problème est survenue lors de la récupération des données d'historisation.", $e);
        }

        $type->setHistoDestruction($date);
        $type->setHistoDestructeur($user);

        try {
            $this->getEntityManager()->flush($type);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $type;
    }

    /**
     * @param CompetenceType $type
     * @return CompetenceType
     */
    public function restoreType($type)
    {
        $type->setHistoDestruction(null);
        $type->setHistoDestructeur(null);

        try {
            $this->getEntityManager()->flush($type);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $type;
    }

    /**
     * @param CompetenceType $type
     * @return CompetenceType
     */
    public function deleteType($type)
    {
        try {
            $this->getEntityManager()->remove($type);
            $this->getEntityManager()->flush($type);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $type;
    }
}