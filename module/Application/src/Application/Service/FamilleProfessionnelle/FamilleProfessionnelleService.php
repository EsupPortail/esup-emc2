<?php

namespace Application\Service\FamilleProfessionnelle;

use Application\Entity\Db\FamilleProfessionnelle;
use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class FamilleProfessionnelleService {
//    use UserServiceAwareTrait;
//    use EntityManagerAwareTrait;
    use GestionEntiteHistorisationTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param FamilleProfessionnelle $famille
     * @return FamilleProfessionnelle
     */
    public function create(FamilleProfessionnelle $famille)
    {
        $this->createFromTrait($famille);
        return $famille;
    }

    /**
     * @param FamilleProfessionnelle $famille
     * @return FamilleProfessionnelle
     */
    public function update(FamilleProfessionnelle $famille)
    {
        $this->updateFromTrait($famille);
        return $famille;
    }

    /**
     * @param FamilleProfessionnelle $famille
     * @return FamilleProfessionnelle
     */
    public function historise(FamilleProfessionnelle $famille)
    {
        $this->historiserFromTrait($famille);
        return $famille;
    }

    /**
     * @param FamilleProfessionnelle $famille
     * @return FamilleProfessionnelle
     */
    public function restore(FamilleProfessionnelle $famille)
    {
        $this->restoreFromTrait($famille);
        return $famille;
    }

    /**
     * @param FamilleProfessionnelle $famille
     * @return FamilleProfessionnelle
     */
    public function delete(FamilleProfessionnelle $famille)
    {
        $this->deleteFromTrait($famille);
        return $famille;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(FamilleProfessionnelle::class)->createQueryBuilder('famille')
            ->addSelect('domaine')->leftJoin('famille.domaines', 'domaine')
            ->addSelect('metier')->leftJoin('domaine.metiers', 'metier')
        ;
        return $qb;
    }

    /**
     * @return FamilleProfessionnelle[]
     */
    public function getFamillesProfessionnelles()
    {
        $qb = $this->createQueryBuilder()
            ->addOrderBy('famille.libelle');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param bool $historiser
     * @return array
     */
    public function getFamillesProfessionnellesAsOptions(bool $historiser = false)
    {
        $familles = $this->getFamillesProfessionnelles();
        $options = [];
        foreach ($familles as $famille) {
            if ($historiser OR $famille->estNonHistorise())
                $options[$famille->getId()] = $famille->getLibelle();
        }
        return $options;
    }

    /**
     * @param integer $id
     * @return FamilleProfessionnelle
     */
    public function getFamilleProfessionnelle($id)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('famille.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs FamilleProfessionnelle partagent le même identifiant [".$id."]");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return FamilleProfessionnelle
     */
    public function getRequestedFamilleProfessionnelle($controller, $paramName = 'famille')
    {
        $id = $controller->params()->fromRoute($paramName);
        $famille = $this->getFamilleProfessionnelle($id);

        return $famille;
    }
}
