<?php

namespace EntretienProfessionnel\Service\Campagne;

use Application\Service\GestionEntiteHistorisationTrait;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use EntretienProfessionnel\Entity\Db\Campagne;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class CampagneService {
    use GestionEntiteHistorisationTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Campagne $campagne
     * @return Campagne
     */
    public function create(Campagne $campagne) : Campagne
    {
        $this->createFromTrait($campagne);
        return $campagne;
    }

    /**
     * @param Campagne $campagne
     * @return Campagne
     */
    public function update(Campagne $campagne) : Campagne
    {
        $this->updateFromTrait($campagne);
        return $campagne;
    }

    /**
     * @param Campagne $campagne
     * @return Campagne
     */
    public function historise(Campagne $campagne) : Campagne
    {
        $this->historiserFromTrait($campagne);
        return $campagne;
    }

    /**
     * @param Campagne $campagne
     * @return Campagne
     */
    public function restore(Campagne $campagne) : Campagne
    {
        $this->restoreFromTrait($campagne);
        return $campagne;
    }

    /**
     * @param Campagne $campagne
     * @return Campagne
     */
    public function delete(Campagne $campagne) : Campagne
    {
        $this->deleteFromTrait($campagne);
        return $campagne;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(Campagne::class)->createQueryBuilder('campagne')
            ->addSelect('precede')->leftJoin('campagne.precede', 'precede')
            ->addSelect('entretien')->leftJoin('campagne.entretiens', 'entretien')
            ->addSelect('sursis')->leftJoin('entretien.sursis', 'sursis')
            ->addSelect('etat')->leftJoin('entretien.etat', 'etat')
            ->addSelect('etattype')->leftJoin('etat.type', 'etattype')
        ;

        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return Campagne[]
     */
    public function getCampagnes(string $champ='annee', string $ordre='DESC') : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('campagne.' . $champ, $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return array (id => string)
     */
    public function getCampagnesAsOptions(string $champ='annee', string $ordre='DESC') : array
    {
        $campagnes = $this->getCampagnes($champ, $ordre);

        $array = [];
        foreach ($campagnes as $campagne) {
            $array[$campagne->getId()] = $campagne->getAnnee();
        }
        return $array;
    }

    /**
     * @param $id
     * @return Campagne
     */
    public function getCampagne($id) : Campagne
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('campagne.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs EntretienProfessionnelCampagne partage le même id [".$id."]", 0, $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return Campagne
     */
    public function getRequestedCampagne(AbstractActionController $controller, string $param = "campagne") : Campagne
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getCampagne($id);
        return $result;
    }

    /**
     * @param DateTime|null $date
     * @return Campagne[]
     */
    public function getCampagnesActives(?DateTime $date = null) : array
    {
        if ($date === null) $date = $this->getDateTime();
        $qb = $this->createQueryBuilder()
            ->andWhere('campagne.dateFin >= :date')
            ->setParameter('date', $date)
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param DateTime|null $date
     * @return Campagne|null
     */
    public function getLastCampagne(?DateTime $date = null) : ?Campagne
    {
        if ($date === null) $date = $this->getDateTime();
        $qb = $this->createQueryBuilder()
            ->andWhere('campagne.dateFin < :date')
            ->setParameter('date', $date)
        ;
        $result = $qb->getQuery()->getResult();

        $last = null;
        /** @var Campagne $item */
        foreach ($result as $item) {
            if ($last === null OR $item->getAnnee() > $last->getAnnee()) $last = $item;
        }
        return $item;
    }
}