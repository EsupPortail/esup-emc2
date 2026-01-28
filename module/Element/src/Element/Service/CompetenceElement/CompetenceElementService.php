<?php

namespace Element\Service\CompetenceElement;

use Application\Entity\Db\Agent;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Element\Entity\Db\Competence;
use Element\Entity\Db\CompetenceElement;
use Element\Service\NiveauMaitrise\NiveauMaitriseServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class CompetenceElementService
{
    use NiveauMaitriseServiceAwareTrait;
    use ProvidesObjectManager;

    /** Gestion des entites ***************************************************************************************/

    public function create(CompetenceElement $element): CompetenceElement
    {
        $this->getObjectManager()->persist($element);
        return $element;
    }

    public function update(CompetenceElement $element): CompetenceElement
    {
        $this->getObjectManager()->flush($element);
        return $element;
    }

    public function historise(CompetenceElement $element): CompetenceElement
    {
        $element->historiser();
        $this->getObjectManager()->flush($element);
        return $element;
    }

    public function restore(CompetenceElement $element): CompetenceElement
    {
        $element->dehistoriser();
        $this->getObjectManager()->flush($element);
        return $element;
    }

    public function delete(CompetenceElement $element): CompetenceElement
    {
        $this->getObjectManager()->remove($element);
        $this->getObjectManager()->flush($element);
        return $element;
    }

    /** REQUETAGE  ****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(CompetenceElement::class)->createQueryBuilder('competenceelement')
            ->addSelect('competence')->join('competenceelement.competence', 'competence')
            ->addSelect('niveau')->leftjoin('competenceelement.niveau', 'niveau');
        return $qb;
    }

    public function getCompetenceElement(int $id): ?CompetenceElement
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('competenceelement.id = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs CompetenceElement partagent le même id [" . $id . "]", 0, $e);
        }
        return $result;
    }

    public function getRequestedCompetenceElement(AbstractActionController $controller, string $param = "competence-element"): ?CompetenceElement
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getCompetenceElement($id);
    }

    /** @return CompetenceElement[] */
    public function getElementsByCompetence(Competence $competence): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('competenceelement.competence = :competence')
            ->setParameter('competence', $competence);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return Agent[] */
    public function getAgentsHavinCompetenceFromAgent(Competence $competence): array
    {
        $qb = $this->getObjectManager()->getRepository(Agent::class)->createQueryBuilder("agent")
            ->join('agent.competences', 'competenceelement')->addSelect('competenceelement')
            ->andWhere('competenceelement.competence = :competence')->setParameter('competence', $competence)
            ->andWhere('competenceelement.histoDestruction IS NULL');
        return $qb->getQuery()->getResult();
    }

    /** @return Agent[] */
    public function getAgentsHavingCompetencesWithCriteres(array $criteria): array
    {
        $qb = $this->getObjectManager()->getRepository(Agent::class)->createQueryBuilder("agent")
            ->join('agent.competences', 'competenceelement')->addSelect('fcompetenceelement')
            ->join('agent.competences', 'fcompetenceelement')->addSelect('fcompetenceelement')
            ->join('fcompetenceelement.competence', 'competence')->addSelect('competence')
            ->join('fcompetenceelement.niveau', 'niveau')->addSelect('niveau');

        foreach ($criteria as $criterion) {
            $competence = $criterion['competence'];
            $operateur = $criterion['operateur'];
            $niveau = $this->getNiveauMaitriseService()->getMaitriseNiveau(($criterion['niveau'] !== '') ? $criterion['niveau'] : null);

            $qb = $qb->andWhere('fcompetenceelement.competence = :competence')->setParameter('competence', $competence);
            if ($operateur !== null && $niveau !== null) {
                $qb = $qb->andWhere('niveau.niveau ' . $operateur . ' :niveau')->setParameter('niveau', $niveau->getNiveau());
            }
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }
}

