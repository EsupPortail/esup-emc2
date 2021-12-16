<?php

namespace Application\Service\HasApplicationCollection;

use Application\Entity\Db\ApplicationElement;
use Application\Entity\Db\Interfaces\HasApplicationCollectionInterface;
use Application\Service\Application\ApplicationServiceAwareTrait;
use Application\Service\ApplicationElement\ApplicationElementServiceAwareTrait;
use DateTime;
use Doctrine\ORM\ORMException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class HasApplicationCollectionService
{
    use ApplicationServiceAwareTrait;
    use ApplicationElementServiceAwareTrait;
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /**
     * @param HasApplicationCollectionInterface $object
     * @return HasApplicationCollectionInterface
     */
    public function updateObject(HasApplicationCollectionInterface $object)
    {
        if ($object instanceof HistoriqueAwareInterface) {
            $user = $this->getUserService()->getConnectedUser();
            if ($user === null) $user = $this->getUserService()->getUtilisateur(0);
            $date = new Datetime();
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
     * @param HasApplicationCollectionInterface $object
     * @param array $data
     * @return HasApplicationCollectionInterface
     */
    public function updateApplications(HasApplicationCollectionInterface $object, $data)
    {
        $applicationIds = [];
        if (isset($data['applications'])) $applicationIds = $data['applications'];

        //Suppression des applications plus présentes
        /** @var ApplicationElement $applicationElement */
        foreach ($object->getApplicationCollection() as $applicationElement) {
            if (array_search($applicationElement->getApplication()->getId(), $applicationIds) === false) {
                $this->getApplicationElementService()->historise($applicationElement);
            }
        }
        //Ajout des applications plus présentes
        foreach ($applicationIds as $applicationId) {
            $application = $this->getApplicationService()->getApplication($applicationId);
            if ($application !== null and !$object->hasApplication($application)) {
                $applicationElement = new ApplicationElement();
                $applicationElement->setApplication($application);
                //TODO ajouter les autres elements : commentaires / validations tout çà
                $this->getApplicationElementService()->create($applicationElement);
                $object->getApplicationCollection()->add($applicationElement);
                $this->updateObject($object);
            }
        }
        return $object;
    }

    /**
     * @param HasApplicationCollectionInterface $object
     * @param ApplicationElement $applicationElement
     * @return HasApplicationCollectionInterface
     */
    public function addApplication(HasApplicationCollectionInterface $object, ApplicationElement $applicationElement)
    {
        $this->getApplicationElementService()->create($applicationElement);
        $object->getApplicationCollection()->add($applicationElement);
        $this->updateObject($object);
        return $object;
    }

    /**
     * @param HasApplicationCollectionInterface $object
     * @param ApplicationElement $applicationElement
     * @return HasApplicationCollectionInterface
     */
    public function deleteApplication(HasApplicationCollectionInterface $object, ApplicationElement $applicationElement)
    {
        $object->getApplicationCollection()->removeElement($applicationElement);
        $this->updateObject($object);
        return $object;
    }

    /**
     * @param HasApplicationCollectionInterface $object
     * @return HasApplicationCollectionInterface
     */
    public function clearApplication(HasApplicationCollectionInterface $object)
    {
        $object->getApplicationCollection()->clear();
        $this->updateObject($object);
        return $object;
    }

}