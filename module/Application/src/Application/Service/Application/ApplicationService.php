<?php

namespace Application\Service\Application;

use Application\Entity\Db\Application;
use Application\Entity\Db\ApplicationGroupe;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class ApplicationService {
    use EntityManagerAwareTrait;

    /** GESTION DE L'ENTITÉ *******************************************************************************************/

    /**
     * @param Application $application
     * @return Application
     */
    public function create(Application $application) : Application
    {
        $application->setActif(true);
        try {
            $this->getEntityManager()->persist($application);
            $this->getEntityManager()->flush($application);
        } catch (ORMException $e) {
            throw new RuntimeException('Un problème est survenu lors de la création en BD', $e);
        }
        return $application;
    }

    /**
     * @param Application $application
     * @return Application
     */
    public function update(Application $application) : Application
    {
        try {
            $this->getEntityManager()->flush($application);
        } catch (ORMException $e) {
            throw new RuntimeException('Un problème est survenu lors de la mise à jour en BD', $e);
        }
        return $application;
    }

    /**
     * @param Application $application
     * @return Application
     */
    public function delete(Application $application) : Application
    {
        try {
            $this->getEntityManager()->remove($application);
            $this->getEntityManager()->flush();
        } catch (ORMException $e) {
            throw new RuntimeException('Un problème est survenu lors de la suppression en BD', $e);
        }
        return $application;
    }

    /** REQUETES ******************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(Application::class)->createQueryBuilder('application')
            ->addSelect('groupe')->leftJoin('application.groupe', 'groupe')
        ;
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return Application[]
     */
    public function getApplications(string $champ = 'libelle', string $ordre='ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('application.' . $champ, $ordre)
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return Application[]
     */
    public function getApplicationsAsOptions(string $champ = 'libelle', string $ordre='ASC') : array
    {
        $result = $this->getApplications($champ, $ordre);
        $array = [];
        foreach ($result as $item) {
            $array[$item->getId()] = $item->getLibelle();
        }

        return $array;
    }

    /**
     * @param ApplicationGroupe|null $groupe
     * @return Application[]
     */
    public function getApplicationsGyGroupe(?ApplicationGroupe $groupe) : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('application.libelle')
        ;
        if ($groupe) {
            $qb = $qb->andWhere('groupe.id = :groupeId')
                ->setParameter('groupeId', $groupe->getId())
            ;
        } else {
            $qb = $qb->andWhere('groupe IS NULL')
            ;
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param int|null $id
     * @return Application|null
     */
    public function getApplication(?int $id) : ?Application
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('application.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException('Plusieurs applications portent le même identifiant ['.$id.']', $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string paramName
     * @return Application
     */
    public function getRequestedApplication(AbstractActionController $controller, string $paramName = 'application') : ?Application
    {
        $id = $controller->params()->fromRoute($paramName);
        return $this->getApplication($id);
    }
}