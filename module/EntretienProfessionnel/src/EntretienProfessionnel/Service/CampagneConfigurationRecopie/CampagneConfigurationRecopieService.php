<?php

namespace EntretienProfessionnel\Service\CampagneConfigurationRecopie;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use EntretienProfessionnel\Entity\Db\CampagneConfigurationRecopie;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class CampagneConfigurationRecopieService
{
    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(CampagneConfigurationRecopie $recopie): void
    {
        $this->getObjectManager()->persist($recopie);
        $this->getObjectManager()->flush($recopie);
    }

    public function update(CampagneConfigurationRecopie $recopie): void
    {
        $this->getObjectManager()->flush($recopie);
    }

    public function delete(CampagneConfigurationRecopie $recopie): void
    {
        $this->getObjectManager()->remove($recopie);
        $this->getObjectManager()->flush($recopie);
    }

    public function historise(CampagneConfigurationRecopie $recopie): void
    {
        $recopie->historiser();
        $this->getObjectManager()->flush($recopie);
    }

    public function restore(CampagneConfigurationRecopie $recopie): void
    {
        $recopie->dehistoriser();
        $this->getObjectManager()->flush($recopie);
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(CampagneConfigurationRecopie::class)->createQueryBuilder('configuration')
            ->join('configuration.from', 'champFrom')->addSelect('champFrom')
            ->join('champFrom.type', 'typeFrom')->addSelect('typeFrom')
            ->join('configuration.to', 'champTo')->addSelect('champTo')
            ->join('champTo.type', 'typeTo')->addSelect('typeTo')
        ;
        return $qb;
    }

    public function getCampagneConfigurationRecopie(?int $id): ?CampagneConfigurationRecopie
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('configuration.id = :id')->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".CampagneConfigurationRecopie::class."] partagent le même id [".$id."]", 0,$e);
        }
        return $result;
    }

    public function getRequestedCampagneConfigurationRecopie(AbstractActionController $controller, string $param="campagne-configuration-recopie"): ?CampagneConfigurationRecopie
    {
        $id = $controller->params()->fromRoute($param);
        $result  = $this->getCampagneConfigurationRecopie($id);
        return $result;
    }

    /** @return  CampagneConfigurationRecopie[] */
    public function getCampagneConfigurationRecopies(bool $withHisto = false): array
    {
        $qb = $this->createQueryBuilder();
        if (!$withHisto) $qb = $qb->andWhere('configuration.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** FACADE ********************************************************************************************************/

    public function verifierTypes() : string
    {
        $log = "";
        $recopies = $this->getCampagneConfigurationRecopies();
        foreach ($recopies as $recopie) {
            $verif = $this->verifierType($recopie);
            if ($verif !== true) $log .= "Les types des champ pour la recopie <strong>". $recopie->getFrom()->getLibelle() . " &Rightarrow; ". $recopie->getTo()->getLibelle(). "</strong> sont incompatibles (<code>".$recopie->getFrom()->getType()->getLibelle()."</code>&ne;<code>".$recopie->getTo()->getType()->getLibelle()."</code>)";
        }
        return $log;
    }

    public function verifierType(CampagneConfigurationRecopie $recopie): bool|string
    {
        if ($recopie->getFrom()->getType() !== $recopie->getTo()->getType()) {
            return "Les types des champ pour la recopie <strong>". $recopie->getFrom()->getLibelle() . " &Rightarrow; ". $recopie->getTo()->getLibelle(). "</strong> sont incompatibles (<code>".$recopie->getFrom()->getType()->getLibelle()."</code>&ne;<code>".$recopie->getTo()->getType()->getLibelle()."</code>)";
        }
        return true;
    }


}

