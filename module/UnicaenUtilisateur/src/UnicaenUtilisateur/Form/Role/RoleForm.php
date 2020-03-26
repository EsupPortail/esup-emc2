<?php

namespace UnicaenUtilisateur\Form\Role;

use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Entity\Db\Role;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\Service\Role\RoleServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Checkbox;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\Factory;
use Zend\Validator\Callback;

class RoleForm extends Form {
    use RoleServiceAwareTrait;
    use EntityManagerAwareTrait;

    public function init()
    {
        //libelle
        $this->add([
            'type' => Text::class,
            'name' => 'libelle',
            'options' => [
                'label' => "Libelle* :",
            ],
            'attributes' => [
                'id' => 'libelle',
            ],
        ]);
        $this->add([
            'name' => 'old-libelle',
            'type' => Hidden::class,
            'attributes' => [
                'value' => "",
            ],
        ]);
        //RoleId
        $this->add([
            'type' => Text::class,
            'name' => 'role_id',
            'options' => [
                'label' => "Identifiant du rôle* :",
            ],
            'attributes' => [
                'id' => 'role_id',
            ],
        ]);
        $this->add([
            'name' => 'old-roleid',
            'type' => Hidden::class,
            'attributes' => [
                'value' => "",
            ],
        ]);
        //parent
        $this->add([
            'type' => Select::class,
            'name' => 'parent_id',
            'options' => [
                'label' => "Role parent :",
                'empty_option' => "Aucun parent",
                'value_options' => $this->getRoleService()->getRolesAsOptions(),
            ],
            'attributes' => [
                'id' => 'parent_id',
            ],
        ]);
        //is default
        //parent
        $this->add([
            'type' => Select::class,
            'name' => 'is_default',
            'options' => [
                'label' => "Est un rôle par defaut* :",
                'value_options' => [
                    'false' => 'Non',
                    'true' => 'Oui',
                ],
            ],
            'attributes' => [
                'id' => 'is_default',
            ],
        ]);
        //ldap filter
        $this->add([
            'type' => Text::class,
            'name' => 'ldap_filter',
            'options' => [
                'label' => "Filtre LDAP* :",
            ],
            'attributes' => [
                'id' => 'ldap_filter',
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
            'libelle' => [
                'required' => true,
                'validators' => [[
                    'name' => Callback::class,
                    'options' => [
                        'messages' => [
                            Callback::INVALID_VALUE => "Ce libellé de rôle existe déjà",
                        ],
                        'callback' => function ($value, $context = []) {
                            if($value == $context['old-libelle']) return true;
                            return ($this->getEntityManager()->getRepository(Role::class)->findOneBy(['libelle'=>$value],[]) == null);
                        },
//                        'break_chain_on_failure' => true,
                    ],
                ]],
            ],
            'role_id' => [
                'required' => true,
                'validators' => [[
                    'name' => Callback::class,
                    'options' => [
                        'messages' => [
                            Callback::INVALID_VALUE => "Cet identifiant de rôle existe déjà",
                        ],
                        'callback' => function ($value, $context = []) {
                            if($value == $context['old-roleid']) return true;
                            return ($this->getEntityManager()->getRepository(Role::class)->findOneBy(['roleId'=>$value],[]) == null);
                        },
//                        'break_chain_on_failure' => true,
                    ],
                ]],
            ],
            'parent_id' => ['required' => false, ],
            'is_default' => ['required' => true, ],
            'ldap_fitler' => ['required' => false, ],
            'old-libelle' => ['required' => false, ],
            'old-roleid' => ['required' => false, ],
        ]));
    }

    public function setOldLibelle($value){
        $this->get('old-libelle')->setValue($value);
    }

    public function setOldRoleId($value){
        $this->get('old-roleid')->setValue($value);
    }
}