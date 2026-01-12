<?php

namespace Carriere\Service\Correspondance;

use Agent\Entity\Db\AgentGrade;
use Carriere\Entity\Db\Correspondance;
use Carriere\Entity\Db\CorrespondanceType;
use Carriere\Service\CorrespondanceType\CorrespondanceTypeServiceAwareTrait;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class CorrespondanceService {

    use ProvidesObjectManager;
    use CorrespondanceTypeServiceAwareTrait;

    /** GESTION DES ENITIES *******************************************************************************************/

    public function create(Correspondance $correspondance): Correspondance
    {
        $correspondance->setInsertedOn(new DateTime());
        $correspondance->setSourceId("EMC2");

        $this->getObjectManager()->persist($correspondance);
        $this->getObjectManager()->flush();
        return $correspondance;
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(Correspondance::class)->createQueryBuilder('correspondance')
            ->leftJoin('correspondance.type', 'ctype')->addSelect('ctype')
            ->andWhere('correspondance.deletedOn IS NULL');
        return $qb;
    }

    /** @return Correspondance[] */
    public function getCorrespondances(string $champ = 'categorie', string $ordre = 'ASC', bool $avecAgent=true, bool $avecHisto = false) : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('correspondance.' . $champ, $ordre)
        ;

        if ($avecAgent) {
            $qb = $qb
                ->addSelect('agentGrade')->join('correspondance.agentGrades', 'agentGrade')
                ->andWhere('agentGrade.deletedOn IS NULL')
                ->addSelect('agent')->join('agentGrade.agent','agent')
                ->andWhere('agent.deletedOn IS NULL')
            ;
            $qb = AgentGrade::decorateWithActif($qb, 'agentGrade');
        }

        if ($avecHisto === false) {
            $qb = Correspondance::decorateWithActif($qb, 'correspondance');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getCorrespondancesAsOptions(string $champ = 'categorie', string $ordre = 'ASC', bool $avecAgent=false) : array
    {
        $correspondances = $this->getCorrespondances($champ, $ordre, $avecAgent);
        $options = [];
        foreach($correspondances as $correspondance) {
            $options[$correspondance->getId()] = (($correspondance->getType())?$correspondance->getType()->getLibelleCourt():""). " ". $correspondance->getLibelleCourt() . " - " . $correspondance->getLibelleLong();
        }
        return $options;
    }

    public function getCorrespondance(?int $id, bool $avecAgent = true) : ?Correspondance
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('correspondance.id = :id')
            ->setParameter('id', $id)
        ;

        if ($avecAgent) {
            $qb = $qb->addSelect('agentGrade')->join('correspondance.agentGrades', 'agentGrade')
                ->addSelect('agent')->join('agentGrade.agent','agent')
                ->andWhere('agent.deletedOn IS NULL')
            ;
        }

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Correcpondance partagent le même id [".$id."]",0,$e);
        }
        return $result;
    }

    public function getRequestedCorrespondance(AbstractActionController $controller, string $param = 'correspondance') : ?Correspondance
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getCorrespondance($id);
    }

    /** @return Correspondance[] */
    public function getCorrespondancesByType(?CorrespondanceType $type) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('correspondance.type = :type')->setParameter('type', $type)
            ->orderBy('correspondance.libelleCourt', 'ASC')
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getCorrespondanceByTypeAndCode(string $typeCode, string $correspondanceCode): ?Correspondance
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('ctype.code = :typeCode')->setParameter('typeCode', $typeCode)
            ->andWhere('correspondance.categorie = :code')->setParameter('code', $correspondanceCode)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".Correspondance::class."] partagent le même code [".$typeCode."|".$correspondanceCode."]",0,$e);
        }
        return $result;
    }

    public function getCorrespondanceByTypeCodeAndLibelle(string $typeCode, string $libelle): ?Correspondance
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('ctype.code = :typeCode')->setParameter('typeCode', $typeCode)
            ->andWhere('correspondance.libelleLong = :libelle')->setParameter('libelle', $libelle)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".Correspondance::class."] partagent le même code [".$typeCode."|".$libelle."]",0,$e);
        }
        return $result;
    }



    public function createWith(string $typeCode, string $correspondanceCode, string $libelle, bool $persist = true): ?Correspondance
    {
        $type = $this->getCorrespondanceTypeService()->getCorrespondanceTypeByCode($typeCode);
        if ($type === null) { throw new RuntimeException("Aucun [".CorrespondanceType::class."] ne porte le code [".$typeCode."]"); }

        $correspondance = new Correspondance();
        $correspondance->setId(1000000 + ord($correspondanceCode));
        $correspondance->setType($type);
        $correspondance->setCategorie($correspondanceCode);
        $correspondance->setLibelleLong($libelle);

        if ($persist) $this->create($correspondance);
        return $correspondance;
    }
}
