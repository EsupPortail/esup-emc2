<?php

namespace EntretienProfessionnel\Service\CampagneConfigurationIndicateur;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use EntretienProfessionnel\Entity\Db\CampagneConfigurationIndicateur;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class CampagneConfigurationIndicateurService {
    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(CampagneConfigurationIndicateur $campagneConfigurationIndicateur): void
    {
        $this->getObjectManager()->persist($campagneConfigurationIndicateur);
        $this->getObjectManager()->flush($campagneConfigurationIndicateur);
    }

    public function update(CampagneConfigurationIndicateur $campagneConfigurationIndicateur): void
    {
        $this->getObjectManager()->flush($campagneConfigurationIndicateur);
    }

    public function delete(CampagneConfigurationIndicateur $campagneConfigurationIndicateur): void
    {
        $this->getObjectManager()->remove($campagneConfigurationIndicateur);
        $this->getObjectManager()->flush($campagneConfigurationIndicateur);
    }

    public function historise(CampagneConfigurationIndicateur $campagneConfigurationIndicateur): void
    {
        $campagneConfigurationIndicateur->historiser();
        $this->getObjectManager()->flush($campagneConfigurationIndicateur);
    }

    public function restore(CampagneConfigurationIndicateur $campagneConfigurationIndicateur): void
    {
        $campagneConfigurationIndicateur->dehistoriser();
        $this->getObjectManager()->flush($campagneConfigurationIndicateur);
    }

    /** QUERRYING *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(CampagneConfigurationIndicateur::class)->createQueryBuilder('configuration');
        return $qb;
    }

    public function getCampagneConfigurationIndicateur(?int $id): ?CampagneConfigurationIndicateur
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('configuration.id = :id')->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".CampagneConfigurationIndicateur::class."] partagent le même id [".$id."]",0, $e);
        }
        return $result;
    }

    public function getRequestedCampagneConfigurationIndicateur(AbstractActionController $controller, string $param='campagne-configuration-indicateur'): ?CampagneConfigurationIndicateur
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getCampagneConfigurationIndicateur($id);
        return $result;
    }

    /** @return  CampagneConfigurationIndicateur[] */
    public function getCampagneConfigurationIndicateurs(bool $withHisto = false): array
    {
        $qb = $this->createQueryBuilder();
        if (!$withHisto) $qb = $qb->andWhere('configuration.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }
}
