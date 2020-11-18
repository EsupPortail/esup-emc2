<?php

namespace Application\Service\HasCompetenceCollection;

use Application\Entity\Db\CompetenceElement;
use Application\Entity\Db\Interfaces\HasCompetenceCollectionInterface;
use Application\Service\CompetenceElement\CompetenceElementServiceAwareTrait;
use Application\Service\Competence\CompetenceServiceAwareTrait;
use Doctrine\ORM\ORMException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Entity\DateTimeAwareTrait;
use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class HasCompetenceCollectionService
{
    use CompetenceServiceAwareTrait;
    use CompetenceElementServiceAwareTrait;
    use EntityManagerAwareTrait;
    use DateTimeAwareTrait;
    use UserServiceAwareTrait;

    /**
     * @param HasCompetenceCollectionInterface $object
     * @return HasCompetenceCollectionInterface
     */
    public function updateObject(HasCompetenceCollectionInterface $object)
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
            $competence = $this->getCompetenceService()->getCompetence($competenceId);
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