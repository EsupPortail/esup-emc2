<?php

namespace Application\Service\ActiviteDescription;

use Application\Entity\Db\ActiviteDescription;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Entity\DateTimeAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class ActiviteDescriptionService {
    use DateTimeAwareTrait;
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/
    /**
     * @param ActiviteDescription $description
     * @return ActiviteDescription
     */
    public function create(ActiviteDescription $description)
    {
        $date = $this->getDateTime();
        $user = $this->getUserService()->getConnectedUser();

        $description->setHistoCreation($date);
        $description->setHistoCreateur($user);
        $description->setHistoModification($date);
        $description->setHistoModificateur($user);

        try {
            $this->getEntityManager()->persist($description);
            $this->getEntityManager()->flush($description);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregirstrement en base",0, $e);
        }
        return $description;
    }

    /**
     * @param ActiviteDescription $description
     * @return ActiviteDescription
     */
    public function update(ActiviteDescription $description)
    {
        $date = $this->getDateTime();
        $user = $this->getUserService()->getConnectedUser();

        $description->setHistoModification($date);
        $description->setHistoModificateur($user);

        try {
            $this->getEntityManager()->flush($description);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregirstrement en base",0, $e);
        }
        return $description;
    }

    /**
     * @param ActiviteDescription $description
     * @return ActiviteDescription
     */
    public function historise(ActiviteDescription $description)
    {
        $date = $this->getDateTime();
        $user = $this->getUserService()->getConnectedUser();

        $description->setHistoDestruction($date);
        $description->setHistoDestructeur($user);

        try {
            $this->getEntityManager()->flush($description);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregirstrement en base",0, $e);
        }
        return $description;
    }

    /**
     * @param ActiviteDescription $description
     * @return ActiviteDescription
     */
    public function restore(ActiviteDescription $description)
    {
        $description->setHistoDestruction(null);
        $description->setHistoDestructeur(null);

        try {
            $this->getEntityManager()->flush($description);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregirstrement en base",0, $e);
        }
        return $description;
    }

    /**
     * @param ActiviteDescription $description
     * @return ActiviteDescription
     */
    public function delete(ActiviteDescription $description)
    {
        try {
            $this->getEntityManager()->remove($description);
            $this->getEntityManager()->flush($description);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregirstrement en base",0, $e);
        }
        return $description;
    }

    /** ACCESSEUR *****************************************************************************************************/

    /**
     * @param integer $id
     * @return ActiviteDescription
     */
    public function getActiviteDescription($id)
    {
        $qb = $this->getEntityManager()->getRepository(ActiviteDescription::class)->createQueryBuilder('description')
            ->andWhere('description.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs ActiviteDescription partagent le même id [".$id."]", 0 , $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return ActiviteDescription
     */
    public function getRequestedActiviteDescription($controller, $param = 'description')
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getActiviteDescription($id);
        return $result;
    }
}