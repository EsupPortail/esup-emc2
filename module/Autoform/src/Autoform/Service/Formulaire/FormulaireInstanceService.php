<?php

namespace Autoform\Service\Formulaire;

use Application\Service\GestionEntiteHistorisationTrait;
use Autoform\Entity\Db\FormulaireInstance;
use Autoform\Entity\Db\FormulaireReponse;
use Doctrine\ORM\NonUniqueResultException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Entity\DateTimeAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class FormulaireInstanceService {
    use DateTimeAwareTrait;
    use EntityManagerAwareTrait;
    use FormulaireServiceAwareTrait;
    use FormulaireReponseServiceAwareTrait;
    use GestionEntiteHistorisationTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param FormulaireInstance $instance
     * @return FormulaireInstance
     */
    public function create(FormulaireInstance $instance) : FormulaireInstance
    {
        $this->createFromTrait($instance);
        return $instance;
    }

    /**
     * @param FormulaireInstance $instance
     * @return FormulaireInstance
     */
    public function update(FormulaireInstance $instance) : FormulaireInstance
    {
        $this->updateFromTrait($instance);
        return $instance;
    }

    /**
     * @param FormulaireInstance $instance
     * @return FormulaireInstance
     */
    public function historise(FormulaireInstance $instance) : FormulaireInstance
    {
        $this->historiserFromTrait($instance);
        return $instance;
    }

    /**
     * @param FormulaireInstance $instance
     * @return FormulaireInstance
     */
    public function restore(FormulaireInstance $instance) : FormulaireInstance
    {
        $this->restoreFromTrait($instance);
        return $instance;
    }

    /**
     * @param FormulaireInstance $instance
     * @return FormulaireInstance
     */
    public function delete(FormulaireInstance $instance) : FormulaireInstance
    {
        $this->deleteFromTrait($instance);
        return $instance;
    }

    /** REQUETAGES ****************************************************************************************************/

    /**
     * @param int|null $id
     * @return FormulaireInstance|null
     */
    public function getFormulaireInstance(?int $id) : ?FormulaireInstance
    {
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
     * @param AbstractActionController $controller
     * @param string $label
     * @return FormulaireInstance|null
     */
    public function getRequestedFormulaireInstance(AbstractActionController $controller, string $label) : ?FormulaireInstance
    {
        $id = $controller->params()->fromRoute($label);
        $instance = $this->getFormulaireInstance($id);
        return $instance;
    }

    /** CREATION ET DUPLICATION ... ***********************************************************************************/

    /**
     * @param FormulaireInstance $reference
     * @param FormulaireInstance $instance
     */
    public function duplicate(FormulaireInstance $reference, FormulaireInstance $instance)
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

    /**
     * @param FormulaireInstance $instance1
     * @param FormulaireInstance $instance2
     * @param $champId1
     * @param $champId2
     */
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

    /**
     * @param string $code
     * @return FormulaireInstance
     */
    public function createInstance($code)
    {
        $instance = new FormulaireInstance();
        $formulaire = $this->getFormulaireService()->getFormulaireByCode($code);
        $instance->setFormulaire($formulaire);
        $this->create($instance);
        return $instance;
    }
}
