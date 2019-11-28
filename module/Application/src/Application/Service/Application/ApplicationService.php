<?php

namespace Application\Service\Application;

use Application\Entity\Db\Application;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class ApplicationService {
    use EntityManagerAwareTrait;

    /**
     * @param string $ordre nom de champ présent dans l'entité
     * @return Application[]
     */
    public function getApplications($ordre = null)
    {
        $qb = $this->getEntityManager()->getRepository(Application::class)->createQueryBuilder('application')
        ;
        if ($ordre) $qb = $qb->orderBy('application.' . $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $ordre nom de champ présent dans l'entité
     * @return Application[]
     */
    public function getApplicationsAsOptions($ordre = null)
    {
        $result = $this->getApplications($ordre);
        $array = [];
        foreach ($result as $item) {
            $array[$item->getId()] = $item->getLibelle();
        }

        return $array;
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

    /**
     * @param Application $application
     * @return Application
     */
    public function create($application)
    {
        $application->setActif(true);
        $this->getEntityManager()->persist($application);
        try {
            $this->getEntityManager()->flush($application);
        } catch (OptimisticLockException $e) {
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
        } catch (OptimisticLockException $e) {
            throw new RuntimeException('Un problème est survenu lors de la mise à jour en BD', $e);
        }
        return $application;
    }

    /**
     * @param Application $application
     */
    public function delete($application)
    {
        $this->getEntityManager()->remove($application);
        try {
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
            throw new RuntimeException('Un problème est survenu lors de la suppression en BD', $e);
        }
    }
}