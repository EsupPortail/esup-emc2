<?php 

namespace UnicaenUtilisateur\Form\User;

use UnicaenUtilisateur\Entity\Db\UserInterface;
use UnicaenUtilisateur\Exception\RuntimeException;
use Zend\Hydrator\HydratorInterface;

class UserHydrator implements HydratorInterface {

    /**
     * @var UserInterface $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'username' => $object->getUsername(),
            'displayname' => $object->getDisplayName(),
            'email' => $object->getEmail(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param UserInterface $object
     * @return UserInterface
     */
    public function hydrate(array $data, $object)
    {
        $object->setUsername($data['username']);
        $object->setDisplayName($data['displayname']);
        $object->setEmail($data['email']);
        
        if ($data['password1'] === $data['password2']) {
            $chiffre = $data['password1'];
            $object->setPassword($chiffre);
        } else {
            throw new RuntimeException('Les deux mots de passe ne sont pas identiques');
        }
        return $object;        
    }
}