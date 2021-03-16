<?php

namespace Application\Service\CompetenceMaitrise;

use Application\Entity\Db\CompetenceMaitrise;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenUtilisateur\Service\GestionEntiteHistorisationTrait;
use Zend\Mvc\Controller\AbstractActionController;

class CompetenceMaitriseService {
    use GestionEntiteHistorisationTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param CompetenceMaitrise $maitrise
     * @return CompetenceMaitrise
     */
    public function create(CompetenceMaitrise $maitrise) : CompetenceMaitrise
    {
        $this->createFromTrait($maitrise);
        return $maitrise;
    }

    /**
     * @param CompetenceMaitrise $maitrise
     * @return CompetenceMaitrise
     */
    public function update(CompetenceMaitrise $maitrise) : CompetenceMaitrise
    {
        $this->updateFromTrait($maitrise);
        return $maitrise;
    }

    /**
     * @param CompetenceMaitrise $maitrise
     * @return CompetenceMaitrise
     */
    public function historise(CompetenceMaitrise $maitrise) : CompetenceMaitrise
    {
        $this->historiserFromTrait($maitrise);
        return $maitrise;
    }

    /**
     * @param CompetenceMaitrise $maitrise
     * @return CompetenceMaitrise
     */
    public function restore(CompetenceMaitrise $maitrise) : CompetenceMaitrise
    {
        $this->restoreFromTrait($maitrise);
        return $maitrise;
    }

    /**
     * @param CompetenceMaitrise $maitrise
     * @return CompetenceMaitrise
     */
    public function delete(CompetenceMaitrise $maitrise) : CompetenceMaitrise
    {
        $this->deleteFromTrait($maitrise);
        return $maitrise;
    }

    /** QUERY *********************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(CompetenceMaitrise::class)->createQueryBuilder('maitrise');
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @param bool $nonHistorise
     * @return CompetenceMaitrise[]
     */
    public function getCompetencesMaitrises(string $champ = 'niveau', string $ordre = 'ASC', bool $nonHistorise = false) : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('maitrise.' . $champ, $ordre);

        if ($nonHistorise !== true) $qb = $qb->andWhere('maitrise.histoDestruction IS NULL');
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @param bool $nonHistorise
     * @return array
     */
    public function getCompetencesMaitrisesAsOptions(string $champ = 'niveau', string $ordre = 'ASC', bool $nonHistorise = false) : array
    {
        $maitrises = $this->getCompetencesMaitrises($champ, $ordre, $nonHistorise);
        $options = [];
        foreach ($maitrises as $maitrise) {
            $options[$maitrise->getId()] = $maitrise->getLibelle();
        }
        return $options;
    }

    /**
     * @param int|null $id
     * @return CompetenceMaitrise|null
     */
    public function getCompetenceMaitrise(?int $id) : ?CompetenceMaitrise
    {
        if ($id === null) return null;
        $qb = $this->createQueryBuilder()
            ->andWhere('maitrise.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs CompetenceMaitrise partagent le même id [".$id."]",0,$e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return CompetenceMaitrise|null
     */
    public function getRequestedCompetenceMaitrise(AbstractActionController $controller, string $param = 'maitrise') : ?CompetenceMaitrise
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getCompetenceMaitrise($id);
        return $result;
    }

    /**
     * @param int $niveau
     * @return CompetenceMaitrise|null
     */
    public function getCompetenceMaitriseByNiveau(int $niveau) : ?CompetenceMaitrise
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('maitrise.niveau = :niveau')
            ->setParameter('niveau', $niveau)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs CompetenceMaitrise partagent le même niveau [".$niveau."]",0,$e);
        }
        return $result;
    }
}