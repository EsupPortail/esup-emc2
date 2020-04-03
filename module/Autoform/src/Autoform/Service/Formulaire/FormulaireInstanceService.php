<?php

namespace Autoform\Service\Formulaire;

use Autoform\Entity\Db\FormulaireInstance;
use Autoform\Entity\Db\FormulaireReponse;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Entity\DateTimeAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class FormulaireInstanceService {
    use DateTimeAwareTrait;
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;
    use FormulaireReponseServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param FormulaireInstance $instance
     * @return FormulaireInstance
     */
    public function create($instance)
    {
        $date = $this->getDateTime();
        $user = $this->getUserService()->getConnectedUser();

        $instance->setHistoCreateur($user);
        $instance->setHistoCreation($date);
        $instance->setHistoModificateur($user);
        $instance->setHistoModification($date);

        try {
            $this->getEntityManager()->persist($instance);
            $this->getEntityManager()->flush($instance);
        } catch (ORMException $e) {
            throw new RuntimeException("Problème d'enregistrement en BD.", $e);
        }

        return $instance;
    }

    /**
     * @param FormulaireInstance $instance
     * @return FormulaireInstance
     */
    public function update($instance)
    {
        $date = $this->getDateTime();
        $user = $this->getUserService()->getConnectedUser();

        $instance->setHistoModificateur($user);
        $instance->setHistoModification($date);

        try {
            $this->getEntityManager()->flush($instance);
        } catch (ORMException $e) {
            throw new RuntimeException("Problème d'enregistrement en BD.", $e);
        }

        return $instance;
    }

    /**
     * @param FormulaireInstance $instance
     * @return FormulaireInstance
     */
    public function historise($instance)
    {
        $date = $this->getDateTime();
        $user = $this->getUserService()->getConnectedUser();

        $instance->setHistoDestructeur($user);
        $instance->setHistoDestruction($date);

        try {
            $this->getEntityManager()->flush($instance);
        } catch (ORMException $e) {
            throw new RuntimeException("Problème d'enregistrement en BD.", $e);
        }

        return $instance;
    }

    /**
     * @param FormulaireInstance $instance
     * @return FormulaireInstance
     */
    public function restore($instance)
    {
        $instance->setHistoDestructeur(null);
        $instance->setHistoDestruction(null);

        try {
            $this->getEntityManager()->flush($instance);
        } catch (ORMException $e) {
            throw new RuntimeException("Problème d'enregistrement en BD.", $e);
        }

        return $instance;
    }

    /**
     * @param FormulaireInstance $instance
     * @return FormulaireInstance
     */
    public function delete($instance)
    {

        try {
            $this->getEntityManager()->remove($instance);
            $this->getEntityManager()->flush();
        } catch (ORMException $e) {
            throw new RuntimeException("Problème d'enregistrement en BD.", $e);
        }

        return $instance;
    }

    /** REQUETAGES ****************************************************************************************************/

    /**
     * @param AbstractActionController $controller
     * @param string $label
     * @return FormulaireInstance
     */
    public function getRequestedFormulaireInstance($controller, $label)
    {
        $id = $controller->params()->fromRoute($label);
        $instance = $this->getFormulaireInstance($id);
        return $instance;
    }

    /**
     * @param integer $id
     * @return FormulaireInstance
     */
    public function getFormulaireInstance($id)
    {
        if ($id === null) return null;

        $qb = $this->getEntityManager()->getRepository(FormulaireInstance::class)->createQueryBuilder('formulaire_instance')
            ->andWhere('formulaire_instance.id = :id')
            ->addSelect('formulaire')->join('formulaire_instance.formulaire', 'formulaire')
            ->addSelect('categorie')->join('formulaire.categories', 'categorie')
            ->addSelect('champ')->join('categorie.champs', 'champ')
            ->addSelect('reponse')->leftJoin('formulaire_instance.reponses', 'reponse')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs FormulaireInstance partagent le même identifiant [".$id."]", $e);
        }
        return $result;
    }

    /**
     * @param FormulaireInstance $reference
     * @param FormulaireInstance $instance
     */
    public function duplicate($reference, $instance)
    {
        foreach ($reference->getReponses() as $reponse) {
            $newReponse = new FormulaireReponse();
            $newReponse->setFormulaireInstance($instance);
            $newReponse->setChamp($reponse->getChamp());
            $newReponse->setReponse($reponse->getReponse());
            $this->getFormulaireReponseService()->create($newReponse);
            $instance->addReponse($reponse);
        }
        $this->update($instance);
    }

    public function recopie(FormulaireInstance $instance1, FormulaireInstance $instance2, $champId1, $champId2) {
        $reponses = $instance1->getReponses();
        foreach ($reponses as $reponse) {
            if ($reponse->getChamp()->getId() == $champId1) {
                $champ = $instance2->getChamp(intval($champId2));
                if ($champ !== null) {
                    $value = $reponse->getReponse();
                    $new = new FormulaireReponse();
                    $new->setFormulaireInstance($instance2);
                    $new->setChamp($champ);
                    $new->setReponse($value);
                    $this->getFormulaireReponseService()->create($new);
                }
            }
        }
    }

}
