<?php

namespace Formation\Service\FormationInstanceInscrit;

use Application\Entity\Db\Agent;
use DateTime;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Formation\Entity\Db\Formation;
use Formation\Entity\Db\FormationInstanceInscrit;
use Formation\Provider\Etat\InscriptionEtats;
use Formation\Provider\Etat\SessionEtats;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;
use Structure\Entity\Db\Structure;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenEtat\Entity\Db\EtatType;

class FormationInstanceInscritService
{
    use EntityManagerAwareTrait;
    use StructureServiceAwareTrait;

    /** GESTION ENTITES ****************************************************************************************/

    /**
     * @param FormationInstanceInscrit $inscrit
     * @return FormationInstanceInscrit
     */
    public function create(FormationInstanceInscrit $inscrit): FormationInstanceInscrit
    {
        try {
            $this->getEntityManager()->persist($inscrit);
            $this->getEntityManager()->flush($inscrit);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $inscrit;
    }

    /**
     * @param FormationInstanceInscrit $inscrit
     * @return FormationInstanceInscrit
     */
    public function update(FormationInstanceInscrit $inscrit): FormationInstanceInscrit
    {
        try {
            $this->getEntityManager()->flush($inscrit);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $inscrit;
    }

    /**
     * @param FormationInstanceInscrit $inscrit
     * @return FormationInstanceInscrit
     */
    public function historise(FormationInstanceInscrit $inscrit): FormationInstanceInscrit
    {
        try {
            $inscrit->historiser();
            $this->getEntityManager()->flush($inscrit);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $inscrit;
    }

    /**
     * @param FormationInstanceInscrit $inscrit
     * @return FormationInstanceInscrit
     */
    public function restore(FormationInstanceInscrit $inscrit): FormationInstanceInscrit
    {
        try {
            $inscrit->dehistoriser();
            $this->getEntityManager()->flush($inscrit);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $inscrit;
    }

    /**
     * @param FormationInstanceInscrit $inscrit
     * @return FormationInstanceInscrit
     */
    public function delete(FormationInstanceInscrit $inscrit): FormationInstanceInscrit
    {
        try {
            $this->getEntityManager()->remove($inscrit);
            $this->getEntityManager()->flush($inscrit);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $inscrit;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder(): QueryBuilder
    {
        try {
            $qb = $this->getEntityManager()->getRepository(FormationInstanceInscrit::class)->createQueryBuilder('inscrit')
                ->addSelect('agent')->leftjoin('inscrit.agent', 'agent')
                ->addSelect('affectation')->leftJoin('agent.affectations', 'affectation')
                ->addSelect('structure')->leftJoin('affectation.structure', 'structure')
                ->addSelect('finstance')->leftjoin('inscrit.instance', 'finstance')
                ->addSelect('formation')->leftjoin('finstance.formation', 'formation')
                ->addSelect('journee')->leftjoin('finstance.journees', 'journee')
                ->addSelect('instanceetat')->leftjoin('finstance.etats', 'instanceetat')
                ->addSelect('instanceetattype')->leftjoin('instanceetat.type', 'instanceetattype')
                ->addSelect('inscritetat')->leftjoin('inscrit.etats', 'inscritetat')
                ->addSelect('inscritetattype')->leftjoin('inscritetat.type', 'inscritetattype');
        } catch (NotSupported $e) {
            throw new RuntimeException("Un problème est survenu lors de la création du QueryBuilder de [".FormationInstanceInscrit::class."]",0,$e);
        }
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return FormationInstanceInscrit[]
     */
    public function getFormationsInstancesInscrits(string $champ = 'id', string $ordre = 'ASC'): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('inscrit.' . $champ, $ordre);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return FormationInstanceInscrit|null
     */
    public function getFormationInstanceInscrit(int $id): ?FormationInstanceInscrit
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('inscrit.id = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs FormationInstanceInscrit partagent le même id [" . $id . "]", 0, $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return FormationInstanceInscrit|null
     */
    public function getRequestedFormationInstanceInscrit(AbstractActionController $controller, string $param = 'inscrit'): ?FormationInstanceInscrit
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getFormationInstanceInscrit($id);
        return $result;
    }

    /**
     * @param Agent $agent
     * @return FormationInstanceInscrit[]
     */
    public function getFormationsByInscrit(Agent $agent): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('inscrit.agent = :agent')
            ->setParameter('agent', $agent)
            ->andWhere('inscritetattype.code <> :code')
            ->setParameter('code', SessionEtats::ETAT_CLOTURE_INSTANCE)
            ->andWhere('inscrit.histoDestruction IS NULL')
            ->orderBy('formation.libelle', 'ASC');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Agent $agent
     * @return FormationInstanceInscrit[]
     */
    public function getFormationsBySuivies(Agent $agent): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('inscrit.agent = :agent')
            ->setParameter('agent', $agent)
            ->andWhere('instanceetattype.code = :retour OR instanceetattype.code = :close')
            ->setParameter('retour', SessionEtats::ETAT_ATTENTE_RETOURS)
            ->setParameter('close', SessionEtats::ETAT_CLOTURE_INSTANCE)
            ->andWhere('inscrit.histoDestruction IS NULL')
            ->orderBy('formation.libelle', 'ASC');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Structure $structure
     * @param bool $avecStructuresFilles
     * @param bool $anneeCourrante
     * @return FormationInstanceInscrit[]
     */
    public function getInscriptionsByStructure(Structure $structure, bool $avecStructuresFilles = true, bool $anneeCourrante = false): array
    {
        $structures = [];
        $structures[] = $structure;

        if ($avecStructuresFilles === true) {
            $structures = $this->getStructureService()->getStructuresFilles($structure);
            $structures[] = $structure;
        }

        $qb = $this->createQueryBuilder()
            ->andWhere('affectation.structure in (:structures)')
            ->setParameter('structures', $structures)
//            ->andWhere('inscritetat.code = :demandevalidation')
//            ->setParameter('demandevalidation', FormationInstanceInscrit::ETAT_DEMANDE_INSCRIPTION)
        ;

        if ($anneeCourrante) {
            $annee = Formation::getAnnee();
            $mini = DateTime::createFromFormat('d/m/Y', '01/09/' . $annee);
            $maxi = DateTime::createFromFormat('d/m/Y', '31/08/' . ($annee + 1));

            $qb = $qb->andWhere('inscrit.histoCreation >= :mini AND inscrit.histoCreation <= :maxi')
                ->setParameter('mini', $mini)
                ->setParameter('maxi', $maxi);
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Agent[] $agents
     * @param EtatType[] $etats
     * @param int|null $annee
     * @return FormationInstanceInscrit[]
     */
    public function getInscriptionsByAgentsAndEtats(array $agents, array $etats, ?int $annee): array
    {
        if ($annee === null) $annee = Formation::getAnnee();
        $debut = DateTime::createFromFormat('d/m/Y', '01/09/' . $annee);
        $fin = DateTime::createFromFormat('d/m/Y', '31/08/' . ($annee + 1));

        $qb = $this->createQueryBuilder()
            ->andWhere('inscrit.histoDestruction IS NULL')
            ->andWhere('inscritetattype.code in (:etats)')->setParameter('etats', $etats)
            ->andWhere('agent in (:agents)')->setParameter('agents', $agents)
            ->andWhere('finstance.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        $result = array_filter($result, function (FormationInstanceInscrit $a) use ($debut, $fin) {
            $sessionDebut = DateTime::createFromFormat('d/m/Y', $a->getInstance()->getDebut());
            $sessionFin = DateTime::createFromFormat('d/m/Y', $a->getInstance()->getFin());
            return ($sessionDebut >= $debut && $sessionFin <= $fin);
        });
        return $result;
    }

    /**
     * @param Agent[] $agents
     * @param int|null $annee
     * @return FormationInstanceInscrit[]
     */
    public function getInscriptionsValideesByAgents(array $agents, ?int $annee): array
    {
        $etats = [InscriptionEtats::ETAT_VALIDER_DRH, InscriptionEtats::ETAT_REFUSER];
        $result = $this->getInscriptionsByAgentsAndEtats($agents, $etats, $annee);
        return $result;
    }

    /**
     * @param Agent[] $agents
     * @param int|null $annee
     * @return FormationInstanceInscrit[]
     */
    public function getInscriptionsNonValideesByAgents(array $agents, ?int $annee): array
    {
        $etats = [InscriptionEtats::ETAT_DEMANDE, InscriptionEtats::ETAT_VALIDER_RESPONSABLE];
        $result = $this->getInscriptionsByAgentsAndEtats($agents, $etats, $annee);
        return $result;
    }

    public function getInscriptionsWithFiltre(array $params)
    {
        $qb = $this->createQueryBuilder()->orderBy('inscrit.histoCreation', 'asc');

        if (isset($params['etat'])) $qb = $qb->andWhere('inscritetat.type = :etat')->setParameter('etat', $params['etat']);
        if (isset($params['historise'])) {
            if ($params['historise'] === '1') $qb = $qb->andWhere('inscrit.histoDestruction IS NOT NULL');
            if ($params['historise'] === '0') $qb = $qb->andWhere('inscrit.histoDestruction IS NULL');
        }
        if (isset($params['annee']) and $params['annee'] !== '') {
            $annee = (int)$params['annee'];
            $debut = DateTime::createFromFormat('d/m/Y', '01/09/' . $annee);
            $fin = DateTime::createFromFormat('d/m/Y', '31/08/' . ($annee + 1));
            $qb = $qb
                ->andWhere('journee.jour >= :debut')->setParameter('debut', $debut)
                ->andWhere('journee.jour <= :fin')->setParameter('fin', $fin);
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Agent[] $agents
     * @return FormationInstanceInscrit[]
     */
    public function getFormationsByAgents(array $agents): array
    {
        $agentIds = array_map(function (Agent $a) {
            return $a->getId();
        }, $agents);
        $qb = $this->createQueryBuilder()
            ->andWhere('agent.id in (:agentIds)')->setParameter('agentIds', $agentIds);
        return $qb->getQuery()->getResult();
    }
}