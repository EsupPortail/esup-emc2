<?php

namespace Formation\Service\HasFormationCollection;

use DateTime;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Formation\Entity\Db\FormationElement;
use Formation\Entity\Db\Interfaces\HasFormationCollectionInterface;
use Formation\Service\Formation\FormationServiceAwareTrait;
use Formation\Service\FormationElement\FormationElementServiceAwareTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class HasFormationCollectionService
{
    use FormationServiceAwareTrait;
    use FormationElementServiceAwareTrait;
    use ProvidesObjectManager;
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

        $this->getObjectManager()->flush($object);
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