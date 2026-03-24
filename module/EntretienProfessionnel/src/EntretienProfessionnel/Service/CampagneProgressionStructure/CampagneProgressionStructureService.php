<?php

namespace EntretienProfessionnel\Service\CampagneProgressionStructure;

use Application\Service\Agent\AgentServiceAwareTrait;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use EntretienProfessionnel\Entity\Db\Campagne;
use EntretienProfessionnel\Entity\Db\CampagneProgressionStructure;
use EntretienProfessionnel\Provider\Etat\EntretienProfessionnelEtats;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use RuntimeException;
use Structure\Entity\Db\Structure;
use Structure\Entity\Db\StructureAgentForce;
use Structure\Service\Structure\StructureServiceAwareTrait;
use Structure\Service\StructureAgentForce\StructureAgentForceServiceAwareTrait;

class CampagneProgressionStructureService
{
    use ProvidesObjectManager;
    use AgentServiceAwareTrait;
    use CampagneServiceAwareTrait;
    use EntretienProfessionnelServiceAwareTrait;
    use StructureServiceAwareTrait;
    use StructureAgentForceServiceAwareTrait;


    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(CampagneProgressionStructure $campagneProgressionStructure): void
    {
        $this->getObjectManager()->persist($campagneProgressionStructure);
        $this->getObjectManager()->flush($campagneProgressionStructure);
    }

    public function update(CampagneProgressionStructure $campagneProgressionStructure): void
    {
        $this->getObjectManager()->flush($campagneProgressionStructure);
    }

    public function delete(CampagneProgressionStructure $campagneProgressionStructure): void
    {
        $this->getObjectManager()->remove($campagneProgressionStructure);
        $this->getObjectManager()->flush($campagneProgressionStructure);
    }

    public function historise(CampagneProgressionStructure $campagneProgressionStructure): void
    {
        $campagneProgressionStructure->historiser();
        $this->getObjectManager()->flush($campagneProgressionStructure);
    }

    public function restore(CampagneProgressionStructure $campagneProgressionStructure): void
    {
        $campagneProgressionStructure->historiser();
        $this->getObjectManager()->flush($campagneProgressionStructure);
    }

    /** QUERRYING *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(CampagneProgressionStructure::class)->createQueryBuilder('cps')
            ->join('cps.campagne', 'campagne')->addSelect('campagne')
            ->join('cps.structure', 'structure')->addSelect('structure');
        return $qb;
    }

    /** @return  CampagneProgressionStructure[] */
    public function getCampagneProgressionStructureByCampagne(Campagne $campagne): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('cps.campagne = :campagne')->setParameter('campagne', $campagne)
            ->andWhere('cps.histoDestruction IS NULL');
        return $qb->getQuery()->getResult();
    }

    /** @return  CampagneProgressionStructure[] */
    public function getCampagneProgressionStructureByStructure(Structure $structure): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('cps.structure = :structure')->setParameter('structure', $structure)
            ->andWhere('cps.histoDestruction IS NULL');
        return $qb->getQuery()->getResult();
    }

    public function getCampagneProgressionStructureByCampagneAndStructure(Campagne $campagne, Structure $structure): ?CampagneProgressionStructure
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('cps.structure = :structure')->setParameter('structure', $structure)
            ->andWhere('cps.histoDestruction IS NULL');
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [" . CampagneProgressionStructure::class . "] actif portent sur la même campagne [Campagne:" . $campagne->getId() . "] et la même structure [Structure:" . $structure->getId() . "].", -1, $e);
        }
        return $result;
    }

    /** @return CampagneProgressionStructure[] */
    public function getCampagneProgressionStructureByCampagneAndStructures(Campagne $campagne, array $structures): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('cps.structure in (:structures)')->setParameter('structures', $structures)
            ->andWhere('cps.histoDestruction IS NULL');
        $result = $qb->getQuery()->getResult();

        $array = [];
        /** @var CampagneProgressionStructure $item */
        foreach ($result as $item) {
            $structure = $item->getStructure();
            $array[$structure->getId()] = $item;
        }
        return $array;
    }

    /** FACADE ********************************************************************************************************/

    public function refresh(Campagne $campagne, Structure $structure, ?DateTime $date = null): void
    {
        if ($date === null) $date = new DateTime();

        // liste les structures à considérer
        $structures = $this->getStructureService()->getStructuresFilles($structure, true);

        // récupération des agents
        $agents = $this->getAgentService()->getAgentsByStructures($structures, $campagne->getDateDebut(), $campagne->getDateFin());
        $agentsForces = array_map(function (StructureAgentForce $agentForce) {
            return $agentForce->getAgent();
        }, $this->getStructureAgentForceService()->getStructureAgentsForcesByStructures($structures));
        foreach ($agentsForces as $agentForce) {
            if (!in_array($agentForce, $agents)) {
                $agents[] = $agentForce;
            }
        }

        // tris des agents
        [$obligatoires, $facultatifs, $raison] = $this->getCampagneService()->trierAgents($campagne, $agents, $structures);
        // récupérations des entretiens
        $entretiens = $this->getEntretienProfessionnelService()->getEntretienProfessionnelByCampagneAndAgents($campagne, $agents);

        // comptage des entretiens
        $validerSuperieur = [];
        $validerObservation = [];
        $validerAutorite = [];
        $validerAgent = [];
        $encours = [];
        $sans = [];

        foreach ($entretiens as $entretien) {
            if ($entretien->getEtatActif() === null) {
                $encours[] = $entretien;
                //throw new RuntimeException("L'entretien #".$entretien->getId()." na pas d'état actif");
            } else {
                switch ($entretien->getEtatActif()->getType()->getCode()) {
                    case EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_RESPONSABLE :
                        $validerSuperieur[] = $entretien;
                        break;
                    case EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_OBSERVATION :
                        $validerObservation[] = $entretien;
                        break;
                    case EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_HIERARCHIE :
                        $validerAutorite[] = $entretien;
                        break;
                    case EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_AGENT :
                        $validerAgent[] = $entretien;
                        break;
                    default :
                        $encours[] = $entretien;
                        break;
                }
            }
        }

        foreach ($obligatoires as $agent) {
            if (!isset($entretiens[$agent->getId()])) $sans[] = $agent;
        }

        $nbValiderAgent = count($validerAgent);
        $nbValiderAutorite = count($validerAutorite);
        $nbValiderObservation = count($validerObservation);
        $nbValiderSuperieur = count($validerSuperieur);
        $nbEncours = count($encours);
        $nbSans = count($sans);
        $nbTotal = $nbValiderAgent + $nbValiderAutorite + $nbValiderObservation + $nbValiderSuperieur + $nbEncours + $nbSans;

        $old = $this->getCampagneProgressionStructureByCampagneAndStructure($campagne, $structure);
        if ($old) $this->historise($old);

        $progression = new CampagneProgressionStructure();
        $progression->setCampagne($campagne);
        $progression->setStructure($structure);
        $progression->setNbComplet($nbValiderAgent);
        $progression->setNbAutorite($nbValiderAutorite);
        $progression->setNbObservation($nbValiderObservation);
        $progression->setNbSuperieur($nbValiderSuperieur);
        $progression->setNbPlanifier($nbEncours);
        $progression->setNbManquant($nbSans);
        $progression->setNbTotal($nbTotal);
        $progression->setDate($date);
        $this->create($progression);
    }

}
