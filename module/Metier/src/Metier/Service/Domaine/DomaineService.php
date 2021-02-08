<?php

namespace Metier\Service\Domaine;

use Metier\Entity\Db\Domaine;
use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class DomaineService {
    use GestionEntiteHistorisationTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Domaine $domaine
     * @return Domaine
     */
    public function create(Domaine $domaine) : Domaine
    {
        $this->createFromTrait($domaine);
        return $domaine;
    }

    /**
     * @param Domaine $domaine
     * @return Domaine
     */
    public function historise(Domaine $domaine) : Domaine
    {
        $this->historiserFromTrait($domaine);
        return $domaine;
    }

    /**
     * @param Domaine $domaine
     * @return Domaine
     */
    public function restore(Domaine $domaine) : Domaine
    {
        $this->restoreFromTrait($domaine);
        return $domaine;
    }

    /**
     * @param Domaine $domaine
     * @return Domaine
     */
    public function update(Domaine $domaine) : Domaine
    {
        $this->updateFromTrait($domaine);
        return $domaine;
    }

    /**
     * @param Domaine $domaine
     * @return Domaine
     */
    public function delete(Domaine $domaine) : Domaine
    {
        $this->deleteFromTrait($domaine);
        return $domaine;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(Domaine::class)->createQueryBuilder('domaine')
            ->addSelect('famille')->leftJoin('domaine.famille', 'famille')
            ->addSelect('metier')->leftJoin('domaine.metiers', 'metier')
        ;
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return Domaine[]
     */
    public function getDomaines(string $champ = 'libelle', string $ordre = 'ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('domaine.' . $champ, $ordre)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param bool $historiser
     * @return array
     */
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

    /**
     * @param integer $id
     * @return Domaine|null
     */
    public function getDomaine(int $id) : ?Domaine
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

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return Domaine|null
     */
    public function getRequestedDomaine(AbstractActionController $controller, string $paramName = 'domaine') : ?Domaine
    {
        $id = $controller->params()->fromRoute($paramName);
        $domaine = $this->getDomaine($id);

        return $domaine;
    }

}
