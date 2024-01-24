<?php

namespace FicheMetier\Service\ThematiqueType;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use FicheMetier\Entity\Db\ThematiqueType;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class ThematiqueTypeService 
{
    use ProvidesObjectManager;
    
    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(ThematiqueType $thematiqueType): ThematiqueType
    {
        $this->getObjectManager()->persist($thematiqueType);
        $this->getObjectManager()->flush($thematiqueType);
        return $thematiqueType;
    }

    public function update(ThematiqueType $thematiqueType): ThematiqueType
    {
        $this->getObjectManager()->flush($thematiqueType);
        return $thematiqueType;
    }

    public function historise(ThematiqueType $thematiqueType): ThematiqueType
    {
        $thematiqueType->historiser();
        $this->getObjectManager()->flush($thematiqueType);
        return $thematiqueType;
    }

    public function restore(ThematiqueType $thematiqueType): ThematiqueType
    {
        $thematiqueType->dehistoriser();
        $this->getObjectManager()->flush($thematiqueType);
        return $thematiqueType;
    }

    public function delete(ThematiqueType $thematiqueType): ThematiqueType
    {
        $this->getObjectManager()->remove($thematiqueType);
        $this->getObjectManager()->flush($thematiqueType);
        return $thematiqueType;
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(ThematiqueType::class)->createQueryBuilder('thematiquetype');
        return $qb;
    }

    public function getThematiqueType(?int $id): ?ThematiqueType
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('thematiquetype.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".ThematiqueType::class."] partagent le même id [".$id."]");
        }
        return $result;
    }

    public function getThematiqueTypeByCode(?string $code): ?ThematiqueType
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('thematiquetype.code = :code')->setParameter('code', $code);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".ThematiqueType::class."] partagent le même code [".$code."]");
        }
        return $result;
    }

    public function getRequestedThematiqueType(AbstractActionController $controller, string $param='thematique-type'): ?ThematiqueType
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getThematiqueType($id);
    }

    /** @return ThematiqueType[] */
    public function getThematiquesTypes(string $champ='libelle', string $ordre='ASC', bool $histo=false): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('thematiquetype.' . $champ, $ordre);

        if (!$histo) $qb = $qb->andWhere('thematiquetype.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** FACADE ********************************************************************************************************/
}