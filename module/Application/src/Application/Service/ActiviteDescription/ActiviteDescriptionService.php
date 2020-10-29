<?php

namespace Application\Service\ActiviteDescription;

use Application\Entity\Db\ActiviteDescription;
use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class ActiviteDescriptionService {
    use GestionEntiteHistorisationTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param ActiviteDescription $description
     * @return ActiviteDescription
     */
    public function create(ActiviteDescription  $description)
    {
        $this->createFromTrait($description);
        return $description;
    }

    /**
     * @param ActiviteDescription $description
     * @return ActiviteDescription
     */
    public function update(ActiviteDescription $description)
    {
        $this->updateFromTrait($description);
        return $description;
    }

    /**
     * @param ActiviteDescription $description
     * @return ActiviteDescription
     */
    public function historise(ActiviteDescription $description)
    {
        $this->historiserFromTrait($description);
        return $description;
    }

    /**
     * @param ActiviteDescription $description
     * @return ActiviteDescription
     */
    public function restore(ActiviteDescription $description)
    {
        $this->restoreFromTrait($description);
        return $description;
    }

    /**
     * @param ActiviteDescription $description
     * @return ActiviteDescription
     */
    public function delete(ActiviteDescription $description)
    {
        $this->deleteFromTrait($description);
        return $description;
    }

    /** ACCESSEUR *****************************************************************************************************/

    public function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(ActiviteDescription::class)->createQueryBuilder('description')
            ->addSelect('activite')->join('description.activite', 'activite')
        ;
        return $qb;
    }

    /**
     * @param integer $id
     * @return ActiviteDescription
     */
    public function getActiviteDescription(int $id)
    {
        $qb = $this->createQueryBuilder()
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
    public function getRequestedActiviteDescription(AbstractActionController $controller, $param = 'description')
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getActiviteDescription($id);
        return $result;
    }
}