<?php

namespace Element\Service\Application;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Element\Entity\Db\Application;
use Element\Entity\Db\ApplicationTheme;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class ApplicationService
{
    use ProvidesObjectManager;

    /** GESTION DE L'ENTITÉ *******************************************************************************************/

    public function create(Application $application): Application
    {
        $application->setActif(true);
        $this->getObjectManager()->persist($application);
        $this->getObjectManager()->flush($application);
        return $application;
    }

    public function update(Application $application): Application
    {
        $this->getObjectManager()->flush($application);
        return $application;
    }

    public function historise(Application $application): Application
    {
        $application->historiser();
        $this->getObjectManager()->flush($application);
        return $application;
    }

    public function restore(Application $application): Application
    {
        $application->dehistoriser();
        $this->getObjectManager()->flush($application);
        return $application;
    }

    public function delete(Application $application): Application
    {
        $this->getObjectManager()->remove($application);
        $this->getObjectManager()->flush();
        return $application;
    }

    /** REQUETES ******************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(Application::class)->createQueryBuilder('application')
            ->addSelect('groupe')->leftJoin('application.groupe', 'groupe');
        return $qb;
    }

    /** @return Application[] */
    public function getApplications(string $champ = 'libelle', string $ordre = 'ASC'): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('application.' . $champ, $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getApplicationsAsOptions(string $champ = 'libelle', string $ordre = 'ASC'): array
    {
        $result = $this->getApplications($champ, $ordre);
        $array = [];
        foreach ($result as $item) {
            $array[$item->getId()] = $item->getLibelle();
        }

        return $array;
    }

    /**  @return Application[] */
    public function getApplicationsGyGroupe(?ApplicationTheme $groupe): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('application.libelle');
        if ($groupe) {
            $qb = $qb->andWhere('groupe.id = :groupeId')
                ->setParameter('groupeId', $groupe->getId());
        } else {
            $qb = $qb->andWhere('groupe IS NULL');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getApplication(?int $id): ?Application
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('application.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException('Plusieurs applications portent le même identifiant [' . $id . ']', 0, $e);
        }
        return $result;
    }

    public function getRequestedApplication(AbstractActionController $controller, string $paramName = 'application'): ?Application
    {
        $id = $controller->params()->fromRoute($paramName);
        return $this->getApplication($id);
    }
}