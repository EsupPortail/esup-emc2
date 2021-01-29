<?php

namespace Formation\Service\HasFormationCollection;

use Formation\Entity\Db\FormationElement;
use Formation\Entity\Db\Interfaces\HasFormationCollectionInterface;
use Formation\Service\Formation\FormationServiceAwareTrait;
use Formation\Service\FormationElement\FormationElementServiceAwareTrait;
use Doctrine\ORM\ORMException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Entity\DateTimeAwareTrait;
use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class HasFormationCollectionService
{
    use FormationServiceAwareTrait;
    use FormationElementServiceAwareTrait;
    use EntityManagerAwareTrait;
    use DateTimeAwareTrait;
    use UserServiceAwareTrait;

    /**
     * @param HasFormationCollectionInterface $object
     * @return HasFormationCollectionInterface
     */
    public function updateObject(HasFormationCollectionInterface $object)
    {
        if ($object instanceof HistoriqueAwareInterface) {
            $user = $this->getUserService()->getConnectedUser();
            if ($user === null) $user = $this->getUserService()->getUtilisateur(0);
            $date = $this->getDateTime();
            $object->setHistoModification($date);
            $object->setHistoModificateur($user);
        }

        try {
            $this->getEntityManager()->flush($object);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $object;
    }

    /**
     * @param HasFormationCollectionInterface $object
     * @param array $data
     * @return HasFormationCollectionInterface
     */
    public function updateFormations(HasFormationCollectionInterface $object, $data)
    {
        $formationIds = [];
        if (isset($data['formations'])) $formationIds = $data['formations'];

        //Suppression des formations plus présentes
        /** @var FormationElement $formationElement */
        foreach ($object->getFormationCollection() as $formationElement) {
            if (array_search($formationElement->getFormation()->getId(), $formationIds) === false) {
                $this->getFormationElementService()->historise($formationElement);
            }
        }
        //Ajout des formations plus présentes
        foreach ($formationIds as $formationId) {
            $formation = $this->getFormationService()->getFormation($formationId);
            if ($formation !== null and !$object->hasFormation($formation)) {
                $formationElement = new FormationElement();
                $formationElement->setFormation($formation);
                //TODO ajouter les autres elements : commentaires / validations tout çà
                $this->getFormationElementService()->create($formationElement);
                $object->getFormationCollection()->add($formationElement);
                $this->updateObject($object);
            }
        }
        return $object;
    }

    /**
     * @param HasFormationCollectionInterface $object
     * @param FormationElement $formationElement
     * @return HasFormationCollectionInterface
     */
    public function addFormation(HasFormationCollectionInterface $object, FormationElement $formationElement)
    {
        $this->getFormationElementService()->create($formationElement);
        $object->getFormationCollection()->add($formationElement);
        $this->updateObject($object);
        return $object;
    }

    /**
     * @param HasFormationCollectionInterface $object
     * @param FormationElement $formationElement
     * @return HasFormationCollectionInterface
     */
    public function deleteFormation(HasFormationCollectionInterface $object, FormationElement $formationElement)
    {
        $object->getFormationCollection()->removeElement($formationElement);
        $this->updateObject($object);
        return $object;
    }

    /**
     * @param HasFormationCollectionInterface $object
     * @return HasFormationCollectionInterface
     */
    public function clearFormation(HasFormationCollectionInterface $object)
    {
        $object->getFormationCollection()->clear();
        $this->updateObject($object);
        return $object;
    }

}