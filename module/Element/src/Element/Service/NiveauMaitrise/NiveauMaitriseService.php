<?php

namespace Element\Service\NiveauMaitrise;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Element\Entity\Db\NiveauMaitrise;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class NiveauMaitriseService
{
    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(NiveauMaitrise $maitrise): void
    {
        $this->getObjectManager()->persist($maitrise);
        $this->getObjectManager()->flush($maitrise);
    }

    public function update(NiveauMaitrise $maitrise): void
    {
        $this->getObjectManager()->flush($maitrise);
    }

    public function historise(NiveauMaitrise $maitrise): void
    {
        $maitrise->historiser();
        $this->getObjectManager()->flush($maitrise);
    }

    public function restore(NiveauMaitrise $maitrise): void
    {
        $maitrise->dehistoriser();
        $this->getObjectManager()->flush($maitrise);
    }

    public function delete(NiveauMaitrise $maitrise): void
    {
        $this->getObjectManager()->remove($maitrise);
        $this->getObjectManager()->flush($maitrise);
    }

    /** QUERY *********************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(NiveauMaitrise::class)->createQueryBuilder('maitrise');
        return $qb;
    }

    /** @return NiveauMaitrise[] */
    public function getMaitrisesNiveaux(string $type = "", string $champ = 'niveau', string $ordre = 'ASC', bool $nonHistorise = false): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('maitrise.' . $champ, $ordre);

        if ($type !== null and $type !== "") {
            $qb = $qb->andWhere('maitrise.type = :type')
                ->setParameter('type', $type);
        }

        if ($nonHistorise !== true) $qb = $qb->andWhere('maitrise.histoDestruction IS NULL');
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getMaitrisesNiveauxAsOptions(string $type = "", string $champ = 'niveau', string $ordre = 'ASC', bool $nonHistorise = false, bool $withZero = false): array
    {
        $maitrises = $this->getMaitrisesNiveaux($type, $champ, $ordre, $nonHistorise);
        $options = [];
        if ($withZero) $options[0] = "";
        foreach ($maitrises as $maitrise) {
            $options[$maitrise->getId()] = $maitrise->getLibelle();
        }
        return $options;
    }

    public function getMaitriseNiveau(?int $id): ?NiveauMaitrise
    {
        if ($id === null) return null;
        $qb = $this->createQueryBuilder()
            ->andWhere('maitrise.id = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs MaitriseNiveau partagent le même id [" . $id . "]", 0, $e);
        }
        return $result;
    }

    public function getRequestedMaitriseNiveau(AbstractActionController $controller, string $param = 'maitrise'): ?NiveauMaitrise
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getMaitriseNiveau($id);
        return $result;
    }

    public function getMaitriseNiveauByNiveau(string $type, int $niveau): ?NiveauMaitrise
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('maitrise.niveau = :niveau')
            ->setParameter('niveau', $niveau)
            ->andWhere('maitrise.type = :type')
            ->setParameter('type', $type);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs MaitriseNiveau partagent le même niveau [" . $type . "/" . $niveau . "]", 0, $e);
        }
        return $result;
    }

    /** FACADE  *******************************************************************************************************/

    /** @return NiveauMaitrise[] */
    public function generateDictionnaire(string $type ): array
    {
        $niveaux = $this->getMaitrisesNiveaux($type);
        $dictionnaire = [];
        foreach ($niveaux as $niveau) {
            $dictionnaire[$niveau->getLibelle()] = $niveau;
        }

        return $dictionnaire;
    }


}
