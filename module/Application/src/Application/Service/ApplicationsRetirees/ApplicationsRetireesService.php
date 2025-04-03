<?php

namespace Application\Service\ApplicationsRetirees;

use Application\Entity\Db\FichePoste;
use Application\Entity\Db\FicheposteApplicationRetiree;
use Doctrine\ORM\NonUniqueResultException;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Element\Entity\Db\Application;
use RuntimeException;

class ApplicationsRetireesService
{
    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(FicheposteApplicationRetiree $applicationConservee): FicheposteApplicationRetiree
    {
        $this->getObjectManager()->persist($applicationConservee);
        $this->getObjectManager()->flush($applicationConservee);
        return $applicationConservee;
    }

    public function update(FicheposteApplicationRetiree $applicationConservee): FicheposteApplicationRetiree
    {
        $this->getObjectManager()->flush($applicationConservee);
        return $applicationConservee;
    }

    public function delete(FicheposteApplicationRetiree $applicationConservee): FicheposteApplicationRetiree
    {
        $this->getObjectManager()->remove($applicationConservee);
        $this->getObjectManager()->flush($applicationConservee);
        return $applicationConservee;
    }

    /** ACCESSEUR *****************************************************************************************************/

    public function getApplicationRetiree(FichePoste $ficheposte, Application $application): ?FicheposteApplicationRetiree
    {
        $qb = $this->getObjectManager()->getRepository(FicheposteApplicationRetiree::class)->createQueryBuilder('retiree')
            ->andWhere('retiree.fichePoste = :ficheposte')
            ->andWhere('retiree.application = :application')
            ->setParameter('ficheposte', $ficheposte)
            ->setParameter('application', $application);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs ApplicationRetirees ...", 0, $e);
        }
        return $result;

    }

    /**
     * @param FichePoste $ficheposte
     * @param Application $application
     * @return FicheposteApplicationRetiree
     */
    public function add(FichePoste $ficheposte, Application $application): FicheposteApplicationRetiree
    {
        $result = $this->getApplicationRetiree($ficheposte, $application);

        if ($result === null) {
            $result = new FicheposteApplicationRetiree();
            $result->setFichePoste($ficheposte);
            $result->setApplication($application);
            $this->create($result);
        }
        return $result;
    }

    /**
     * @param FichePoste $ficheposte
     * @param Application $application
     * @return FicheposteApplicationRetiree
     */
    public function remove(FichePoste $ficheposte, Application $application): FicheposteApplicationRetiree
    {
        $result = $this->getApplicationRetiree($ficheposte, $application);

        if ($result !== null) {
            $this->delete($result);
        }
        return $result;
    }

}