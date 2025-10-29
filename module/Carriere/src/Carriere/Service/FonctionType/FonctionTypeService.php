<?php

namespace Carriere\Service\FonctionType;

use Carriere\Entity\Db\FonctionType;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use FicheMetier\Entity\Db\CodeEmploiType;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class FonctionTypeService
{
    use ProvidesObjectManager;

    /** GESTION DE L'ENTITE *******************************************************************************************/

    public function create(FonctionType $fonctionType): void
    {
        $this->getObjectManager()->persist($fonctionType);
        $this->getObjectManager()->flush($fonctionType);
    }

    public function update(FonctionType $fonctionType): void
    {
        $this->getObjectManager()->flush($fonctionType);
    }

    public function historise(FonctionType $fonctionType): void
    {
        $fonctionType->historiser();
        $this->getObjectManager()->flush($fonctionType);
    }

    public function restore(FonctionType $fonctionType): void
    {
        $fonctionType->dehistoriser();
        $this->getObjectManager()->flush($fonctionType);
    }

    public function delete(FonctionType $fonctionType): void
    {
        $this->getObjectManager()->remove($fonctionType);
        $this->getObjectManager()->flush($fonctionType);
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(FonctionType::class)->createQueryBuilder('fonctiontype');
        return $qb;
    }

    public function getFonctionType(?int $id): ?FonctionType
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('fonctiontype.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".FonctionType::class."] partagent le même id [".$id."]",0,$e);
        }
        return $result;
    }

    public function getRequestedFonctionType(AbstractActionController $controller, string $param='fonction-type'): ?FonctionType
    {
        $id = $controller->params()->fromRoute($param);
        $resutl = $this->getFonctionType($id);
        return $resutl;
    }

    /** @return FonctionType[] */
    public function getFonctionsTypes(bool $withHisto = false): array
    {
        $qb = $this->createQueryBuilder();
        if (!$withHisto) $qb = $qb->andWhere('fonctiontype.histoDestruction IS NULL');
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getFonctionsTypesAsOptions(bool $withHisto = false): array
    {
        $result  = $this->getFonctionsTypes($withHisto);
        $options = [];
        foreach ($result as $fonctionType) {
            $options[$fonctionType->getId()] = $fonctionType->getLibelle();
        }
        return $options;
    }

    public function getFonctionTypeByCode(?string $code): ?FonctionType
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('fonctiontype.code = :code')->setParameter('code', $code)
            ->andWhere('fonctiontype.histoDestruction IS NULL')
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".FonctionType::class."] partagent le même code [".$code."] (Attention, cela tient compte du caractère historisé)",0,$e);
        }
        return $result;
    }

    /** FACADE ********************************************************************************************************/

    public function isCodeDisponible(FonctionType $type, ?string $code = null): bool
    {
        $result = $this->getFonctionTypeByCode($type->getCode()??$code);
        if ($result === null) return true;
        if ($result === $type) return true;
        return false;
    }

    /** @return CodeEmploiType[] */
    public function getCodesEmploisTypesByCodeFonction(FonctionType $type, bool $withHisto = false): array
    {
        $qb = $this->getObjectManager()->getRepository(CodeEmploiType::class)->createQueryBuilder('codeemploitype')
            ->andWhere('codeemploitype.codefonction = :type')->setParameter('type', $type)
        ;
        if (!$withHisto) $qb = $qb->andWhere('codeemploitype.histoDestruction IS NULL');
        $result  = $qb->getQuery()->getResult();
        return $result;
    }
}