<?php

namespace Element\Service\HasCompetenceCollection;

use DateTime;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Element\Entity\Db\Competence;
use Element\Entity\Db\CompetenceElement;
use Element\Entity\Db\Interfaces\HasCompetenceCollectionInterface;
use Element\Service\Competence\CompetenceServiceAwareTrait;
use Element\Service\CompetenceElement\CompetenceElementServiceAwareTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class HasCompetenceCollectionService
{
    use CompetenceServiceAwareTrait;
    use CompetenceElementServiceAwareTrait;
    use ProvidesObjectManager;
    use UserServiceAwareTrait;

    /**
     * @param HasCompetenceCollectionInterface $object
     * @return HasCompetenceCollectionInterface
     */
    public function updateObject(HasCompetenceCollectionInterface $object): HasCompetenceCollectionInterface
    {
        if ($object instanceof HistoriqueAwareInterface) {
            $user = $this->getUserService()->getConnectedUser();
            if ($user === null) $user = $this->getUserService()->findById(0);
            $date = new DateTime();
            $object->setHistoModification($date);
            $object->setHistoModificateur($user);
        }

        $this->getObjectManager()->flush($object);
        return $object;
    }

    /**
     * @param HasCompetenceCollectionInterface $object
     * @param array $data
     * @return HasCompetenceCollectionInterface
     */
    public function updateCompetences(HasCompetenceCollectionInterface $object, $data): HasCompetenceCollectionInterface
    {
        $competenceIds = [];
        if (isset($data['competences'])) $competenceIds = $data['competences'];

        //Suppression des applications plus présentes
        /** @var CompetenceElement $competenceElement */
        foreach ($object->getCompetenceCollection() as $competenceElement) {
            if (!in_array($competenceElement->getCompetence()->getId(), $competenceIds)) {
                $this->getCompetenceElementService()->historise($competenceElement);
            }
        }
        //Ajout des applications plus présentes
        foreach ($competenceIds as $competenceId) {
            if ($competenceId instanceof Competence) {
                $competence = $competenceId;
            } else {
                $competence = $this->getCompetenceService()->getCompetence($competenceId);
            }

            if ($competence !== null and !$object->hasCompetence($competence)) {
                $competenceElement = new CompetenceElement();
                $competenceElement->setCompetence($competence);
                //TODO ajouter les autres elements : commentaires / validations tout ça
                $this->getCompetenceElementService()->create($competenceElement);
                $object->getCompetenceCollection()->add($competenceElement);
                $this->updateObject($object);
            }
        }
        return $object;
    }


}