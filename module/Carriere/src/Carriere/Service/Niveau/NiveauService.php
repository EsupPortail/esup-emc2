<?php

namespace Carriere\Service\Niveau;

use Carriere\Entity\Db\Niveau;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;

class NiveauService {
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(Niveau $niveau) : Niveau
    {
        try {
            $this->getEntityManager()->persist($niveau);
            $this->getEntityManager()->flush($niveau);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $niveau;
    }

    public function update(Niveau $niveau) : Niveau
    {
        try {
            $this->getEntityManager()->flush($niveau);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $niveau;
    }

    public function historise(Niveau $niveau) : Niveau
    {
        try {
            $niveau->historiser();
            $this->getEntityManager()->flush($niveau);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $niveau;
    }

    public function restore(Niveau $niveau) : Niveau
    {
        try {
            $niveau->dehistoriser();
            $this->getEntityManager()->flush($niveau);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $niveau;
    }

    public function delete(Niveau $niveau) : Niveau
    {
        try {
            $this->getEntityManager()->remove($niveau);
            $this->getEntityManager()->flush($niveau);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $niveau;
    }

    /** REQUETES **************************************************************************************/

    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(Niveau::class)->createQueryBuilder('niveau');
        return $qb;
    }

    static public function decorateWithNiveau(QueryBuilder $qb, string $queryName, string $fieldName = 'niveaux') : QueryBuilder
    {
        $qb = $qb->addSelect($fieldName)->leftJoin($queryName.'.'.$fieldName, $fieldName)
            ->addSelect($fieldName.'bas')->leftJoin($fieldName.'.borneInferieure', $fieldName.'bas')
            ->addSelect($fieldName.'haut')->leftJoin($fieldName.'.borneSuperieure', $fieldName.'haut')
            ->addSelect($fieldName.'rec')->leftJoin($fieldName.'.valeurRecommandee', $fieldName.'rec')
        ;

        return $qb;
    }

    /** @return Niveau[] */
    public function getNiveaux(string $champs = 'niveau', string $ordre = 'ASC', bool $avecHisto = false) : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('niveau.' . $champs, $ordre);

        if ($avecHisto === false) {
            $qb = $qb->andWhere('niveau.histoDestruction IS NULL');
        }
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getNiveauxAsOptions(string $champs = 'niveau', string $ordre = 'ASC', bool $avecHisto = false) : array
    {
        $niveaux = $this->getNiveaux($champs, $ordre, $avecHisto);
        $options = [];
        foreach ($niveaux as $niveau) {
            $options[$niveau->getId()] = "" . $niveau->getEtiquette() . " - " . $niveau->getLibelle();
        }
        return $options;
    }

    public function getNiveau(?int $id) : ?Niveau
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('niveau.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Niveau partagent le même id [".$id."]",0,$e);
        }
        return $result;
    }

    public function getRequestedNiveau(AbstractActionController $controller, string $param='niveau') : ?Niveau
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getNiveau($id);
        return $result;
    }
}

