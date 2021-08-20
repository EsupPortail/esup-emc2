<?php

namespace EntretienProfessionnel\Service\Sursis;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use EntretienProfessionnel\Entity\Db\Sursis;
use UnicaenApp\Exception\RuntimeException;
use UnicaenUtilisateur\Service\GestionEntiteHistorisationTrait;
use Zend\Mvc\Controller\AbstractActionController;

class SursisService {
    use GestionEntiteHistorisationTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Sursis $sursis
     * @return Sursis
     */
    public function create(Sursis $sursis) : Sursis
    {
        $this->createFromTrait($sursis);
        return $sursis;
    }

    /**
     * @param Sursis $sursis
     * @return Sursis
     */
    public function update(Sursis $sursis) : Sursis
    {
        $this->updateFromTrait($sursis);
        return $sursis;
    }

    /**
     * @param Sursis $sursis
     * @return Sursis
     */
    public function historise(Sursis $sursis) : Sursis
    {
        $this->historiserFromTrait($sursis);
        return $sursis;
    }

    /**
     * @param Sursis $sursis
     * @return Sursis
     */
    public function restore(Sursis $sursis) : Sursis
    {
        $this->restoreFromTrait($sursis);
        return $sursis;
    }

    /**
     * @param Sursis $sursis
     * @return Sursis
     */
    public function delete(Sursis $sursis) : Sursis
    {
        $this->deleteFromTrait($sursis);
        return $sursis;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(Sursis::class)->createQueryBuilder('sursis')
            ->addSelect('entretien')->join('sursis.entretien', 'entretien')
            ->addSelect('createur')->join('sursis.histoCreateur', 'createur')
            ->addSelect('modificateur')->leftjoin('sursis.histoModificateur', 'modificateur')
            ->addSelect('destructeur')->leftjoin('sursis.histoDestructeur', 'destructeur')
        ;

        return $qb;
    }

    /**
     * @return Sursis[]
     */
    public function getSursisListe() : array
    {
        $qb = $this->createQueryBuilder();
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param int $id
     * @return Sursis|null
     */
    public function getSursis(int $id) : ?Sursis
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('sursis.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Sursis partagent le même id [".$id."]",0,$e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return Sursis|null
     */
    public function getRequestedSursis(AbstractActionController $controller, string $param='sursis') : ?Sursis
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getSursis($id);
        return $result;
    }
}