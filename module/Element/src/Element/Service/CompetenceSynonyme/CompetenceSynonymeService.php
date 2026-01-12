<?php

namespace Element\Service\CompetenceSynonyme;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Element\Entity\Db\Competence;
use Element\Entity\Db\CompetenceSynonyme;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class CompetenceSynonymeService
{
    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(CompetenceSynonyme $competenceSynonyme): void
    {
        $this->getObjectManager()->persist($competenceSynonyme);
        $this->getObjectManager()->flush($competenceSynonyme);
    }

    public function update(CompetenceSynonyme $competenceSynonyme): void
    {
        $this->getObjectManager()->flush($competenceSynonyme);
    }

    public function delete(CompetenceSynonyme $competenceSynonyme): void
    {
        $this->getObjectManager()->remove($competenceSynonyme);
        $this->getObjectManager()->flush($competenceSynonyme);
    }

    /** QUERRY ********************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(CompetenceSynonyme::class)->createQueryBuilder('competenceSynonyme')
            ->join('competenceSynonyme.competence', 'competence')->addSelect('competence')
        ;
        return $qb;
    }

    public function getCompetenceSynonyme(?int $id): ?CompetenceSynonyme
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('competenceSynonyme.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".CompetenceSynonyme::class."] partagent le même libellé [".$id."]", -1,$e);
        }
        return $result;
    }

    public function getRequestedCompetenceSynonyme(AbstractActionController $controller, string $param = "competence-synonyme") : ?CompetenceSynonyme
    {
        $id = $controller->params()->fromRoute($param);
        $competence = $this->getCompetenceSynonyme($id);
        return $competence;
    }

    /** @return CompetenceSynonyme[] */
    public function getCompetencesSynonymes(): array
    {
        $qb = $this->createQueryBuilder();
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return CompetenceSynonyme[] */
    public function getCompetencesSynonymesByCompetence(?Competence $competence): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('competenceSynonyme.competence = :competence')->setParameter('competence', $competence)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** FACADE ********************************************************************************************************/

    public function createWith(Competence $competence, string $libelle): void
    {
        $synonyme = new CompetenceSynonyme();
        $synonyme->setCompetence($competence);
        $synonyme->setLibelle($libelle);
        $this->create($synonyme);
    }

}