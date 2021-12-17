<?php

namespace Application\Service\ApplicationsRetirees;

use Application\Entity\Db\Application;
use Application\Entity\Db\FichePoste;
use Application\Entity\Db\FicheposteApplicationRetiree;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

class ApplicationsRetireesService {
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param FicheposteApplicationRetiree $applicationConservee
     * @return FicheposteApplicationRetiree
     */
    public function create(FicheposteApplicationRetiree $applicationConservee) : FicheposteApplicationRetiree
    {
        try {
            $this->getEntityManager()->persist($applicationConservee);
            $this->getEntityManager()->flush($applicationConservee);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en base.", 0 , $e);
        }

        return $applicationConservee;
    }

    /**
     * @param FicheposteApplicationRetiree $applicationConservee
     * @return FicheposteApplicationRetiree
     */
    public function update(FicheposteApplicationRetiree $applicationConservee) : FicheposteApplicationRetiree
    {
        try {
            $this->getEntityManager()->flush($applicationConservee);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en base.", 0 , $e);
        }

        return $applicationConservee;
    }

    /**
     * @param FicheposteApplicationRetiree $applicationConservee
     * @return FicheposteApplicationRetiree
     */
    public function delete(FicheposteApplicationRetiree $applicationConservee) : FicheposteApplicationRetiree
    {
        try {
            $this->getEntityManager()->remove($applicationConservee);
            $this->getEntityManager()->flush($applicationConservee);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en base.", 0 , $e);
        }

        return $applicationConservee;
    }

    /** ACCESSEUR *****************************************************************************************************/

    /**
     * @param FichePoste $ficheposte
     * @param Application $application
     * @return FicheposteApplicationRetiree
     */
    public function getApplicationRetiree(FichePoste $ficheposte, Application $application) : FicheposteApplicationRetiree
    {
        $qb = $this->getEntityManager()->getRepository(FicheposteApplicationRetiree::class)->createQueryBuilder('retiree')
            ->andWhere('retiree.fichePoste = :ficheposte')
            ->andWhere('retiree.application = :application')
            ->setParameter('ficheposte', $ficheposte)
            ->setParameter('application', $application);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs ApplicationRetirees ...",0,$e);
        }
        return $result;

    }

    /**
     * @param FichePoste $ficheposte
     * @param Application $application
     * @return FicheposteApplicationRetiree
     */
    public function add(FichePoste $ficheposte, Application $application) : FicheposteApplicationRetiree
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
    public function remove(FichePoste $ficheposte, Application $application) : FicheposteApplicationRetiree
    {
        $result = $this->getApplicationRetiree($ficheposte, $application);

        if ($result !== null) {
            $this->delete($result);
        }
        return $result;
    }

}