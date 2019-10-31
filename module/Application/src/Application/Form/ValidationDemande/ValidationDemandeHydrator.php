<?php

namespace Application\Form\ValidationDemande;

use Application\Entity\Db\ValidationDemande;
use Utilisateur\Service\User\UserServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class ValidationDemandeHydrator implements HydratorInterface {
    use UserServiceAwareTrait;
    /**
     * @param ValidationDemande $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'cible'      => ($object->getObjectId())?$object->getObjectId():null,
            'validateur' => ($object->getValidateur())?$object->getValidateur()->getId():null,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param ValidationDemande $object
     * @return ValidationDemande
     */
    public function hydrate(array $data, $object)
    {
       $validateur = $this->getUserService()->getUtilisateur(($data['validateur'])?$data['validateur']:null);
       $object->setObjectId($data['cible']);
       $object->setValidateur($validateur);
       return $object;
    }
}