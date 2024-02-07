<?php

namespace Formation\Service\FormationGroupe;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Formation\Entity\Db\Axe;
use Formation\Entity\Db\FormationGroupe;
use UnicaenApp\Exception\RuntimeException;
use Laminas\Mvc\Controller\AbstractActionController;

class FormationGroupeService
{
    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param FormationGroupe $groupe
     * @return FormationGroupe
     */
    public function create(FormationGroupe $groupe) : FormationGroupe
    {
        $this->getObjectManager()->persist($groupe);
        $this->getObjectManager()->flush($groupe);
        return $groupe;
    }

    /**
     * @param FormationGroupe $groupe
     * @return FormationGroupe
     */
    public function update(FormationGroupe $groupe) : FormationGroupe
    {
        $this->getObjectManager()->flush($groupe);
        return $groupe;
    }

    /**
     * @param FormationGroupe $groupe
     * @return FormationGroupe
     */
    public function historise(FormationGroupe $groupe) : FormationGroupe
    {
        $groupe->historiser();
        $this->getObjectManager()->flush($groupe);
        return $groupe;
    }

    /**
     * @param FormationGroupe $groupe
     * @return FormationGroupe
     */
    public function restore(FormationGroupe $groupe) : FormationGroupe
    {
        $groupe->dehistoriser();
        $this->getObjectManager()->flush($groupe);
        return $groupe;
    }

    /**
     * @param FormationGroupe $groupe
     * @return FormationGroupe
     */
    public function delete(FormationGroupe $groupe) : FormationGroupe
    {
        $this->getObjectManager()->remove($groupe);
        $this->getObjectManager()->flush($groupe);
        return $groupe;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(FormationGroupe::class)->createQueryBuilder('groupe')
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
     * @param int|null $id
     * @return FormationGroupe|null
     */
    public function getFormationGroupe(?int $id) : ?FormationGroupe
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('groupe.id = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs FormationGroupe paratagent le même id [" . $id . "]",0,$e);
        }
        return $result;
    }

    public function getRequestedFormationGroupe(AbstractActionController $controller, string  $param = 'formation-groupe') : ?FormationGroupe
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getFormationGroupe($id);
        return $result;
    }

    public function getFormationGroupeByLibelle(?string $libelle, ?Axe $axe = null) : ?FormationGroupe
    {
        if ($libelle === null) return null;

        $qb = $this->createQueryBuilder()
            ->andWhere('groupe.libelle = :libelle')
            ->setParameter('libelle', $libelle)
        ;
        if ($axe) $qb = $qb->andWhere('groupe.axe = :axe')->setParameter('axe', $axe);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs FormationGroupe partagent le même libellé [".$libelle."].",0, $e);
        }
        return $result;
    }

    public function getFormationGroupeBySource(string $source, $id) : ? FormationGroupe
    {
        $qb = $this->getObjectManager()->getRepository(FormationGroupe::class)->createQueryBuilder('groupe')
            ->andWhere('groupe.source = :source')->setParameter('source', $source)
            ->andWhere('groupe.idSource = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs FormationGroupe partagent la même source [".$source."|".$id."].",0, $e);
        }
        return $result;
    }

    /** Façade ********************************************************************************************/

    public function createFormationGroupe(string $libelle, ?Axe $axe): FormationGroupe
    {
        $theme = new FormationGroupe();
        $theme->setLibelle($libelle);
        $theme->setAxe($axe);
        $this->create($theme);
        return $theme;
    }

}