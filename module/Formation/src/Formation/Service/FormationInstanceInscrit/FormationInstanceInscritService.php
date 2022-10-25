<?php

namespace Formation\Service\FormationInstanceInscrit;

use Application\Entity\Db\Agent;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Formation\Entity\Db\Formation;
use Formation\Entity\Db\FormationInstance;
use Formation\Entity\Db\FormationInstanceInscrit;
use Formation\Provider\Etat\InscriptionEtats;
use Formation\Provider\Etat\SessionEtats;
use Structure\Entity\Db\Structure;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use UnicaenEtat\Entity\Db\Etat;

class FormationInstanceInscritService
{
    use EntityManagerAwareTrait;
    use StructureServiceAwareTrait;

    /** GESTION ENTITES ****************************************************************************************/

    /**
     * @param FormationInstanceInscrit $inscrit
     * @return FormationInstanceInscrit
     */
    public function create(FormationInstanceInscrit $inscrit) : FormationInstanceInscrit
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
    public function update(FormationInstanceInscrit $inscrit) : FormationInstanceInscrit
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
    public function historise(FormationInstanceInscrit $inscrit) : FormationInstanceInscrit
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
    public function restore(FormationInstanceInscrit $inscrit) : FormationInstanceInscrit
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
    public function delete(FormationInstanceInscrit $inscrit) : FormationInstanceInscrit
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
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(FormationInstanceInscrit::class)->createQueryBuilder('inscrit')
            ->addSelect('agent')->join('inscrit.agent', 'agent')

            ->addSelect('affectation')->leftJoin('agent.affectations', 'affectation')
            ->addSelect('structure')->leftJoin('affectation.structure', 'structure')
            ->addSelect('frais')->leftJoin('inscrit.frais', 'frais')

            ->addSelect('finstance')->join('inscrit.instance', 'finstance')
            ->addSelect('formation')->join('finstance.formation', 'formation')
            ->addSelect('journee')->join('finstance.journees', 'journee')

            ->addSelect('instanceetat')->leftjoin('finstance.etat', 'instanceetat')
            ->addSelect('instanceetattype')->leftjoin('instanceetat.type', 'instanceetattype')
            ->addSelect('inscritetat')->leftjoin('inscrit.etat', 'inscritetat')
            ->addSelect('inscritetattype')->leftjoin('inscritetat.type', 'inscritetattype')
        ;
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return FormationInstanceInscrit
     */
    public function getFormationsInstancesInscrits($champ = 'id', $ordre = 'ASC')
    {
        $qb = $this->getEntityManager()->getRepository(FormationInstanceInscrit::class)->createQueryBuilder('inscrit')
            ->orderBy('inscrit.' . $champ, $ordre);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return FormationInstanceInscrit
     */
    public function getFormationInstanceInscrit(int $id)
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
     * @return FormationInstanceInscrit
     */
    public function getRequestedFormationInstanceInscrit(AbstractActionController $controller, $param = 'inscrit')
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getFormationInstanceInscrit($id);
        return $result;
    }

    /**
     * @param Agent $agent
     * @return FormationInstanceInscrit[]
     */
    public function getFormationsByInscrit(Agent $agent) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('inscrit.agent = :agent')
            ->setParameter('agent', $agent)
            ->andWhere('instanceetat.code <> :code')
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
    public function getFormationsBySuivies(Agent $agent) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('inscrit.agent = :agent')
            ->setParameter('agent', $agent)
            ->andWhere('instanceetat.code = :retour OR instanceetat.code = :close')
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
    public function getInscriptionsByStructure(Structure $structure, bool $avecStructuresFilles = true, bool $anneeCourrante = false) : array
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
            $today = new DateTime();
            $month = ((int) $today->format('m'));
            $year  = ((int) $today->format('Y'));
            $annee = ($month > 8 ) ? $year : ($year-1) ;

            $mini = DateTime::createFromFormat('d/m/Y', '01/09/' . $annee);
            $maxi = DateTime::createFromFormat('d/m/Y', '31/08/' . ($annee+1));

