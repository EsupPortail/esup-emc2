<?php

namespace Indicateur\Service\Abonnement;

use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Indicateur\Entity\Db\Abonnement;
use Mailing\Service\Mailing\MailingServiceAwareTrait;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Utilisateur\Entity\Db\User;
use Zend\Mvc\Controller\AbstractActionController;

class AbonnementService {
    use EntityManagerAwareTrait;
    use MailingServiceAwareTrait;

    /**
     * @param string $attribut
     * @param string $ordre
     * @return Abonnement[]
     */
    public function getAbonnements($attribut = 'id', $ordre = 'ASC')
    {
        $qb = $this->getEntityManager()->getRepository(Abonnement::class)->createQueryBuilder('abonnement')
            ->orderBy('abonnement.' . $attribut, $ordre)
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param $id
     * @return Abonnement
     */
    public function getAbonnement($id)
    {
        $qb = $this->getEntityManager()->getRepository(Abonnement::class)->createQueryBuilder('abonnement')
            ->andWhere('abonnement.id = :id')
            ->setParameter('id', $id)
            ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Abonnement partagent le même id [".$id."]",$e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return Abonnement
     */
    public function getRequestedAbonnement($controller, $paramName='abonnement')
    {
        $id = $controller->params()->fromRoute($paramName);
        return $this->getAbonnement($id);
    }

    /**
     * @param Abonnement $abonnement
     * @return Abonnement
     */
    public function create($abonnement)
    {
        try {
            $this->getEntityManager()->persist($abonnement);
            $this->getEntityManager()->flush($abonnement);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en base.", $e);
        }
        return $abonnement;
    }

    /**
     * @param Abonnement $abonnement
     * @return Abonnement
     */
    public function update($abonnement)
    {
        try {
            $this->getEntityManager()->flush($abonnement);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en base.", $e);
        }
        return $abonnement;
    }

    /**
     * @param Abonnement $abonnement
     * @return Abonnement
     */
    public function delete($abonnement)
    {
        try {
            $this->getEntityManager()->remove($abonnement);
            $this->getEntityManager()->flush($abonnement);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en base.", $e);
        }
        return $abonnement;
    }

    /**
     * @param User
     * @return Abonnement[]
     */
    public function getAbonnementsByUser($user)
    {
        $qb = $this->getEntityManager()->getRepository(Abonnement::class)->createQueryBuilder('abonnement')
            ->andWhere('abonnement.user = :user')
            ->setParameter('user', $user)
            ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Abonnement $abonnement
     * @param string $titre
     * @param string $texte
     * @param DateTime $date
     */
    public function notify($abonnement, $titre, $texte, $date)
    {
        $mail = $abonnement->getUser()->getEmail();
        $this->getMailingService()->sendMail($mail, $titre, $texte);
        $abonnement->setDernierEnvoi($date);
    }
}