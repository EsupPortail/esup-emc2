<?php

namespace Application\Service\EntretienProfessionnel;

use Application\Entity\Db\Agent;
use Application\Entity\Db\EntretienProfessionnel;
use Utilisateur\Service\User\UserServiceAwareTrait;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Controller\AbstractConsoleController;

class EntretienProfessionnelService {
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /**
     * @return EntretienProfessionnel
     * @var EntretienProfessionnel $entretien
     */
    public function create($entretien)
    {
        try {
            $date = new DateTime();
        } catch(Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération de la date", $e);
        }
        $user = $this->getUserService()->getConnectedUser();

        $entretien->setHistoCreation($date);
        $entretien->setHistoCreateur($user);
        $entretien->setHistoModification($date);
        $entretien->setHistoModificateur($user);

        try {
            $this->getEntityManager()->persist($entretien);
            $this->getEntityManager()->flush($entretien);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement d'une EntretienProfessionnel", $e);
        }
        return $entretien;
    }

    /**
     * @return EntretienProfessionnel
     * @var EntretienProfessionnel $entretien
     */
    public function update($entretien)
    {
        try {
            $date = new DateTime();
        } catch(Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération de la date", $e);
        }
        $user = $this->getUserService()->getConnectedUser();

        $entretien->setHistoModification($date);
        $entretien->setHistoModificateur($user);

        try {
            $this->getEntityManager()->flush($entretien);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement d'une EntretienProfessionnel", $e);
        }
        return $entretien;
    }

    /**
     * @return EntretienProfessionnel
     * @var EntretienProfessionnel $entretien
     */
    public function historise($entretien)
    {
        try {
            $date = new DateTime();
        } catch(Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération de la date", $e);
        }
        $user = $this->getUserService()->getConnectedUser();

        $entretien->setHistoDestruction($date);
        $entretien->setHistoDestructeur($user);

        try {
            $this->getEntityManager()->flush($entretien);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement d'une EntretienProfessionnel", $e);
        }
        return $entretien;
    }

    /**
     * @return EntretienProfessionnel
     * @var EntretienProfessionnel $entretien
     */
    public function restore($entretien)
    {
        try {
            $date = new DateTime();
        } catch(Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération de la date", $e);
        }
        $user = $this->getUserService()->getConnectedUser();

        $entretien->setHistoModification($date);
        $entretien->setHistoModificateur($user);
        $entretien->setHistoDestruction(null);
        $entretien->setHistoDestructeur(null);

        try {
            $this->getEntityManager()->flush($entretien);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement d'une EntretienProfessionnel", $e);
        }
        return $entretien;
    }

    /**
     * @return EntretienProfessionnel
     * @var EntretienProfessionnel $entretien
     */
    public function delete($entretien)
    {
        try {
            $this->getEntityManager()->remove($entretien);
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'effacement d'une EntretienProfessionnel", $e);
        }
        return $entretien;
    }

    /**
     * @return EntretienProfessionnel[]
     */
    public function getEntretiensProfessionnels()
    {
        $qb = $this->getEntityManager()->getRepository(EntretienProfessionnel::class)->createQueryBuilder('entretien')
            ->orderBy('entretien.id');
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return EntretienProfessionnel
     */
    public function getEntretienProfessionnel($id)
    {
        $qb = $this->getEntityManager()->getRepository(EntretienProfessionnel::class)->createQueryBuilder('entretien')
            ->andWhere('entretien.id = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs EntretienProfessionnel partagent le même identifiant [".$id."]", $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return EntretienProfessionnel
     */
    public function getRequestedEntretienProfessionnel($controller, $paramName)
    {
        $id = $controller->params()->fromRoute($paramName);
        $entretien = $this->getEntretienProfessionnel($id);
        return $entretien;
    }

    /**
     * @param Agent $agent
     * @return EntretienProfessionnel[]
     */
    public function getEntretiensProfessionnelsParAgent($agent)
    {
        $qb = $this->getEntityManager()->getRepository(EntretienProfessionnel::class)->createQueryBuilder('entretien')
            ->andWhere('entretien.agent = :agent')
            ->setParameter('agent', $agent)
            ->orderBy('entretien.annee, entretien.id', 'ASC')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

}