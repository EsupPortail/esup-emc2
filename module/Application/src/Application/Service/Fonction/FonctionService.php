<?php

namespace Application\Service\Fonction;

use Application\Entity\Db\Fonction;
use Application\Entity\Db\FonctionActivite;
use Application\Entity\Db\FonctionDestination;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;

class FonctionService {
    use EntityManagerAwareTrait;

    /** Destinations **************************************************************************************************/

    /**
     * @param FonctionDestination $destination
     * @return FonctionDestination
     */
    public function createDestination(FonctionDestination $destination) : FonctionDestination
    {
        try {
            $this->getEntityManager()->persist($destination);
            $this->getEntityManager()->flush($destination);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $destination;
    }

    /**
     * @param FonctionDestination $destination
     * @return FonctionDestination
     */
    public function updateDestination(FonctionDestination $destination) : FonctionDestination
    {
        try {
            $this->getEntityManager()->flush($destination);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $destination;
    }

    /**
     * @param FonctionDestination $destination
     * @return FonctionDestination
     */
    public function historiseDestination(FonctionDestination $destination) : FonctionDestination
    {
        try {
            $destination->historiser();
            $this->getEntityManager()->flush($destination);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $destination;
    }

    /**
     * @param FonctionDestination $destination
     * @return FonctionDestination
     */
    public function restoreDestination(FonctionDestination $destination) : FonctionDestination
    {
        try {
            $destination->dehistoriser();
            $this->getEntityManager()->flush($destination);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $destination;
    }

    /**
     * @param FonctionDestination $destination
     * @return FonctionDestination
     */
    public function deleteDestination(FonctionDestination $destination) : FonctionDestination
    {
        try {
            $this->getEntityManager()->remove($destination);
            $this->getEntityManager()->flush($destination);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $destination;
    }

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilderDestination() {
        $qb = $this->getEntityManager()->getRepository(FonctionDestination::class)->createQueryBuilder('destination')
            ->addSelect('activite')->leftJoin('destination.activites','activite')
        ;
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return FonctionDestination[]
     */
    public function getDestinations($champ = 'code', $ordre = 'ASC')
    {
        $qb = $this->createQueryBuilderDestination()
            ->orderBy('destination.' . $champ, $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return FonctionDestination
     */
    public function getDestination(int $id)
    {
        $qb = $this->createQueryBuilderDestination()
            ->andWhere('destination.id = :id')
            ->setParameter('id' , $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs FonctionDestination partagent le même id [".$id."]");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return FonctionDestination
     */
    public function getResquestedDestrination(AbstractActionController $controller, string $param='destination')
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getDestination($id);
        return $result;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return array
     */
    public function getDestinationsAsOptions($champ = 'code', $ordre = 'ASC')
    {
        $destinations = $this->getDestinations($champ, $ordre);
        $options = [];
        foreach ($destinations as $destination) {
            $options[$destination->getId()] = $destination->getCode() . " - " . $destination->getLibelle();
        }
        return $options;
    }

    /** Activite ******************************************************************************************************/

    /**
     * @param FonctionActivite $activite
     * @return FonctionActivite
     */
    public function createActivite(FonctionActivite $activite) : FonctionActivite
    {
        try {
            $this->getEntityManager()->persist($activite);
            $this->getEntityManager()->flush($activite);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $activite;
    }

    /**
     * @param FonctionActivite $activite
     * @return FonctionActivite
     */
    public function updateActivite(FonctionActivite $activite) : FonctionActivite
    {
        try {
            $this->getEntityManager()->flush($activite);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $activite;
    }

    /**
     * @param FonctionActivite $activite
     * @return FonctionActivite
     */
    public function historiseActivite(FonctionActivite $activite) : FonctionActivite
    {
        try {
            $activite->historiser();
            $this->getEntityManager()->flush($activite);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $activite;
    }

    /**
     * @param FonctionActivite $activite
     * @return FonctionActivite
     */
    public function restoreActivite(FonctionActivite $activite) : FonctionActivite
    {
        try {
            $activite->dehistoriser();
            $this->getEntityManager()->flush($activite);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $activite;
    }

    /**
     * @param FonctionActivite $activite
     * @return FonctionActivite
     */
    public function deleteActivite(FonctionActivite $activite) : FonctionActivite
    {
        try {
            $this->getEntityManager()->remove($activite);
            $this->getEntityManager()->flush($activite);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $activite;
    }

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilderActivite() {
        $qb = $this->getEntityManager()->getRepository(FonctionActivite::class)->createQueryBuilder('activite')
            ->addSelect('destination')->join('activite.destination','destination')
        ;
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return FonctionActivite[]
     */
    public function getActivites($champ = 'code', $ordre = 'ASC')
    {
        $qb = $this->createQueryBuilderActivite()
            ->orderBy('activite.' . $champ, $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return FonctionActivite
     */
    public function getActivite(int $id)
    {
        $qb = $this->createQueryBuilderActivite()
            ->andWhere('activite.id = :id')
            ->setParameter('id' , $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs FonctionActivite partagent le même id [".$id."]");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return FonctionActivite
     */
    public function getResquestedActivite(AbstractActionController $controller, $param='activite')
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getActivite($id);
        return $result;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return array
     */
    public function getActivitesAsOptions($champ = 'code', $ordre = 'ASC')
    {
        $activites = $this->getActivites($champ, $ordre);
        $options = [];
        foreach ($activites as $activite) {
            $options[$activite->getId()] = $activite->getCode() . " - " . $activite->getLibelle();
        }
        return $options;
    }

    /** Site **********************************************************************************************************/

    /**
     * @return Fonction[]
     */
    public function getFonctions()
    {
        $qb = $this->getEntityManager()->getRepository(Fonction::class)->createQueryBuilder('fonction')
            ->addSelect('libelle')->leftJoin('fonction.libelles','libelle')
            ->orderBy('fonction.parent, fonction.id')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @return array
     */
    public function getFonctionsAsOptions()
    {
        $fonctions = $this->getFonctions();
        $options = [];
        foreach ($fonctions as $fonction) {
            $array = [];
            foreach ($fonction->getLibelles() as $libelle) $array[] = $libelle->getLibelle();
            $options[$fonction->getId()] = implode("/", $array);
        }
        return $options;
    }

    /**
     * @param string $id
     * @return Fonction
     */
    public function getFonction(string $id)
    {
        $qb = $this->getEntityManager()->getRepository(Fonction::class)->createQueryBuilder('fonction')
            ->addSelect('libelle')->leftJoin('fonction.libelles','libelle')
            ->andWhere('fonction.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException('Plusieurs Fonction partagent le même id ['.$id.']', $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return Fonction
     */
    public function getRequestedFonction(AbstractActionController $controller, string $paramName)
    {
        $id = $controller->params()->fromRoute($paramName);
        $site = $this->getFonction($id);
        return $site;
    }

    /**
     * @param Fonction $fonction
     * @return Fonction
     */
    public function update(Fonction $fonction)
    {
        try {
            $this->getEntityManager()->flush($fonction);
        } catch (ORMException $e) {
            throw new RuntimeException('Un problème est surevenue lors de l\'enregistrement en base.', $e);
        }
        return $fonction;

    }
}
