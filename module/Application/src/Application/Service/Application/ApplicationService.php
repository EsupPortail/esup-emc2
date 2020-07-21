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
    public function create($application)
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
    public function update($application)
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
     */
    public function delete($application)
    {
        try {
            $this->getEntityManager()->remove($application);
            $this->getEntityManager()->flush();
        } catch (ORMException $e) {
            throw new RuntimeException('Un problème est survenu lors de la suppression en BD', $e);
        }
    }

    /** REQUETES ******************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
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
    public function getApplications($champ = 'libelle', $ordre='ASC')
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
    public function getApplicationsAsOptions($champ = 'libelle', $ordre='ASC')
    {
        $result = $this->getApplications($champ, $ordre);
        $array = [];
        foreach ($result as $item) {
            $array[$item->getId()] = $item->getLibelle();
        }

        return $array;
    }

    /**
     * @param ApplicationGroupe $groupe
     * @return Application[]
     */
    public function getApplicationsGyGroupe($groupe)
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
     * @param int $id
     * @return Application
     */
    public function getApplication($id)
    {
        $qb = $this->getEntityManager()->getRepository(Application::class)->createQueryBuilder('application')
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
    public function getRequestedApplication($controller, $paramName)
    {
        $id = $controller->params()->fromRoute($paramName);
        return $this->getApplication($id);
    }
}