<?php

namespace Element\Service\CompetenceDiscipline;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Element\Entity\Db\CompetenceDiscipline;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class CompetenceDisciplineService
{
    use ProvidesObjectManager;

    /** ENTITY MANAGMENT **********************************************************************************************/

    /**
     * @param CompetenceDiscipline $discipline
     * @return CompetenceDiscipline
     */
    public function create(CompetenceDiscipline $discipline): CompetenceDiscipline
    {
        $this->getObjectManager()->persist($discipline);
        $this->getObjectManager()->flush($discipline);
        return $discipline;
    }

    /**
     * @param CompetenceDiscipline $discipline
     * @return CompetenceDiscipline
     */
    public function update(CompetenceDiscipline $discipline): CompetenceDiscipline
    {
        $this->getObjectManager()->flush($discipline);
        return $discipline;
    }

    /**
     * @param CompetenceDiscipline $discipline
     * @return CompetenceDiscipline
     */
    public function historise(CompetenceDiscipline $discipline): CompetenceDiscipline
    {
        $discipline->historiser();
        $this->getObjectManager()->flush($discipline);
        return $discipline;
    }

    /**
     * @param CompetenceDiscipline $discipline
     * @return CompetenceDiscipline
     */
    public function restore(CompetenceDiscipline $discipline): CompetenceDiscipline
    {
        $discipline->dehistoriser();
        $this->getObjectManager()->flush($discipline);
        return $discipline;
    }

    /**
     * @param CompetenceDiscipline $discipline
     * @return CompetenceDiscipline
     */
    public function delete(CompetenceDiscipline $discipline): CompetenceDiscipline
    {
        $this->getObjectManager()->remove($discipline);
        $this->getObjectManager()->flush($discipline);
        return $discipline;
    }

    /** REQUETE *******************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(CompetenceDiscipline::class)->createQueryBuilder('discipline')
            ->addSelect('competence')->leftJoin('discipline.competences', 'competence');
        return $qb;
    }

    /** @return CompetenceDiscipline[] */
    public function getCompetencesDisciplines(bool $withHisto = false, string $champ = 'libelle', string $order = 'ASC'): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('discipline.' . $champ, $order);
        if (!$withHisto) $qb = $qb->andWhere('discipline.histoDestruction IS NULL');
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $champ
     * @param string $order
     * @return array
     */
    public function getCompetencesDisciplinesAsOptions(string $champ = 'libelle', string $order = 'ASC'): array
    {
        $types = $this->getCompetencesDisciplines(false, $champ, $order);
        $options = [];
        foreach ($types as $type) {
            $options[$type->getId()] = $type->getLibelle();
        }
        return $options;
    }

    /**
     * @param int|null $id
     * @return CompetenceDiscipline|null
     */
    public function getCompetenceDiscipline(?int $id): ?CompetenceDiscipline
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('discipline.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".CompetenceDiscipline::class."] partagent le même id [" . $id . "]", 0, $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return CompetenceDiscipline|null
     */
    public function getRequestedCompetenceDiscipline(AbstractActionController $controller, string $paramName = 'competence-discipline'): ?CompetenceDiscipline
    {
        $id = $controller->params()->fromRoute($paramName);
        $theme = $this->getCompetenceDiscipline($id);
        return $theme;
    }

    /**
     * @param string $libelle
     * @return CompetenceDiscipline|null
     */
    public function getCompetenceDisciplineByLibelle(string $libelle): ?CompetenceDiscipline
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('discipline.libelle = :libelle')->setParameter('libelle', $libelle);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException('Plusieurs ['.CompetenceDiscipline::class.'] partagent le même libellé [' . $libelle . ']', 0, $e);
        }
        return $result;
    }

    /** FACADE ***********************************************************************/

    public function createWith(?string $libelle): CompetenceDiscipline
    {
        $theme = new CompetenceDiscipline();
        $theme->setLibelle($libelle);
        $this->create($theme);
        return $theme;
    }

}
