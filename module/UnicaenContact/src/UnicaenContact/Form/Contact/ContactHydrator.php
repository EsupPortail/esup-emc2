<?php

namespace UnicaenContact\Form\Contact;

use Laminas\Hydrator\HydratorInterface;
use UnicaenContact\Entity\Db\Contact;
use UnicaenContact\Service\Type\TypeServiceAwareTrait;

class ContactHydrator implements HydratorInterface
{
    use TypeServiceAwareTrait;

    public function extract(object $object): array
    {
        /** @var Contact $object */
        $data = [
            'type' => $object->getType()?->getId(),
            'service' => $object->getService(),
            'prenom' => $object->getPrenom(),
            'nom' => $object->getNom(),
            'telephone' => $object->getTelephone(),
            'email' => $object->getEmail(),
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        /** @var Contact $object */
        $type = (isset($data['type'])) ? $this->getTypeService()->getType($data['type']) : null;
        $service = (isset($data['service']) AND trim($data['service']) !== "")?trim($data['service']):null;
        $prenom = (isset($data['prenom']) AND trim($data['prenom']) !== "")?trim($data['prenom']):null;
        $nom = (isset($data['nom']) AND trim($data['nom']) !== "")?trim($data['nom']):null;
        $telephone = (isset($data['telephone']) AND trim($data['telephone']) !== "")?trim($data['telephone']):null;
        $email = (isset($data['email']) AND trim($data['email']) !== "")?trim($data['email']):null;

        $object->setType($type);
        $object->setService($service);
        $object->setPrenom($prenom);
        $object->setNom($nom);
        $object->setTelephone($telephone);
        $object->setEmail($email);
        return $object;
    }
}