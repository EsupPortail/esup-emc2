<?php

namespace Application\Service\EntretienProfessionnel;

use Application\Entity\Db\EntretienProfessionnelCampagne;
use Application\Service\GestionEntiteHistorisationTrait;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class EntretienProfessionnelCampagneService {
//    use UserServiceAwareTrait;
//    use DateTimeAwareTrait;
//    use EntityManagerAwareTrait;
    use GestionEntiteHistorisationTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param EntretienProfessionnelCampagne $campagne
     * @return EntretienProfessionnelCampagne
     */
    public function create(EntretienProfessionnelCampagne $campagne)
    {
        $this->createFromTrait($campagne);
        return $campagne;
    }

    /**
     * @param EntretienProfessionnelCampagne $campagne
     * @return EntretienProfessionnelCampagne
     */
    public function update(EntretienProfessionnelCampagne $campagne)
    {
        $this->updateFromTrait($campagne);
        return $campagne;
    }

    /**
     * @param EntretienProfessionnelCampagne $campagne
     * @return EntretienProfessionnelCampagne
     */
    public function historise(EntretienProfessionnelCampagne $campagne)
    {
        $this->historiserFromTrait($campagne);
        return $campagne;
    }

    /**
     * @param EntretienProfessionnelCampagne $campagne
     * @return EntretienProfessionnelCampagne
     */
    public function restore(EntretienProfessionnelCampagne $campagne)
    {
        $this->restoreFromTrait($campagne);
        return $campagne;
    }

    /**
     * @param EntretienProfessionnelCampagne $campagne
     * @return EntretienProfessionnelCampagne
     */
    public function delete(EntretienProfessionnelCampagne $campagne)
    {
        $this->deleteFromTrait($campagne);
        return $campagne;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(EntretienProfessionnelCampagne::class)->createQueryBuilder('campagne')
            ->addSelect('precede')->leftJoin('campagne.precede', 'precede')
            ->addSelect('entretien')->leftJoin('campagne.entretiens', 'entretien');

        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return EntretienProfessionnelCampagne[]
     */
    public function getEntretiensProfessionnelsCampagnes($champ='annee', $ordre='DESC')
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('campagne.' . $champ, $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return array
     */
    public function getEntretiensProfessionnelsCampagnesAsOptions($champ='annee', $ordre='DESC') {
        $campagnes = $this->getEntretiensProfessionnelsCampagnes($champ, $ordre);

        $array = [];
        foreach ($campagnes as $campagne) {
            $array[$campagne->getId()] = $campagne->getAnnee();
        }
        return $array;
    }

    /**
     * @param $id
     * @return EntretienProfessionnelCampagne
     */
    public function getEntretienProfessionnelCampagne($id)
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
     * @return EntretienProfessionnelCampagne
     */
    public function getRequestedEntretienProfessionnelCampagne($controller, $param = "campagne")
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getEntretienProfessionnelCampagne($id);
        return $result;
    }

    /**
     * @param DateTime|null $date
     * @return EntretienProfessionnelCampagne[]
     */
    public function getCampagnesActives($date = null)
    {
        if ($date === null) $date = $this->getDateTime();
        $qb = $this->createQueryBuilder()
            ->andWhere('campagne.dateFin >= :date')
            ->setParameter('date', $date)
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }
}