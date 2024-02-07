<?php

namespace Formation\Service\Formateur;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Formation\Entity\Db\Formateur;
use UnicaenApp\Exception\RuntimeException;
use Laminas\Mvc\Controller\AbstractActionController;

class FormateurService
{
    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Formateur $formateur
     * @return Formateur
     */
    public function create(Formateur $formateur) : Formateur
    {
        $this->getObjectManager()->persist($formateur);
        $this->getObjectManager()->flush($formateur);
        return $formateur;
    }

    /**
     * @param Formateur $formateur
     * @return Formateur
     */
    public function update(Formateur $formateur) : Formateur
    {
        $this->getObjectManager()->flush($formateur);
        return $formateur;
    }

    /**
     * @param Formateur $formateur
     * @return Formateur
     */
    public function historise(Formateur $formateur) : Formateur
    {
        $formateur->historiser();
        $this->getObjectManager()->flush($formateur);
        return $formateur;
    }

    /**
     * @param Formateur $formateur
     * @return Formateur
     */
    public function restore(Formateur $formateur) : Formateur
    {
        $formateur->dehistoriser();
        $this->getObjectManager()->flush($formateur);
        return $formateur;
    }

    /**
     * @param Formateur $formateur
     * @return Formateur
     */
    public function delete(Formateur $formateur) : Formateur
    {
        $this->getObjectManager()->remove($formateur);
        $this->getObjectManager()->flush($formateur);
        return $formateur;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(Formateur::class)->createQueryBuilder('formateur')
            ->addSelect('finstance')->join('formateur.instance', 'finstance');
        return $qb;
    }

    /**
     * @param int|null $id
     * @return Formateur|null
     */
    public function getFormateur(?int $id) : ?Formateur
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('formateur.id = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Formateur partagent le même id [" . $id . "]",0,$e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return Formateur|null
     */
    public function getRequestedFormateur(AbstractActionController $controller, string $param = 'formateur') : ?Formateur
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getFormateur($id);
    }
}