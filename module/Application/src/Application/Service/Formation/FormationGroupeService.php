<?php

namespace Application\Service\Formation;

use Application\Entity\Db\FormationGroupe;
use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class FormationGroupeService {
//    use EntityManagerAwareTrait;
//    use UserServiceAwareTrait;
//    use DateTimeAwareTrait;
    use GestionEntiteHistorisationTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param FormationGroupe $groupe
     * @return FormationGroupe
     */
    public function create(FormationGroupe $groupe)
    {
        $this->createFromTrait($groupe);
        return $groupe;
    }

    /**
     * @param FormationGroupe $groupe
     * @return FormationGroupe
     */
    public function update(FormationGroupe $groupe)
    {
        $this->updateFromTrait($groupe);
        return $groupe;
    }

    /**
     * @param FormationGroupe $groupe
     * @return FormationGroupe
     */
    public function historise(FormationGroupe $groupe)
    {
        $this->historiserFromTrait($groupe);
        return $groupe;
    }

    /**
     * @param FormationGroupe $groupe
     * @return FormationGroupe
     */
    public function restore(FormationGroupe $groupe)
    {
        $this->restoreFromTrait($groupe);
        return $groupe;
    }

    /**
     * @param FormationGroupe $groupe
     * @return FormationGroupe
     */
    public function delete(FormationGroupe $groupe)
    {
        $this->deleteFromTrait($groupe);
        return $groupe;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(FormationGroupe::class)->createQueryBuilder('groupe')
             ->addSelect('formation')->leftJoin('groupe.formations', 'formation')
            ;
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return FormationGroupe[]
     */
    public function getFormationsGroupes($champ = 'ordre', $ordre='ASC')
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('groupe.' . $champ, $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @return array
     */
    public function getFormationsGroupesAsOption()
    {
        $groupes = $this->getFormationsGroupes();
        $array = [];
        foreach ($groupes as $groupe) {
            $array[$groupe->getId()] = $groupe->getLibelle();
        }
        return $array;
    }

    /**
     * @param integer $id
     * @return FormationGroupe
     */
    public function getFormationGroupe($id)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('groupe.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs FormationGroupe paratagent le même id [".$id."]");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return FormationGroupe
     */
    public function getRequestedFormationGroupe($controller, $param = 'formation-groupe')
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getFormationGroupe($id);
        return $result;
    }
}