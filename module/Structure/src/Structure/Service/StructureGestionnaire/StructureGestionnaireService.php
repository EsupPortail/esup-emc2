<?php

namespace Structure\Service\StructureGestionnaire;

use DateInterval;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;
use Structure\Entity\Db\Structure;
use Structure\Entity\Db\StructureGestionnaire;

class StructureGestionnaireService
{
    use ProvidesObjectManager;

    /** Gestion des entités ******************************************************/

    public function create(StructureGestionnaire $gestionnaire): void
    {
        $this->getObjectManager()->persist($gestionnaire);
        $this->getObjectManager()->flush($gestionnaire);
    }

    public function update(StructureGestionnaire $gestionnaire): void
    {
        $this->getObjectManager()->flush($gestionnaire);
    }

    public function historise(StructureGestionnaire $gestionnaire): void
    {
        $gestionnaire->historiser();
        $this->getObjectManager()->flush($gestionnaire);
    }

    public function restore(StructureGestionnaire $gestionnaire): void
    {
        $gestionnaire->dehistoriser();
        $this->getObjectManager()->flush($gestionnaire);
    }

    public function delete(StructureGestionnaire $gestionnaire): void
    {
        $this->getObjectManager()->remove($gestionnaire);
        $this->getObjectManager()->flush($gestionnaire);
    }

    /** querying *****************************************************************************************/

    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(StructureGestionnaire::class)->createQueryBuilder('gestionnaire')
            ->addSelect('structure')->join('gestionnaire.structure', 'structure')
            ->addSelect('agent')->join('gestionnaire.agent', 'agent')
            ->andWhere('gestionnaire.deletedOn IS NULL')
        ;
        return $qb;
    }

    public function getStructureGestionnaire(?string $id) : ?StructureGestionnaire
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('gestionnaire.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".StructureGestionnaire::class."] partagent le même id [".$id."]", 0, $e);
        }
        return $result;
    }

    public function getRequestedStructureGestionnaire(AbstractActionController $controller, string $param='structure-gestionnaire') : ?StructureGestionnaire
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getStructureGestionnaire($id);
        return $result;
    }

    /** @return StructureGestionnaire[] */
    public function getStructureGestionnaires(): array
    {
        $qb = $this->createQueryBuilder();
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return StructureGestionnaire[] */
    public function getStructureGestionnaireByStructure(Structure $structure, bool $histo = false, bool $encours = true, string $champ = 'id', $ordre = 'ASC'): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('gestionnaire.structure = :structure')->setParameter('structure', $structure)
            ->andWhere('gestionnaire.deletedOn IS NULL')
            ->andWhere('agent.deletedOn IS NULL')
            ->andWhere('structure.deletedOn IS NULL')
            ->orderBy('gestionnaire.' . $champ, $ordre);
        if ($histo === false) $qb = $qb->andWhere('gestionnaire.histoDestruction IS NULL');
        if ($encours === true) {
            $qb = $qb->andWhere('gestionnaire.dateDebut IS NULL OR gestionnaire.dateDebut <= :now')
                ->andWhere('gestionnaire.dateFin IS NULL OR gestionnaire.dateFin >= :now')
                ->setParameter('now', new DateTime());
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }


    /** façade ********************************************************************************************************/

    public function historiseAll(?Structure $structure): void
    {
        if ($structure !== null) {
            $gestionnaires = $this->getStructureGestionnaireByStructure($structure);
            foreach ($gestionnaires as $gestionnaire) $this->historise($gestionnaire);
        }
    }

    public function clotureAll(?Structure $structure, ?DateTime $date = null) : void
    {
        if ($date === null) $date = new DateTime();
        $dateCloture = DateTime::createFromFormat('Y-m-d H:i:s' , $date->sub(new DateInterval('P1D'))->format('Y-m-d 18:00:00'));
        if ($structure !== null) {
            $gestionnaires = $this->getStructureGestionnaireByStructure($structure);
            foreach ($gestionnaires as $gestionnaire) {
                $gestionnaire->setDateFin($dateCloture);
                $this->update($gestionnaire);
            }
        }
    }

}