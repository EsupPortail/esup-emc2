<?php

namespace EntretienProfessionnel\Service\Observateur;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use EntretienProfessionnel\Entity\Db\Observateur;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;
use UnicaenUtilisateur\Entity\Db\AbstractUser;
use UnicaenUtilisateur\Entity\Db\UserInterface;

class ObservateurService {
    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(Observateur $observateur): Observateur
    {
        $this->getObjectManager()->persist($observateur);
        $this->getObjectManager()->flush($observateur);
        return $observateur;
    }

    public function update(Observateur $observateur): Observateur
    {
        $this->getObjectManager()->flush($observateur);
        return $observateur;
    }

    public function historise(Observateur $observateur): Observateur
    {
        $observateur->historiser();
        $this->getObjectManager()->flush($observateur);
        return $observateur;
    }

    public function restore(Observateur $observateur): Observateur
    {
        $observateur->dehistoriser();
        $this->getObjectManager()->flush($observateur);
        return $observateur;
    }

    public function delete(Observateur $observateur): Observateur
    {
        $this->getObjectManager()->remove($observateur);
        $this->getObjectManager()->flush($observateur);
        return $observateur;
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(Observateur::class)->createQueryBuilder('observateur')
            ->join('observateur.entretien', 'entretien')->addSelect('entretien')
            ->join('entretien.campagne', 'campagne')->addSelect('campagne')
            ->join('entretien.agent', 'agent')->addSelect('agent')
            ->join('observateur.user', 'user')->addSelect('user')
            ->orderBy('user.displayName')
        ;
        return $qb;
    }

    public function getObservateur(?int $id): ?Observateur
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('observateur.id = :id')->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".Observateur::class."] partagent le même id [".$id."]",0,$e);
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
        $qb = $this->createQueryBuilder();
        if (!$withHisto) { $qb = $qb->andWhere('observateur.histoDestruction IS NULL'); }

        return $qb->getQuery()->getResult();
    }

    /** @return Observateur[] */
    public function getObservateursByEntretienProfessionnel(EntretienProfessionnel $entretienProfessionnel, bool $withHisto = false): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('observateur.entretien = :entretien')->setParameter('entretien', $entretienProfessionnel)
        ;
        if (!$withHisto) { $qb = $qb->andWhere('observateur.histoDestruction IS NULL'); }

        return $qb->getQuery()->getResult();
    }

    /** @return Observateur[] */
    public function getObservateursByUser(UserInterface $user, bool $withHisto = false): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('observateur.user = :user')->setParameter('user', $user)
        ;
        if (!$withHisto) { $qb = $qb->andWhere('observateur.histoDestruction IS NULL'); }

        return $qb->getQuery()->getResult();
    }

    /** FONCTION POUR LA GESTION DES RôLES AUTOMATIQUES */

    /** @return AbstractUser[] */
    public function getUsersInObservateurs(): array
    {
        $observateurs = $this->getObservateurs();
        $users = [];
        foreach ($observateurs as $observateur)
        {
            $users[$observateur->getUser()->getId()] = $observateur->getUser();
        }
        return $users;
    }

    public function isObservateur(EntretienProfessionnel $entretien, ?AbstractUser $getUtilisateur): bool
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('observateur.entretien = :entretien')->setParameter('entretien', $entretien)
            ->andWhere('observateur.user = :user')->setParameter('user', $getUtilisateur)
            ->andWhere('observateur.histoDestruction IS NULL')
        ;
        $result = $qb->getQuery()->getResult();
        return !empty($result);
    }

    /** @return Observateur[] */
    public function getObservateursByTerm(mixed $term): array
    {
        $qb = $this->createQueryBuilder();
        $qb = $qb->andWhere("LOWER(user.displayName) like :search")
            ->setParameter('search', '%' . strtolower($term) . '%');

        $result = $qb->getQuery()->getResult();
        $observateurs = [];
        foreach ($result as $observateur) $observateurs[$observateur->getUser()->getId()] = $observateur;
        return $observateurs;
    }

    /** @var Observateur[] $observateurs */
    public function formatObservateurJSON(array $observateurs): array
    {
        $result = [];
        foreach ($observateurs as $observateur) {
            $result[] = array(
                'id' => $observateur->getUser()->getId(),
                'label' => $observateur->getUser()->getDisplayName(),
                'extra' => "<span class='badge' style='background-color: slategray;'>" . $observateur->getUser()->getEmail() . "</span>",
            );
        }
        usort($result, function ($a, $b) {
            return strcmp($a['label'], $b['label']);
        });
        return $result;
    }

    /** @return Observateur[] */
    public function getObservateursWithFiltre(array $params = []): array
    {
        $qb = $this->createQueryBuilder();

        if (isset($params['campagne'])) $qb = $qb->andWhere('campagne.id = :campagne')->setParameter('campagne', $params['campagne']);
        if (isset($params['agent-id'])) $qb = $qb->andWhere('agent.id = :agent')->setParameter('agent', $params['agent-id']);
        if (isset($params['observateur-id'])) $qb = $qb->andWhere('user.id = :user')->setParameter('user', $params['observateur-id']);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** FACADE ********************************************************************************************************/

}