            $qb = $qb->andWhere('inscrit.histoCreation >= :mini AND inscrit.histoCreation <= :maxi')
                ->setParameter('mini', $mini)
                ->setParameter('maxi', $maxi)
            ;
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Agent[] $agents
     * @param Etat[] $etats
     * @param int|null $annee
     * @return FormationInstanceInscrit[]
     */
    public function getInscriptionsValideesByAgentsAndEtats(array $agents, array $etats, ?int $annee) : array
    {
        if ($annee === null) Formation::getAnnee();
        $debut = DateTime::createFromFormat('d/m/Y', '01/09/'.$annee);
        $fin   = DateTime::createFromFormat('d/m/Y', '31/08/'.($annee+1));

        $qb = $this->getEntityManager()->getRepository(FormationInstanceInscrit::class)->createQueryBuilder('inscription')
            ->andWhere('inscription.histoDestruction IS NULL')
            ->join('inscription.etat', 'etat')->addSelect('etat')
            ->andWhere('etat.code in (:etats)')->setParameter('etats', $etats)
//            ->andWhere('inscription.debut > :debut')->setParameter('debut', $debut)
//            ->andWhere('inscription.debut < :fin')->setParameter('fin', $fin)
            ->join('inscription.agent', 'agent')->addSelect('agent')
            ->andWhere('agent in (:agents)')->setParameter('agents', $agents)
        ;

        $result = $qb->getQuery()->getResult();
        array_filter($result, function(FormationInstanceInscrit $a) use ($debut, $fin) {
            return $a->getInstance()->getDebut() >= $debut
                AND $a->getInstance()->getFin() >= $fin;
        });
        return $result;
    }

    /**
     * @param Agent[] $agents
     * @param int|null $annee
     * @return FormationInstanceInscrit[]
     */
    public function getInscriptionsValideesByAgents(array $agents, ?int $annee) : array
    {
        $etats = [ InscriptionEtats::ETAT_VALIDER_DRH, InscriptionEtats::ETAT_REFUSER];
        $result = $this->getInscriptionsValideesByAgentsAndEtats($agents, $etats, $annee);
        return $result;
    }

    /**
     * @param Agent[] $agents
     * @param int|null $annee
     * @return FormationInstanceInscrit[]
     */
    public function getInscriptionsNonValideesByAgents(array $agents, ?int $annee) : array
    {
        $etats = [ InscriptionEtats::ETAT_DEMANDE, InscriptionEtats::ETAT_VALIDER_RESPONSABLE];
        $result = $this->getInscriptionsValideesByAgentsAndEtats($agents, $etats, $annee);
        return $result;
    }

    public function getInscriptionsWithFiltre(array $params)
    {
        $qb = $this->createQueryBuilder()->orderBy('inscrit.histoCreation', 'asc');

        if (isset($params['etat'])) $qb = $qb->andWhere('inscrit.etat = :etat')->setParameter('etat', $params['etat']);
        if (isset($params['historise'])) {
            if ($params['historise'] === '1') $qb = $qb->andWhere('inscrit.histoDestruction IS NOT NULL');
            if ($params['historise'] === '0') $qb = $qb->andWhere('inscrit.histoDestruction IS NULL');
        }
        if (isset($params['annee']) AND $params['annee'] !== '') {
            $annee = (int) $params['annee'];
            $debut = DateTime::createFromFormat('d/m/Y', '01/09/'.$annee);
            $fin = DateTime::createFromFormat('d/m/Y', '31/08/'.($annee+1));
            $qb = $qb
                ->andWhere('journee.jour >= :debut')->setParameter('debut', $debut)
                ->andWhere('journee.jour <= :fin')->setParameter('fin', $fin)
            ;
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getInscrpitionsByAgentsAndAnnee(array $agents, ?int $annee = null) : array
    {
        if ($annee === null) $annee = Formation::getAnnee();
        $debut = DateTime::createFromFormat('d/m/Y H:i', '01/09/'.$annee.' 08:00');
        $fin = DateTime::createFromFormat('d/m/Y H:i', '31/08/'.($annee+1).' 18:00');

        $qb = $this->createQueryBuilder()->orderBy('inscrit.histoCreation', 'asc')
            ->andWhere('inscrit.agent in (:agents)')->setParameter('agents', $agents)
            ->andWhere('inscrit.histoCreation >= :debut')->setParameter('debut', $debut)
            ->andWhere('inscrit.histoCreation <= :fin')->setParameter('fin', $fin)
        ;
        /** @var FormationInstanceInscrit[] $result */
        $result = $qb->getQuery()->getResult();

        $inscriptions = [];
        foreach ($result as $item) {
            $inscriptions[$item->getAgent()->getId()][] = $item;
        }

        return $inscriptions;
    }
}