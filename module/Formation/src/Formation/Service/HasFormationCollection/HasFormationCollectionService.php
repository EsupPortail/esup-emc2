<?php

namespace Formation\Service\HasFormationCollection;

use DateTime;
use Doctrine\ORM\Exception\ORMException;
use Formation\Entity\Db\FormationElement;
use Formation\Entity\Db\Interfaces\HasFormationCollectionInterface;
use Formation\Service\Formation\FormationServiceAwareTrait;
use Formation\Service\FormationElement\FormationElementServiceAwareTrait;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class HasFormationCollectionService
{
    use FormationServiceAwareTrait;
    use FormationElementServiceAwareTrait;
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /**
     * @param HasFormationCollectionInterface $object
     * @return HasFormationCollectionInterface
     */
    public function updateObject(HasFormationCollectionInterface $object): HasFormationCollectionInterface
    {
        if ($object instanceof HistoriqueAwareInterface) {
            $user = $this->getUserService()->getConnectedUser();
            if ($user === null) $user = $this->getUserService()->find(0);
            $date = new DateTime();
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
    public function updateFormations(HasFormationCollectionInterface $object, $data): HasFormationCollectionInterface
    {
        $formationIds = [];
        if (isset($data['formations'])) $formationIds = $data['formations'];

        //Suppression des formations plus présentes
        /** @var FormationElement $formationElement */
        foreach ($object->getFormationCollection() as $formationElement) {
            if (!in_array($formationElement->getFormation()->getId(), $formationIds)) {
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
    public function addFormation(HasFormationCollectionInterface $object, FormationElement $formationElement): HasFormationCollectionInterface
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
    public function deleteFormation(HasFormationCollectionInterface $object, FormationElement $formationElement): HasFormationCollectionInterface
    {
        $object->getFormationCollection()->removeElement($formationElement);
        $this->updateObject($object);
        return $object;
    }

    /**
     * @param HasFormationCollectionInterface $object
     * @return HasFormationCollectionInterface
     */
    public function clearFormation(HasFormationCollectionInterface $object): HasFormationCollectionInterface
    {
        $object->getFormationCollection()->clear();
        $this->updateObject($object);
        return $object;
    }

}