<?php

namespace FicheMetier\Service\FicheMetierConfigurationApplicationParDefaut;


use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Element\Entity\Db\Application;
use Element\Entity\Db\ApplicationElement;
use Element\Service\ApplicationElement\ApplicationElementServiceAwareTrait;
use FicheMetier\Entity\Db\FicheMetier;
use FicheMetier\Entity\Db\FicheMetierConfigurationApplicationParDefaut;
use FicheMetier\Service\FicheMetier\FicheMetierServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class FicheMetierConfigurationApplicationParDefautService
{
    use ProvidesObjectManager;
    use ApplicationElementServiceAwareTrait;
    use FicheMetierServiceAwareTrait;

    /** GESTION DES ENTITÉS ***********************************************/

    public function create(FicheMetierConfigurationApplicationParDefaut $applicationParDefaut): void
    {
        $this->getObjectManager()->persist($applicationParDefaut);
        $this->getObjectManager()->flush($applicationParDefaut);
    }

    public function update(FicheMetierConfigurationApplicationParDefaut $applicationParDefaut): void
    {
        $this->getObjectManager()->flush($applicationParDefaut);
    }

    public function historise(FicheMetierConfigurationApplicationParDefaut $applicationParDefaut): void
    {
        $applicationParDefaut->historiser();
        $this->getObjectManager()->flush($applicationParDefaut);
    }

    public function restore(FicheMetierConfigurationApplicationParDefaut $applicationParDefaut): void
    {
        $applicationParDefaut->dehistoriser();
        $this->getObjectManager()->flush($applicationParDefaut);
    }

    public function delete(FicheMetierConfigurationApplicationParDefaut $applicationParDefaut): void
    {
        $this->getObjectManager()->remove($applicationParDefaut);
        $this->getObjectManager()->flush($applicationParDefaut);
    }

    /** QUERYING *******************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(FicheMetierConfigurationApplicationParDefaut::class)->createQueryBuilder('applicationParDefaut')
            ->addSelect('application')->join('applicationParDefaut.application', 'application')
        ;
        return $qb;
    }

    public function getFicheMetierConfigurationApplicationParDefaut(?int $id): ?FicheMetierConfigurationApplicationParDefaut
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('applicationParDefaut.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".FicheMetierConfigurationApplicationParDefaut::class."] partagent le même id [".$id."]",0,$e);
        }
        return $result;
    }

    public function getRequestedFicheMetierConfigurationApplicationParDefaut(AbstractActionController $controller, string $param='application-par-defaut'): ?FicheMetierConfigurationApplicationParDefaut
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getFicheMetierConfigurationApplicationParDefaut((int) $id);
        return $result;
    }

    /** @return FicheMetierConfigurationApplicationParDefaut[] */
    public function getFicheMetierConfigurationApplicationsParDefaut(bool $withHisto = false): array
    {
        $qb = $this->createQueryBuilder();
        if (!$withHisto) {
            $qb = $qb->andWhere('applicationParDefaut.histoDestruction IS NULL');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** FACADE ********************************************************************************************************/

    public function hasApplication(?Application $application): bool
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('application.id = :id')->setParameter('id', $application->getId());
        ;
        $result = $qb->getQuery()->getResult();
        return !empty($result);
    }


    public function applyDefault(FicheMetier $ficheMetier): void
    {
        $applications = $this->getFicheMetierConfigurationApplicationsParDefaut();
        foreach ($applications as $application) {
            if (!$ficheMetier->hasApplication($application->getApplication())) {
                $element = new ApplicationElement();
                $element->setApplication($application->getApplication());
                $this->getApplicationElementService()->create($element);
                $ficheMetier->addApplicationElement($element);
            }
        }
        $this->getFicheMetierService()->update($ficheMetier);
    }
}
