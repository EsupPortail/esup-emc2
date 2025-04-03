<?php

namespace Fichier\Service\Nature;

use Doctrine\ORM\NonUniqueResultException;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Fichier\Entity\Db\Nature;
use RuntimeException;

class NatureService
{
    use ProvidesObjectManager;

    public function getNaturesAsOptions(): array
    {
        $qb = $this->getObjectManager()->getRepository(Nature::class)->createQueryBuilder('nature')
            ->orderBy('nature.id');
        $result = $qb->getQuery()->getResult();

        $options = [];
        $options[null] = "Sélectionner une nature de fichier ...";
        /** @var Nature $item */
        foreach ($result as $item) {
            $options[$item->getId()] = $item->getLibelle();
        }
        return $options;
    }

    public function getNature(?int $id): ?Nature
    {
        $qb = $this->getObjectManager()->getRepository(Nature::class)->createQueryBuilder('nature')
            ->andWhere('nature.id = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Nature partagent le même identifiant [" . $id . "]",0, $e);
        }
        return $result;
    }

    public function getNatureByCode(string $code): ?Nature
    {
        $qb = $this->getObjectManager()->getRepository(Nature::class)->createQueryBuilder('nature')
            ->andWhere('nature.code = :code')
            ->setParameter('code', $code);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Nature partagent le même code [" . $code . "]", 0,$e);
        }
        return $result;
    }
}