<?php

namespace Structure\Service\StructureResponsable;

use DateInterval;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;
use Structure\Entity\Db\Structure;
use Structure\Entity\Db\StructureResponsable;

class StructureResponsableService
{
    use ProvidesObjectManager;

    /** Gestion des entités ******************************************************/

    public function create(StructureResponsable $responsable): void
    {
        $this->getObjectManager()->persist($responsable);
        $this->getObjectManager()->flush($responsable);
    }

    public function update(StructureResponsable $responsable): void
    {
        $this->getObjectManager()->flush($responsable);
    }

    public function historise(StructureResponsable $responsable): void
    {
        $responsable->historiser();
        $this->getObjectManager()->flush($responsable);
    }

    public function restore(StructureResponsable $responsable): void
    {
        $responsable->dehistoriser();
        $this->getObjectManager()->flush($responsable);
    }

    public function delete(StructureResponsable $responsable): void
    {
        $this->getObjectManager()->remove($responsable);
        $this->getObjectManager()->flush($responsable);
    }

    /** querying *****************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(StructureResponsable::class)->createQueryBuilder('responsable')
            ->addSelect('structure')->join('responsable.structure', 'structure')
            ->addSelect('agent')->join('responsable.agent', 'agent')
            ->andWhere('responsable.deletedOn IS NULL');
        return $qb;
    }

    public function getStructureResponsable(?string $id): ?StructureResponsable
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('responsable.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [" . StructureResponsable::class . "] partagent le même id [" . $id . "]", 0, $e);
        }
        return $result;
    }

    public function getRequestedStructureResponsable(AbstractActionController $controller, string $param = 'structure-responsable'): ?StructureResponsable
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getStructureResponsable($id);
        return $result;
    }

    /** @return StructureResponsable[] */
    public function getStructureResponsables(): array
    {
        $qb = $this->createQueryBuilder();
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return StructureResponsable[] */
    public function getStructureResponsableByStructure(Structure $structure, bool $histo = false, bool $encours = true, string $champ = 'id', $ordre = 'ASC'): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('responsable.structure = :structure')->setParameter('structure', $structure)
            ->andWhere('responsable.deletedOn IS NULL')
            ->andWhere('agent.deletedOn IS NULL')
            ->andWhere('structure.deletedOn IS NULL')
            ->orderBy('responsable.' . $champ, $ordre);
        if ($histo === false) $qb = $qb->andWhere('responsable.histoDestruction IS NULL');
        if ($encours === true) {
            $qb = $qb->andWhere('responsable.dateDebut IS NULL OR responsable.dateDebut <= :now')
                ->andWhere('responsable.dateFin IS NULL OR responsable.dateFin >= :now')
                ->setParameter('now', new DateTime());
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }


    /** façade ********************************************************************************************************/

    public function historiseAll(?Structure $structure): void
    {
        if ($structure !== null) {
            $gestionnaires = $this->getStructureResponsableByStructure($structure);
            foreach ($gestionnaires as $gestionnaire) $this->historise($gestionnaire);
        }
    }

    public function clotureAll(?Structure $structure, ?DateTime $date = null): void
    {
        if ($date === null) $date = new DateTime();
        $dateCloture = DateTime::createFromFormat('Y-m-d H:i:s', $date->sub(new DateInterval('P1D'))->format('Y-m-d 18:00:00'));
        if ($structure !== null) {
            $gestionnaires = $this->getStructureResponsableByStructure($structure);
            foreach ($gestionnaires as $gestionnaire) {
                $gestionnaire->setDateFin($dateCloture);
                $this->update($gestionnaire);
            }
        }
    }
}
