<?php

namespace Autoform\Service\Formulaire;

use Application\Service\User\UserServiceAwareTrait;
use Autoform\Entity\Db\Formulaire;
use Autoform\Entity\Db\FormulaireInstance;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Exception;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class FormulaireInstanceService {
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /**
     * @param AbstractActionController $controller
     * @param string $label
     * @return FormulaireInstance
     */
    public function getRequestedFormulaireInstance($controller, $label)
    {
        $id = $controller->params()->fromRoute($label);
        $instance = $this->getFormulaireInstance($id);
        return $instance;
    }

    /**
     * @return FormulaireInstance[]
     */
    public function getFormulairesInstances()
    {
        $qb = $this->getEntityManager()->getRepository(FormulaireInstance::class)->createQueryBuilder('formulaire_instance')
            ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Formulaire $formulaire
     * @return FormulaireInstance[]
     */
    public function getFormulairesInstancesByFormulaire($formulaire)
    {
        $qb = $this->getEntityManager()->getRepository(FormulaireInstance::class)->createQueryBuilder('formulaire_instance')
            ->andWhere('formulaire_instance.formulaire = :formulaire')
            ->setParameter('formulaire', $formulaire)
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return FormulaireInstance
     */
    public function getFormulaireInstance($id)
    {
        if ($id === null) return null;

        $qb = $this->getEntityManager()->getRepository(FormulaireInstance::class)->createQueryBuilder('formulaire_instance')
            ->andWhere('formulaire_instance.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs FormulaireInstance partagent le même identifiant [".$id."]", $e);
        }
        return $result;
    }

    /**
     * @param FormulaireInstance $instance
     * @return FormulaireInstance
     */
    public function create($instance)
    {
        try {
            $date = new DateTime();
        } catch (Exception $e) {
            throw new RuntimeException("Problème de récupération de la date", $e);
        }
        $user = $this->getUserService()->getConnectedUser();

        $instance->setHistoCreateur($user);
        $instance->setHistoCreation($date);
        $instance->setHistoModificateur($user);
        $instance->setHistoModification($date);

        try {
            $this->getEntityManager()->persist($instance);
            $this->getEntityManager()->flush($instance);
        } catch (ORMException $e) {
            throw new RuntimeException("Problème d'enregistrement en BD.", $e);
        }

        return $instance;
    }

    /**
     * @param FormulaireInstance $instance
     * @return FormulaireInstance
     */
    public function update($instance)
    {
        try {
            $date = new DateTime();
        } catch (Exception $e) {
            throw new RuntimeException("Problème de récupération de la date", $e);
        }
        $user = $this->getUserService()->getConnectedUser();

        $instance->setHistoModificateur($user);
        $instance->setHistoModification($date);

        try {
            $this->getEntityManager()->flush($instance);
        } catch (ORMException $e) {
            throw new RuntimeException("Problème d'enregistrement en BD.", $e);
        }

        return $instance;
    }

    /**
     * @param FormulaireInstance $instance
     * @return FormulaireInstance
     */
    public function historise($instance)
    {
        try {
            $date = new DateTime();
        } catch (Exception $e) {
            throw new RuntimeException("Problème de récupération de la date", $e);
        }
        $user = $this->getUserService()->getConnectedUser();

        $instance->setHistoDestructeur($user);
        $instance->setHistoDestruction($date);

        try {
            $this->getEntityManager()->flush($instance);
        } catch (ORMException $e) {
            throw new RuntimeException("Problème d'enregistrement en BD.", $e);
        }

        return $instance;
    }

    /**
     * @param FormulaireInstance $instance
     * @return FormulaireInstance
     */
    public function restore($instance)
    {
        $instance->setHistoDestructeur(null);
        $instance->setHistoDestruction(null);

        try {
            $this->getEntityManager()->flush($instance);
        } catch (ORMException $e) {
            throw new RuntimeException("Problème d'enregistrement en BD.", $e);
        }

        return $instance;
    }

    /**
     * @param FormulaireInstance $instance
     * @return FormulaireInstance
     */
    public function delete($instance)
    {

        try {
            $this->getEntityManager()->remove($instance);
            $this->getEntityManager()->flush();
        } catch (ORMException $e) {
            throw new RuntimeException("Problème d'enregistrement en BD.", $e);
        }

        return $instance;
    }


}