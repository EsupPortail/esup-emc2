<?php

namespace Application\Service\Domaine;

use Application\Entity\Db\Domaine;
use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class DomaineService {
//    use EntityManagerAwareTrait;
//    use UserServiceAwareTrait;
    use GestionEntiteHistorisationTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Domaine $domaine
     * @return Domaine
     */
    public function create(Domaine $domaine)
    {
        $this->createFromTrait($domaine);
        return $domaine;
    }

    /**
     * @param Domaine $domaine
     * @return Domaine
     */
    public function historise(Domaine $domaine)
    {
        $this->historiserFromTrait($domaine);
        return $domaine;
    }

    /**
     * @param Domaine $domaine
     * @return Domaine
     */
    public function restore(Domaine $domaine)
    {
        $this->restoreFromTrait($domaine);
        return $domaine;
    }

    /**
     * @param Domaine $domaine
     * @return Domaine
     */
    public function update(Domaine $domaine)
    {
        $this->updateFromTrait($domaine);
        return $domaine;
    }

    /**
     * @param Domaine $domaine
     * @return Domaine
     */
    public function delete(Domaine $domaine)
    {
        $this->deleteFromTrait($domaine);
        return $domaine;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
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
    public function getDomaines($champ = 'libelle', $ordre = 'ASC')
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
    public function getDomainesAsOptions(bool $historiser = false)
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
     * @return Domaine
     */
    public function getDomaine($id)
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
     * @return Domaine
     */
    public function getRequestedDomaine($controller, $paramName = 'domaine')
    {
        $id = $controller->params()->fromRoute($paramName);
        $domaine = $this->getDomaine($id);

        return $domaine;
    }

}
