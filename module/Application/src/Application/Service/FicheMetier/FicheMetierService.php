<?php

namespace Application\Service\FicheMetier;

use Application\Entity\Db\FicheMetier;
use Application\Service\User\UserServiceAwareTrait;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

class FicheMetierService {
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /**
     * @param string $order an attribute use to sort
     * @return FicheMetier[]
     */
    public function getFichesMetiers($order = 'id')
    {
        $qb = $this->getEntityManager()->getRepository(FicheMetier::class)->createQueryBuilder('ficheMetier')
            ->orderBy('ficheMetier.', $order)
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param int $id
     * @return FicheMetier
     */
    public function getFicheMetier($id)
    {
        $qb = $this->getEntityManager()->getRepository(FicheMetier::class)->createQueryBuilder('ficheMetier')
            ->andWhere('ficheMetier.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs fiches métiers portent le même identifiant [".$id."].");
        }
        return $result;
    }

    /**
     * @param FicheMetier $fiche
     * @return FicheMetier
     */
    public function historiser($fiche) {
        //TODO récupérer l'utilisateur connecté
        $utilisateur = null;
        $fiche->historiser($utilisateur);
        try {
            $this->getEntityManager()->flush($fiche);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de l'historsation de la fiche métier [".$fiche->getId()."].");
        }
        return $fiche;
    }

    /**
     * @param FicheMetier $fiche
     * @return FicheMetier
     */
    public function restaurer($fiche) {
        $fiche->dehistoriser();
        try {
            $this->getEntityManager()->flush($fiche);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de la restauration de la fiche métier [".$fiche->getId()."].");
        }
        return $fiche;
    }

    /**
     * @param FicheMetier $fiche
     * @return FicheMetier
     */
    public function creer($fiche)
    {
        $connectedUtilisateur = $this->getUserService()->getConnectedUser();

        $fiche->setHistoCreation(new DateTime());
        $fiche->setHistoCreateur($connectedUtilisateur);
        $fiche->setHistoModification(new DateTime());
        $fiche->setHistoModificateur($connectedUtilisateur);
        $this->getEntityManager()->persist($fiche);
        try {
            $this->getEntityManager()->flush($fiche);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de la création de la fiche.");
        }

        return $fiche;
    }
}