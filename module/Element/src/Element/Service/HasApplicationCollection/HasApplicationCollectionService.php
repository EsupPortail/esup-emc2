<?php

namespace Element\Service\HasApplicationCollection;

use DateTime;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Element\Entity\Db\Application;
use Element\Entity\Db\ApplicationElement;
use Element\Entity\Db\Interfaces\HasApplicationCollectionInterface;
use Element\Service\Application\ApplicationServiceAwareTrait;
use Element\Service\ApplicationElement\ApplicationElementServiceAwareTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class HasApplicationCollectionService
{
    use ApplicationServiceAwareTrait;
    use ApplicationElementServiceAwareTrait;
    use ProvidesObjectManager;
    use UserServiceAwareTrait;

    public function updateObject(HasApplicationCollectionInterface $object): HasApplicationCollectionInterface
    {
        if ($object instanceof HistoriqueAwareInterface) {
            $user = $this->getUserService()->getConnectedUser();
            if ($user === null) $user = $this->getUserService()->findById(0);
            $date = new Datetime();
            $object->setHistoModification($date);
            $object->setHistoModificateur($user);
        }

        $this->getObjectManager()->flush($object);
        return $object;
    }

    /**
     * @param HasApplicationCollectionInterface $object
     * @param array $data
     * @return HasApplicationCollectionInterface
     */
    public function updateApplications(HasApplicationCollectionInterface $object, $data): HasApplicationCollectionInterface
    {
        $applicationIds = [];
        if (isset($data['applications'])) $applicationIds = $data['applications'];

        //Suppression des applications plus présentes
        /** @var ApplicationElement $applicationElement */
        foreach ($object->getApplicationCollection() as $applicationElement) {
            if (!in_array($applicationElement->getApplication()->getId(), $applicationIds)) {
                $this->getApplicationElementService()->historise($applicationElement);
            }
        }
        //Ajout des applications plus présentes
        foreach ($applicationIds as $applicationId) {

            if ($applicationId instanceof Application) {
                $application = $applicationId;
            } else {
                $application = $this->getApplicationService()->getApplication($applicationId);
            }

            if ($application !== null and !$object->hasApplication($application)) {
                $applicationElement = new ApplicationElement();
                $applicationElement->setApplication($application);
                //TODO ajouter les autres elements : commentaires / validations tout ça
                $this->getApplicationElementService()->create($applicationElement);
                $object->getApplicationCollection()->add($applicationElement);
                $this->updateObject($object);
            }
        }
        return $object;
    }


}