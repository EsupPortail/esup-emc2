<?php

namespace Carriere\Service\Niveau;

use Carriere\Entity\Db\Niveau;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class NiveauService
{
    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(Niveau $niveau): Niveau
    {
        $this->getObjectManager()->persist($niveau);
        $this->getObjectManager()->flush($niveau);
        return $niveau;
    }

    public function update(Niveau $niveau): Niveau
    {
        $this->getObjectManager()->flush($niveau);
        return $niveau;
    }

    public function historise(Niveau $niveau): Niveau
    {
        $niveau->historiser();
        $this->getObjectManager()->flush($niveau);
        return $niveau;
    }

    public function restore(Niveau $niveau): Niveau
    {
        $niveau->dehistoriser();
        $this->getObjectManager()->flush($niveau);
        return $niveau;
    }

    public function delete(Niveau $niveau): Niveau
    {
        $this->getObjectManager()->remove($niveau);
        $this->getObjectManager()->flush($niveau);
        return $niveau;
    }

    /** REQUETES **************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(Niveau::class)->createQueryBuilder('niveau');
        return $qb;
    }

    static public function decorateWithNiveau(QueryBuilder $qb, string $queryName, string $fieldName = 'niveaux'): QueryBuilder
    {
        $qb = $qb->addSelect($fieldName)->leftJoin($queryName . '.' . $fieldName, $fieldName)
            ->addSelect($fieldName . 'bas')->leftJoin($fieldName . '.borneInferieure', $fieldName . 'bas')
            ->addSelect($fieldName . 'haut')->leftJoin($fieldName . '.borneSuperieure', $fieldName . 'haut')
            ->addSelect($fieldName . 'rec')->leftJoin($fieldName . '.valeurRecommandee', $fieldName . 'rec');

        return $qb;
    }

    /** @return Niveau[] */
    public function getNiveaux(string $champs = 'niveau', string $ordre = 'ASC', bool $avecHisto = false): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('niveau.' . $champs, $ordre);

        if ($avecHisto === false) {
            $qb = $qb->andWhere('niveau.histoDestruction IS NULL');
        }
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getNiveauxAsOptions(string $champs = 'niveau', string $ordre = 'ASC', bool $avecHisto = false): array
    {
        $niveaux = $this->getNiveaux($champs, $ordre, $avecHisto);
        $options = [];
        foreach ($niveaux as $niveau) {
            $options[$niveau->getId()] = "" . $niveau->getEtiquette() . " - " . $niveau->getLibelle();
        }
        return $options;
    }

    public function getNiveau(?int $id): ?Niveau
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('niveau.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".Niveau::class."] partagent le même id [" . $id . "]", 0, $e);
        }
        return $result;
    }

    public function getRequestedNiveau(AbstractActionController $controller, string $param = 'niveau'): ?Niveau
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getNiveau($id);
        return $result;
    }

    public function getNiveauByEtiquette($etiquette): ?Niveau
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('niveau.etiquette = :etiquette')
            ->setParameter('etiquette', $etiquette);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".Niveau::class."] partagent la même etiquette [" . $etiquette . "]", 0, $e);
        }
        return $result;
    }

    /** @return Niveau[] */
    public function generateDictionnaire(string $disciminant = 'niveau'): array
    {
        $dictionnaire = [];
        $niveaux = $this->getNiveaux();
        foreach ($niveaux as $niveau) {
            $tabId = match ($disciminant) {
                'niveau' => $niveau->getNiveau(),
                'etiquette' => $niveau->getEtiquette(),
            };
            $dictionnaire[$tabId] = $niveau;
        }
        return $dictionnaire;
    }
}

