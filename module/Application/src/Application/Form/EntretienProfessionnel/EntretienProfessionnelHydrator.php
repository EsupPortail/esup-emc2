<?php

namespace Application\Form\EntretienProfessionnel;

use Application\Entity\Db\EntretienProfessionnel;
use Utilisateur\Service\User\UserServiceAwareTrait;
use DateTime;
use Zend\Stdlib\Hydrator\HydratorInterface;

class EntretienProfessionnelHydrator implements HydratorInterface {
    use UserServiceAwareTrait;

    /**
     * @param EntretienProfessionnel $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'responsable' => $object->getResponsable(),
            'agent' => $object->getPersonnel(),
            'annee' => $object->getAnnee(),
            'date_entretien' => $object->getDateEntretien(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param EntretienProfessionnel $object
     * @return EntretienProfessionnel
     */
    public function hydrate(array $data, $object)
    {
        $reponsable = $this->getUserService()->getUtilisateur($data['responsable']);
        $agent      = $this->getUserService()->getUtilisateur($data['agent']);
        $date       = DateTime::createFromFormat('d/m/Y', $data['date_entretien']);

        $object->setResponsable($reponsable);
        $object->setPersonnel($agent);
        $object->setAnnee($data['annee']);
        $object->setDateEntretien($date);

        return $object;
    }

}