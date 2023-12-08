<?php

namespace Formation\Service\InscriptionExterne;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Formation\Entity\Db\FormationInstance;
use Formation\Entity\Db\InscriptionExterne;
use Formation\Entity\Db\StagiaireExterne;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class InscriptionExterneService {
    use ProvidesObjectManager;
    
    /** Gestion de l'entité ****************************************************/
    
    public function create(InscriptionExterne $inscription): InscriptionExterne
    {
        $this->getObjectManager()->persist($inscription);
        $this->getObjectManager()->flush($inscription);
        return $inscription;
    }

    public function update(InscriptionExterne $inscription): InscriptionExterne
    {
        $this->getObjectManager()->flush($inscription);
        return $inscription;
    }

    public function historise(InscriptionExterne $inscription): InscriptionExterne
    {
        $inscription->historiser();
        $this->getObjectManager()->flush($inscription);
        return $inscription;
    }

    public function restore(InscriptionExterne $inscription): InscriptionExterne
    {
        $inscription->dehistoriser();
        $this->getObjectManager()->flush($inscription);
        return $inscription;
    }

    public function delete(InscriptionExterne $inscription): InscriptionExterne
    {
        $this->getObjectManager()->remove($inscription);
        $this->getObjectManager()->flush($inscription);
        return $inscription;
    }
    
    /** Querying ***************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(InscriptionExterne::class)->createQueryBuilder('inscriptionexterne')
            ->leftJoin('inscriptionexterne.session', 'session')->addSelect('session')
            ->leftJoin('inscriptionexterne.stagiaire', 'stagiaire')->addSelect('stagiaire')
        ;
        return $qb;
    }

    public function getInscriptionExterne(?int $id): ?InscriptionExterne
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('inscriptionexterne.id = :id')->setParameter('id', $id);
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".InscriptionExterne::class."] partagent le même id [".$id."]",0,$e );
        }
        return $result;
    }

    public function getRequestedInscriptionExterne(AbstractActionController $controller, string $param="inscription-externe"): ?InscriptionExterne
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getInscriptionExterne($id);
        return $result;
    }

    /** @return InscriptionExterne[] */
    public function getInscriptionsExternes(string $champ="histoCreation", string $ordre="ASC", bool $withHisto = false): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('inscriptionexterne.'.$champ, $ordre);
        if (!$withHisto) $qb = $qb->andWhere('inscriptioninterne.histoDestruction IS NULL');
        $result = $qb->getQuery()->getResult();

        return $result;
    }

    /** Facade *****************************************************************/

    public function createWith(FormationInstance $session, StagiaireExterne $stagiaire, ?string $description = null) : InscriptionExterne
    {
        $inscription = new InscriptionExterne();
        $inscription->setStagiaire($stagiaire);
        $inscription->setSession($session);
        $inscription->setDescription($description);

        $this->create($inscription);

        return $inscription;
    }
}