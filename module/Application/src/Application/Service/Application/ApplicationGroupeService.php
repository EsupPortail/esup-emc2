<?php

namespace Application\Service\Application;

use Application\Entity\Db\ApplicationGroupe;
use Application\Service\RendererAwareTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class ApplicationGroupeService {
    use EntityManagerAwareTrait;
    use RendererAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param ApplicationGroupe $groupe
     * @return ApplicationGroupe
     */
    public function create(ApplicationGroupe $groupe) : ApplicationGroupe
    {
        try {
            $this->getEntityManager()->persist($groupe);
            $this->getEntityManager()->flush($groupe);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $groupe;
    }

    /**
     * @param ApplicationGroupe $groupe
     * @return ApplicationGroupe
     */
    public function update(ApplicationGroupe $groupe) : ApplicationGroupe
    {
        try {
            $this->getEntityManager()->flush($groupe);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $groupe;
    }

    /**
     * @param ApplicationGroupe $groupe
     * @return ApplicationGroupe
     */
    public function historise(ApplicationGroupe $groupe) : ApplicationGroupe
    {
        try {
            $groupe->historiser();
            $this->getEntityManager()->flush($groupe);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $groupe;
    }

    /**
     * @param ApplicationGroupe $groupe
     * @return ApplicationGroupe
     */
    public function restore(ApplicationGroupe $groupe) : ApplicationGroupe
    {
        try {
            $groupe->dehistoriser();
            $this->getEntityManager()->flush($groupe);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $groupe;
    }

    /**
     * @param ApplicationGroupe $groupe
     * @return ApplicationGroupe
     */
    public function delete(ApplicationGroupe $groupe) : ApplicationGroupe
    {
        try {
            $this->getEntityManager()->remove($groupe);
            $this->getEntityManager()->flush($groupe);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $groupe;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(ApplicationGroupe::class)->createQueryBuilder('groupe')
             ->addSelect('application')->leftJoin('groupe.applications', 'application')
            ;
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return ApplicationGroupe[]
     */
    public function getApplicationsGroupes($champ = 'ordre', $ordre='ASC')
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('groupe.' . $champ, $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param ApplicationGroupe $groupe
     * @return array
     */
    public function optionify(ApplicationGroupe $groupe) {
        $this_option = [
            'value' =>  $groupe->getId(),
            'label' => $groupe->getLibelle(),
        ];
        return $this_option;
    }

    /**
     * @return array
     */
    public function getApplicationsGroupesAsOption()
    {
        $groupes = $this->getApplicationsGroupes();
        $array = [];
        foreach ($groupes as $groupe) {
            $option = $this->optionify($groupe);
            $array[$groupe->getId()] = $option;
        }
        return $array;
    }

    /**
     * @param integer $id
     * @return ApplicationGroupe
     */
    public function getApplicationGroupe(int $id)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('groupe.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs ApplicationGroupe paratagent le même id [".$id."]");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return ApplicationGroupe
     */
    public function getRequestedApplicationGroupe(AbstractActionController $controller, $param = 'application-groupe')
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getApplicationGroupe($id);
        return $result;
    }
}