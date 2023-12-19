<?php

namespace Formation\Service\Presence;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Formation\Entity\Db\FormationInstance;
use Formation\Entity\Db\Inscription;
use Formation\Entity\Db\Presence;
use Formation\Entity\Db\Seance;
use Laminas\Mvc\Controller\AbstractActionController;
use UnicaenApp\Exception\RuntimeException;

class PresenceService
{
    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Presence $presence
     * @return Presence
     */
    public function create(Presence $presence): Presence
    {
        $this->getObjectManager()->persist($presence);
        $this->getObjectManager()->flush($presence);
        return $presence;
    }

    /**
     * @param Presence $presence
     * @return Presence
     */
    public function update(Presence $presence): Presence
    {
        $this->getObjectManager()->flush($presence);
        return $presence;
    }

    /**
     * @param Presence $presence
     * @return Presence
     */
    public function historise(Presence $presence): Presence
    {
        $presence->historiser();
        $this->getObjectManager()->flush($presence);
        return $presence;
    }

    /**
     * @param Presence $presence
     * @return Presence
     */
    public function restore(Presence $presence): Presence
    {
        $presence->dehistoriser();
        $this->getObjectManager()->flush($presence);
        return $presence;
    }

    /**
     * @param Presence $presence
     * @return Presence
     */
    public function delete(Presence $presence): Presence
    {
        $this->getObjectManager()->remove($presence);
        $this->getObjectManager()->flush($presence);
        return $presence;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(Presence::class)->createQueryBuilder('presence')
            ->addSelect('journee')->join('presence.journee', 'journee')
            ->addSelect('finstance')->join('journee.instance', 'finstance')
            ->addSelect('formation')->join('finstance.formation', 'formation')
            ->addSelect('inscription')->join('presence.inscription', 'inscription');
        return $qb;
    }

    /**
     * @param integer|null $id
     * @return Presence|null
     */
    public function getPresence(?int $id): ?Presence
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('presence.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Presence partagent le même id [" . $id . "].", 0, $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return Presence|null
     */
    public function getRequestedPresence(AbstractActionController $controller, string $param = 'presence'): ?Presence
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getPresence($id);
    }

    public function getPresenceByJourneeAndInscription(Seance $journee, Inscription $inscription): ?Presence
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('presence.journee = :journee')
            ->andWhere('presence.inscription = :inscription')
            ->setParameter('journee', $journee)
            ->setParameter('inscription', $inscription)
            ->andWhere('presence.histoDestruction IS NULL');

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Presence (non historisées) partagent la même journée [" . $journee->getId() . "] et le même inscrit [" . $inscription->getId() . "]", 0, $e);
        }
        return $result;
    }

    /**
     * @param FormationInstance $instance
     * @return Presence[]
     */
    public function getPresenceByInstance(FormationInstance $instance): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('journee.instance = :instance')
            ->setParameter('instance', $instance);

        return $qb->getQuery()->getResult();

    }

    /**
     * @return Presence[]
     */
    public function getPresences(): array
    {
        $qb = $this->getObjectManager()->getRepository(Presence::class)->createQueryBuilder('presence');
        $result = $qb->getQuery()->getResult();
        return $result;
    }


}