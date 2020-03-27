<?php

namespace Application\Service\ActiviteDescription;

use Application\Entity\Db\ActiviteDescription;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class ActiviteDescriptionService {
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/
    /**
     * @param ActiviteDescription $description
     * @return ActiviteDescription
     */
    public function create(ActiviteDescription $description)
    {
        $description->updateCreation($this->getUserService());
        $description->updateModification($this->getUserService());

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
        $description->updateModification($this->getUserService());

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
        $description->updateDestructeur($this->getUserService());

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
}