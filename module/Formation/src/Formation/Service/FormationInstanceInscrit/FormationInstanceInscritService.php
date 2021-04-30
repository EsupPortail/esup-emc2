<?php

namespace Formation\Service\FormationInstanceInscrit;

use Application\Entity\Db\Agent;
use Application\Entity\Db\Structure;
use Application\Service\GestionEntiteHistorisationTrait;
use Application\Service\Structure\StructureServiceAwareTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Formation\Entity\Db\FormationInstance;
use Formation\Entity\Db\FormationInstanceInscrit;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class FormationInstanceInscritService
{
    use GestionEntiteHistorisationTrait;
    use StructureServiceAwareTrait;

    /** GESTION ENTITES ****************************************************************************************/

    /**
     * @param FormationInstanceInscrit $inscrit
     * @return FormationInstanceInscrit
     */
    public function create(FormationInstanceInscrit $inscrit)
    {
        $this->createFromTrait($inscrit);
        return $inscrit;
    }

    /**
     * @param FormationInstanceInscrit $inscrit
     * @return FormationInstanceInscrit
     */
    public function update(FormationInstanceInscrit $inscrit)
    {
        $this->updateFromTrait($inscrit);
        return $inscrit;
    }

    /**
     * @param FormationInstanceInscrit $inscrit
     * @return FormationInstanceInscrit
     */
    public function historise(FormationInstanceInscrit $inscrit)
    {
        $this->historiserFromTrait($inscrit);
        return $inscrit;
    }

    /**
     * @param FormationInstanceInscrit $inscrit
     * @return FormationInstanceInscrit
     */
    public function restore(FormationInstanceInscrit $inscrit)
    {
        $this->restoreFromTrait($inscrit);
        return $inscrit;
    }

    /**
     * @param FormationInstanceInscrit $inscrit
     * @return FormationInstanceInscrit
     */
    public function delete(FormationInstanceInscrit $inscrit)
    {
        $this->deleteFromTrait($inscrit);
        return $inscrit;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(FormationInstanceInscrit::class)->createQueryBuilder('inscrit')
            ->addSelect('agent')->join('inscrit.agent', 'agent')
            ->addSelect('affectation')->leftJoin('agent.affectations', 'affectation')
            ->addSelect('structure')->leftJoin('affectation.structure', 'structure')
            ->addSelect('finstance')->join('inscrit.instance', 'finstance')
            ->addSelect('instanceetat')->leftjoin('finstance.etat', 'instanceetat')
            ->addSelect('inscritetat')->leftjoin('inscrit.etat', 'inscritetat')
            ->addSelect('formation')->join('finstance.formation', 'formation');
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
            ->setParameter('code', FormationInstance::ETAT_CLOTURE_INSTANCE)
            ->andWhere('inscrit.histoDestruction IS NULL')
            ->orderBy('formation.libelle', 'ASC');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Structure $structure
     * @param bool $avecStructuresFilles
     * @return FormationInstanceInscrit[]
     */
    public function getInscriptionsByStructure(Structure $structure, bool $avecStructuresFilles = true) : array
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
            ->andWhere('inscritetat.code = :demandevalidation')
            ->setParameter('demandevalidation', FormationInstanceInscrit::ETAT_DEMANDE_INSCRIPTION)
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }
}