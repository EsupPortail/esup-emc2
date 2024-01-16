<?php

namespace Observation\Service\ObservationType;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Observation\Entity\Db\ObservationType;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class ObservationTypeService {
    use ProvidesObjectManager;

    /** GESTION DES ENTITES ************************************************************/

    public function create(ObservationType $type): ObservationType
    {
        $this->getObjectManager()->persist($type);
        $this->getObjectManager()->flush($type);
        return $type;
    }

    public function update(ObservationType $type): ObservationType
    {
        $this->getObjectManager()->flush($type);
        return $type;
    }

    public function historise(ObservationType $type): ObservationType
    {
        $type->historiser();
        $this->getObjectManager()->flush($type);
        return $type;
    }

    public function restore(ObservationType $type): ObservationType
    {
        $type->dehistoriser();
        $this->getObjectManager()->flush($type);
        return $type;
    }

    public function delete(ObservationType $type): ObservationType
    {
        $this->getObjectManager()->remove($type);
        $this->getObjectManager()->flush($type);
        return $type;
    }

    /** REQUETAGE **********************************************************************/

    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(ObservationType::class)->createQueryBuilder('observationtype')
            ->leftJoin('observationtype.observations', 'observation')->addSelect('observation')
        ;
        return $qb;
    }

    public function getObservationType(?int $id): ?ObservationType
    {
        $qb  = $this->createQueryBuilder()
            ->andWhere('observationtype.id = :id')->setParameter('id', $id);
        try {
            $type = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".ObservationType::class."] partagent le même id [".$id."]",0,$e);
        }
        return $type;
    }

    public function getRequestedObservationType(AbstractActionController $controller, string $param="observation-type"): ?ObservationType
    {
        $id = $controller->params()->fromRoute($param);
        $type = $this->getObservationType($id);
        return $type;
    }

    /** @return ObservationType[] */
    public function getObservationsTypes(string $champ='code', string $ordre='ASC', bool $histo = false): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('observationtype.'. $champ, $ordre);

        if (!$histo) $qb = $qb->andWhere('observationtype.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return string[] */
    public function getObservationsTypesAsOption(string $champ='code', string $ordre='ASC', bool $histo = false): array
    {
        $result = $this->getObservationsTypes($champ, $ordre, $histo);
        $options = [];
        foreach ($result as $item) {
            $label  = ($item->getCategorie())??"Sans catégorie";
            $label .= " > ";
            $label .= $item->getLibelle();
            $options[$item->getId()] = $label;
        }
        return $options;
    }

    public function getObservationTypeByCode(?string $code): ?ObservationType
    {
        $qb  = $this->createQueryBuilder()
            ->andWhere('observationtype.code = :code')->setParameter('code', $code);
        try {
            $type = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".ObservationType::class."] partagent le même code [".$code."]",0,$e);
        }
        return $type;
    }

    /** @return ObservationType[] */
    public function getObservationsTypesWithFiltre(array $params): array
    {
        $qb  = $this->createQueryBuilder();
        if (isset($params['categorie'])) {
            $qb = $qb->andWhere('observationtype.categorie = :categorie')->setParameter('categorie', $params['categorie']);
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** FACADE *************************************************************************/
}