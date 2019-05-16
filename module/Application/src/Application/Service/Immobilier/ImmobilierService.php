<?php

namespace Application\Service\Immobilier;

use Application\Entity\Db\Batiment;
use Application\Entity\Db\Site;
use Doctrine\ORM\NonUniqueResultException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class ImmobilierService {
    use EntityManagerAwareTrait;

    /** Site **********************************************************************************************************/

    /**
     * @return Site[]
     */
    public function getSites()
    {
        $qb = $this->getEntityManager()->getRepository(Site::class)->createQueryBuilder('site')
            ->addSelect('batiment')->leftJoin('site.batiments','batiment')
            ->orderBy('site.libelle')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @return array
     */
    public function getSitesAsOptions()
    {
        $sites = $this->getSites();
        $options = [];
        foreach ($sites as $site) {
            $options[$site->getId()] = $site->getLibelle();
        }
        return $options;
    }

    /**
     * @param string $id
     * @return Site
     */
    public function getSite($id)
    {
        $qb = $this->getEntityManager()->getRepository(Site::class)->createQueryBuilder('site')
            ->addSelect('batiment')->leftJoin('site.batiments','batiment')
            ->andWhere('site.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException('Plusieurs Site partagent le même id ['.$id.']', $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return Site
     */
    public function getRequestedSite($controller, $paramName)
    {
        $id = $controller->params()->fromRoute($paramName);
        $site = $this->getSite($id);
        return $site;
    }

    /** Batiment ******************************************************************************************************/

    /**
     * @return Batiment[]
     */
    public function getBatiments()
    {
        $qb = $this->getEntityManager()->getRepository(Batiment::class)->createQueryBuilder('batiment')
            ->addSelect('site')->leftJoin('batiment.site','site')
            ->orderBy('batiment.libelle')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @return array
     */
    public function getBatimentsAsOptions()
    {
        $batiments = $this->getBatiments();
        $options = [];
        foreach ($batiments as $batiment) {
            $options[$batiment->getId()] = $batiment->getLibelle();
        }
        return $options;
    }

    /**
     * @param string $id
     * @return Batiment
     */
    public function getBatiment($id)
    {
        $qb = $this->getEntityManager()->getRepository(Batiment::class)->createQueryBuilder('batiment')
            ->addSelect('site')->leftJoin('batiment.site','site')
            ->andWhere('batiment.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException('Plusieurs Batiment partagent le même id ['.$id.']', $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return Batiment
     */
    public function getRequestedBatiment($controller, $paramName)
    {
        $id = $controller->params()->fromRoute($paramName);
        $batiment = $this->getBatiment($id);
        return $batiment;
    }
}