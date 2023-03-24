<?php

namespace Element\Service\Niveau;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Element\Entity\Db\Niveau;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;

class NiveauService {
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(Niveau $maitrise) : Niveau
    {
        try {
            $this->getEntityManager()->persist($maitrise);
            $this->getEntityManager()->flush($maitrise);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $maitrise;
    }

    public function update(Niveau $maitrise) : Niveau
    {
        try {
            $this->getEntityManager()->flush($maitrise);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $maitrise;
    }

    public function historise(Niveau $maitrise) : Niveau
    {
        try {
            $maitrise->historiser();
            $this->getEntityManager()->flush($maitrise);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $maitrise;
    }

    public function restore(Niveau $maitrise) : Niveau
    {
        try {
            $maitrise->dehistoriser();
            $this->getEntityManager()->flush($maitrise);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $maitrise;
    }

    public function delete(Niveau $maitrise) : Niveau
    {
        try {
            $this->getEntityManager()->remove($maitrise);
            $this->getEntityManager()->flush($maitrise);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $maitrise;
    }

    /** QUERY *********************************************************************************************************/

    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(Niveau::class)->createQueryBuilder('maitrise');
        return $qb;
    }

    /** @return Niveau[] */
    public function getMaitrisesNiveaux(string $type = "", string $champ = 'niveau', string $ordre = 'ASC', bool $nonHistorise = false) : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('maitrise.' . $champ, $ordre);

        if ($type !== null AND $type !== "") {
            $qb = $qb->andWhere('maitrise.type = :type')
                ->setParameter('type', $type);
        }

        if ($nonHistorise !== true) $qb = $qb->andWhere('maitrise.histoDestruction IS NULL');
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getMaitrisesNiveauxAsOptions(string $type="", string $champ = 'niveau', string $ordre = 'ASC', bool $nonHistorise = false) : array
    {
        $maitrises = $this->getMaitrisesNiveaux($type, $champ, $ordre, $nonHistorise);
        $options = [];
        foreach ($maitrises as $maitrise) {
            $options[$maitrise->getId()] = $maitrise->getLibelle();
        }
        return $options;
    }

    public function getMaitriseNiveau(?int $id) : ?Niveau
    {
        if ($id === null) return null;
        $qb = $this->createQueryBuilder()
            ->andWhere('maitrise.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs MaitriseNiveau partagent le même id [".$id."]",0,$e);
        }
        return $result;
    }

    public function getRequestedMaitriseNiveau(AbstractActionController $controller, string $param = 'maitrise') : ?Niveau
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getMaitriseNiveau($id);
        return $result;
    }

    public function getMaitriseNiveauByNiveau(string $type, int $niveau) : ?Niveau
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('maitrise.niveau = :niveau')
            ->setParameter('niveau', $niveau)
            ->andWhere('maitrise.type = :type')
            ->setParameter('type', $type)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs MaitriseNiveau partagent le même niveau [".$type."/".$niveau."]",0,$e);
        }
        return $result;
    }
}