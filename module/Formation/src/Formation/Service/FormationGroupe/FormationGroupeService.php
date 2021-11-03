<?php

namespace Formation\Service\FormationGroupe;

use Application\Service\GestionEntiteHistorisationTrait;
use Application\Service\RendererAwareTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Formation\Entity\Db\FormationGroupe;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class FormationGroupeService
{
    use GestionEntiteHistorisationTrait;
    use RendererAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param FormationGroupe $groupe
     * @return FormationGroupe
     */
    public function create(FormationGroupe $groupe) : FormationGroupe
    {
        $this->createFromTrait($groupe);
        return $groupe;
    }

    /**
     * @param FormationGroupe $groupe
     * @return FormationGroupe
     */
    public function update(FormationGroupe $groupe) : FormationGroupe
    {
        $this->updateFromTrait($groupe);
        return $groupe;
    }

    /**
     * @param FormationGroupe $groupe
     * @return FormationGroupe
     */
    public function historise(FormationGroupe $groupe) : FormationGroupe
    {
        $this->historiserFromTrait($groupe);
        return $groupe;
    }

    /**
     * @param FormationGroupe $groupe
     * @return FormationGroupe
     */
    public function restore(FormationGroupe $groupe) : FormationGroupe
    {
        $this->restoreFromTrait($groupe);
        return $groupe;
    }

    /**
     * @param FormationGroupe $groupe
     * @return FormationGroupe
     */
    public function delete(FormationGroupe $groupe) : FormationGroupe
    {
        $this->deleteFromTrait($groupe);
        return $groupe;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(FormationGroupe::class)->createQueryBuilder('groupe')
            ->addSelect('formation')->leftJoin('groupe.formations', 'formation');
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return FormationGroupe[]
     */
    public function getFormationsGroupes(string $champ = 'ordre', string $ordre = 'ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('groupe.' . $champ, $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param FormationGroupe $groupe
     * @return array
     */
    public function optionify(FormationGroupe $groupe) : array
    {
        $this_option = [
            'value' => $groupe->getId(),
            'label' => $groupe->getLibelle(),
        ];
        return $this_option;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return array
     */
    public function getFormationsGroupesAsOption(string $champ = 'libelle', string $ordre = 'ASC') : array
    {
        $groupes = $this->getFormationsGroupes($champ, $ordre);
        $array = [];
        foreach ($groupes as $groupe) {
            $option = $this->optionify($groupe);
            $array[$groupe->getId()] = $option;
        }
        return $array;
    }

    /**
     * @param integer $id
     * @return FormationGroupe|null
     */
    public function getFormationGroupe(int $id) : ?FormationGroupe
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('groupe.id = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs FormationGroupe paratagent le même id [" . $id . "]");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return FormationGroupe
     */
    public function getRequestedFormationGroupe(AbstractActionController $controller, string  $param = 'formation-groupe') : ?FormationGroupe
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getFormationGroupe($id);
        return $result;
    }

    /**
     * @param string|null $libelle
     * @return FormationGroupe|null
     */
    public function getFormationGroupeByLibelle(?string $libelle) : ?FormationGroupe
    {
        if ($libelle === null) return null;

        $qb = $this->createQueryBuilder()
            ->andWhere('groupe.libelle = :libelle')
            ->setParameter('libelle', $libelle)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs FormationGroupe partagent le même libellé [".$libelle."].");
        }
        return $result;
    }
}