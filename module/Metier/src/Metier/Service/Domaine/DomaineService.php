<?php

namespace Metier\Service\Domaine;

use Doctrine\ORM\Exception\ORMException;
use Metier\Entity\Db\Domaine;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;

class DomaineService {
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(Domaine $domaine) : Domaine
    {
        try {
            $this->getEntityManager()->persist($domaine);
            $this->getEntityManager()->flush($domaine);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $domaine;
    }

    public function update(Domaine $domaine) : Domaine
    {
        try {
            $this->getEntityManager()->flush($domaine);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $domaine;
    }

    public function historise(Domaine $domaine) : Domaine
    {
        try {
            $domaine->historiser();
            $this->getEntityManager()->flush($domaine);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $domaine;
    }

    public function restore(Domaine $domaine) : Domaine
    {
        try {
            $domaine->dehistoriser();
            $this->getEntityManager()->flush($domaine);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $domaine;
    }

    public function delete(Domaine $domaine) : Domaine
    {
        try {
            $this->getEntityManager()->remove($domaine);
            $this->getEntityManager()->flush($domaine);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $domaine;
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(Domaine::class)->createQueryBuilder('domaine')
            ->addSelect('famille')->leftJoin('domaine.familles', 'famille')
            ->addSelect('metier')->leftJoin('domaine.metiers', 'metier')
        ;
        return $qb;
    }

    /** @return Domaine[] */
    public function getDomaines(string $champ = 'libelle', string $ordre = 'ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('domaine.' . $champ, $ordre)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return Domaine[] */
    public function getDomainesAsOptions(bool $historiser = false) : array
    {
        $domaines = $this->getDomaines();
        $options = [];
        foreach ($domaines as $domaine) {
            if ($historiser OR $domaine->estNonHistorise())
                $options[$domaine->getId()] = $domaine->getLibelle();
        }
        return $options;
    }

    public function getDomaine(?int $id) : ?Domaine
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('domaine.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Domaine partagent le même identifiant [".$id."]");
        }
        return $result;
    }

    public function getRequestedDomaine(AbstractActionController $controller, string $paramName = 'domaine') : ?Domaine
    {
        $id = $controller->params()->fromRoute($paramName);
        $domaine = $this->getDomaine($id);

        return $domaine;
    }

}
