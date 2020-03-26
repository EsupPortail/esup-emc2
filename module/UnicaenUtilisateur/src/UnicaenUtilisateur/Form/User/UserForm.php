<?php 

namespace UnicaenUtilisateur\Form\User;

use DoctrineModule\Validator\NoObjectExists;
use DoctrineModule\Validator\UniqueObject;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Entity\Db\User;
use Zend\Form\Element\Button;
use Zend\Form\Element\Email;
use Zend\Form\Element\Password;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\Factory;
use Zend\Validator\Identical;
use Zend\Validator\Regex;

class UserForm extends Form {
    use EntityManagerAwareTrait;

    public function init() {
        // libelle
        $this->add([
            'type' => Text::class,
            'name' => 'username',
            'options' => [
                'label' => "Nom d'utilisateur* :",
            ],
            'attributes' => [
                'id' => 'username',
            ],
        ]);
        //nom afficher
        $this->add([
            'type' => Text::class,
            'name' => 'displayname',
            'options' => [
                'label' => "Nom affiché* :",
            ],
            'attributes' => [
                'id' => 'displayname',
            ],
        ]);
        //email
        $this->add([
            'type' => Email::class,
            'name' => 'email',
            'options' => [
                'label' => "Adresse électronique* :",
            ],
            'attributes' => [
                'id' => 'email',
            ],
        ]);
        //password1
        $this->add([
            'type' => Password::class,
            'name' => 'password1',
            'options' => [
                'label' => "Mot de passe* :",
            ],
            'attributes' => [
                'id' => 'password1',
            ],
        ]);
        //password2
        $this->add([
            'type' => Password::class,
            'name' => 'password2',
            'options' => [
                'label' => "Vérification du mot de passe* :",
            ],
            'attributes' => [
                'id' => 'password2',
            ],
        ]);
        //submit
        $this->add([
            'type' => Button::class,
            'name' => 'bouton',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ],
        ]);

        $this->setInputFilter((new Factory())->createInputFilter([
            'username'      => [
                'required' => true,
                'validators' => [[
                    'name' => NoObjectExists::class,
                    'options' => [
                        'object_manager' => $this->getEntityManager(),
                        'object_repository'   => $this->getEntityManager()->getRepository(User::class),
                        'fields'   => 'username',
                        'messages' => [
                            NoObjectExists::ERROR_OBJECT_FOUND => "Le nom d'utilisateur fourni est déjà utilisé.",
                        ],
                    ]
                ]],
            ],
            'displayname'   => [ 'required' => true,],
            'email'         => [
                'required' => true,
                'validators' => [[
                    'name' => NoObjectExists::class,
                    'options' => [
                        'object_manager' => $this->getEntityManager(),
                        'object_repository'   => $this->getEntityManager()->getRepository(User::class),
                        'fields'   => 'email',
                        'messages' => [
                            NoObjectExists::ERROR_OBJECT_FOUND => "L'adresse électronique fournie est déjà utilisée.",
                        ],
                    ]
                ]],
            ],
            'password1'     => [ 
                'required' => true,
                'validators' => [
                [
                    'name' => Regex::class,
                    'options' => [
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*_])(?=.{8,})/',
                        'messages' => [
                            Regex::NOT_MATCH => "Le mot de passe choisi est trop simple. <br/> <b><u>N.B.:</u></b> Le mot de passe doit avoir une longueur minimale de 8 caractères avec au moins : <ul> <li>une majuscule;</li> <li>une minuscule;</li> <li>un chiffre;</li> <li>un caractère spécial.</li></ul>",
                        ],
                        //'break_chain_on_failure' => true,
                    ],
            ],],
            ],
            'password2'     => [ 
                'required' => true,
                'validators' => [
                    new Identical('password1'),
                ],
            ],
        ]));
    }
}