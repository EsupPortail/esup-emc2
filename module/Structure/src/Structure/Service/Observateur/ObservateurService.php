<?php

namespace Structure\Service\Observateur;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;
use Structure\Entity\Db\Observateur;
use Structure\Entity\Db\Structure;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenUtilisateur\Entity\Db\AbstractUser;
use UnicaenUtilisateur\Entity\Db\UserInterface;

class ObservateurService
{
    use ProvidesObjectManager;
    use StructureServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(Observateur $observateur): Observateur
    {
        $this->getObjectManager()->persist($observateur);
        $this->getObjectManager()->flush();
        return $observateur;
    }

    public function update(Observateur $observateur): Observateur
    {
        $this->getObjectManager()->flush();
        return $observateur;
    }

    public function historise(Observateur $observateur): Observateur
    {
        $observateur->historiser();
        $this->getObjectManager()->flush();
        return $observateur;
    }

    public function restore(Observateur $observateur): Observateur
    {
        $observateur->dehistoriser();
        $this->getObjectManager()->flush();
        return $observateur;
    }

    public function delete(Observateur $observateur): Observateur
    {
        $this->getObjectManager()->remove($observateur);
        $this->getObjectManager()->flush();
        return $observateur;
    }

    /** QUERYING ******************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(Observateur::class)->createQueryBuilder('observateur')
            ->addSelect('structure')->join('observateur.structure', 'structure')
            ->addSelect('utilisateur')->join('observateur.utilisateur', 'utilisateur');
        return $qb;
    }

    public function getObservateur(?int $id): ?Observateur
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('observateur.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [" . Observateur::class . "] paragent le même id [" . $id . "]", 0, $e);
        }
        return $result;
    }

    public function getRequestedObservateur(AbstractActionController $controller, string $param = 'observateur'): ?Observateur
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getObservateur($id);
    }

    /** @return Observateur[] */
    public function getObservateurs(bool $withHisto = false): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('utilisateur.displayName', 'ASC');
        if (!$withHisto) {
            $qb = $qb->andWhere('observateur.histoDestruction IS NULL');
        }
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Structure[] $structures
     * @return Observateur[]
     */
    public function getObservateursByStructures(array $structures, bool $withHisto = false): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('observateur.structure IN (:structures)')->setParameter("structures", $structures);
        if (!$withHisto) {
            $qb = $qb->andWhere('observateur.histoDestruction IS NULL');
        }
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return Observateur[] */
    public function getObservateursByUtilisateur(?UserInterface $user, bool $withHisto = false): array
    {
        if ($user === null) return [];
        $qb = $this->createQueryBuilder()
            ->andWhere('observateur.utilisateur = :utilisateur')->setParameter("utilisateur", $user);

        if (!$withHisto) $qb = $qb->andWhere('observateur.histoDestruction IS NULL');
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function isObservateur(array $structures, UserInterface $user, bool $withHisto = false): bool
    {
        $observateurs = $this->getObservateursByUtilisateur($user);

        $structuresEnObservations = [];
        foreach ($observateurs as $observateur) {
            $structure = $observateur->getStructure();
            $structures_ = $this->getStructureService()->getStructuresFilles($structure, true);
            foreach ($structures_ as $structure_) {
                $structuresEnObservations[$structure_->getId()] = $structure_;
            }
        }

        $structuresAObservees = [];
        foreach ($structures as $structure) {
            $structures_ = $this->getStructureService()->getStructuresFilles($structure, true);
            foreach ($structures_ as $structure_) {
                $structuresAObservees[$structure_->getId()] = $structure_;
            }
        }

        foreach ($structuresAObservees as $structureAObservee) {
            if (in_array($structureAObservee, $structuresEnObservations, true)) {
                return true;
            }
        }
        return false;
    }

    /** FACADE ********************************************************************************************************/

    public function createObservateur(Structure $structure, AbstractUser $user, ?string $description = null): Observateur
    {
        $observateur = new Observateur();
        $observateur->setStructure($structure);
        $observateur->setUtilisateur($user);
        $observateur->setDescription($description);
        $this->create($observateur);
        return $observateur;
    }

    public function getUsersInObservateurs(): array
    {
        $result = $this->getObservateurs();
        $users = [];
        foreach ($result as $observateur) {
            $user = $observateur->getUtilisateur();
            if ($user !== null) $users[$user->getId()] = $user;
        }
        return $users;
    }


}