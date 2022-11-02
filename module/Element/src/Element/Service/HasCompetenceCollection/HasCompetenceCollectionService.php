<?php

namespace Element\Service\HasCompetenceCollection;

use DateTime;
use Doctrine\ORM\ORMException;
use Element\Entity\Db\Competence;
use Element\Entity\Db\CompetenceElement;
use Element\Entity\Db\Interfaces\HasCompetenceCollectionInterface;
use Element\Service\CompetenceElement\CompetenceElementServiceAwareTrait;
use Element\Service\Competence\CompetenceServiceAwareTrait;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class HasCompetenceCollectionService
{
    use CompetenceServiceAwareTrait;
    use CompetenceElementServiceAwareTrait;
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /**
     * @param HasCompetenceCollectionInterface $object
     * @return HasCompetenceCollectionInterface
     */
    public function updateObject(HasCompetenceCollectionInterface $object)
    {
        if ($object instanceof HistoriqueAwareInterface) {
            $user = $this->getUserService()->getConnectedUser();
            if ($user === null) $user = $this->getUserService()->findById(0);
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
     * @param HasCompetenceCollectionInterface $object
     * @param array $data
     * @return HasCompetenceCollectionInterface
     */
    public function updateCompetences(HasCompetenceCollectionInterface $object, $data)
    {
        $competenceIds = [];
        if (isset($data['competences'])) $competenceIds = $data['competences'];

        //Suppression des applications plus présentes
        /** @var CompetenceElement $competenceElement */
        foreach ($object->getCompetenceCollection() as $competenceElement) {
            if (array_search($competenceElement->getCompetence()->getId(), $competenceIds) === false) {
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
                //TODO ajouter les autres elements : commentaires / validations tout çà
                $this->getCompetenceElementService()->create($competenceElement);
                $object->getCompetenceCollection()->add($competenceElement);
                $this->updateObject($object);
            }
        }
        return $object;
    }

    /**
     * @param HasCompetenceCollectionInterface $object
     * @param CompetenceElement $competenceElement
     * @return HasCompetenceCollectionInterface
     */
    public function addCompetence(HasCompetenceCollectionInterface $object, CompetenceElement $competenceElement)
    {
        $this->getCompetenceElementService()->create($competenceElement);
        $object->getCompetenceCollection()->add($competenceElement);
        $this->updateObject($object);
        return $object;
    }

    /**
     * @param HasCompetenceCollectionInterface $object
     * @param CompetenceElement $competenceElement
     * @return HasCompetenceCollectionInterface
     */
    public function deleteCompetence(HasCompetenceCollectionInterface $object, CompetenceElement $competenceElement)
    {
        $object->getCompetenceCollection()->removeElement($competenceElement);
        $this->updateObject($object);
        return $object;
    }

    /**
     * @param HasCompetenceCollectionInterface $object
     * @return HasCompetenceCollectionInterface
     */
    public function clearCompetence(HasCompetenceCollectionInterface $object)
    {
        $object->getCompetenceCollection()->clear();
        $this->updateObject($object);
        return $object;
    }

}