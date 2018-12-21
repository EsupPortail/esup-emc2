<?php

namespace Application\Form\Agent;

use Application\Entity\Db\AgentStatus;
use Application\Entity\Db\Corps;
use Application\Entity\Db\Correspondance;
use DoctrineModule\Form\Element\ObjectSelect;
use UnicaenApp\Form\Element\Date;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class AgentForm extends Form {
    use EntityManagerAwareTrait;

    public function init()
    {
        // prenom
        $this->add([
            'type' => Text::class,
            'name' => 'prenom',
            'options' => [
                'label' => "Prénom* :",
            ],
            'attributes' => [
                'id' => 'prenom',
            ],
        ]);
        // nom
        $this->add([
            'type' => Text::class,
            'name' => 'nom',
            'options' => [
                'label' => "Nom* :",
            ],
            'attributes' => [
                'id' => 'nom',
            ],
        ]);
        // numeroPoste
        $this->add([
            'type' => Text::class,
            'name' => 'numeroPoste',
            'options' => [
                'label' => "Numero de poste :",
            ],
            'attributes' => [
                'id' => 'numeroPoste',
            ],
        ]);
        // date de début
        $this->add([
            'type' => Date::class,
            'name' => 'dateDebut',
            'options' => [
                'label' => "Date de début* :",
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'format' => 'd/m/Y'
            ],
            'attributes' => [
                'id' => 'dateDebut',
                'class' => 'form-control'
            ],
        ]);

        // date de fin
        $this->add([
            'type' => Date::class,
            'name' => 'dateFin',
            'options' => [
                'label' => "Date de fin :",
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'format' => 'd/m/Y'
            ],
            'attributes' => [
                'id' => 'dateFin',
                'class' => 'form-control'
            ],
        ]);
        // quotite
        $this->add([
            'type' => Select::class,
            'name' => 'quotite',
            'options' => [
                'label' => "Quotite travaillée* :",
                'value_options' => [
                    '100' => '100 % ',
                    '90'  => ' 90 % ',
                    '80'  => ' 80 % ',
                    '70'  => ' 70 % ',
                    '60'  => ' 60 % ',
                    '50'  => ' 50 % ',
                ],
            ],
            'attributes' => [
                'id' => 'quotite',
            ],
        ]);
        // Status
        $this->add([
            'type' => ObjectSelect::class,
            'name' => 'status',
            'options' => [
                'label' => "Status de l'agent* :",
                'empty_option' => "Sélectionner un status ...",
                'object_manager' => $this->getEntityManager(),
                'target_class' => AgentStatus::class,
                'property' => 'libelle',
                'find_method' => [
                    'name' => 'findBy',
                    'params' => [
                        'criteria' => [],
                        'orderBy' => ['libelle' => 'ASC'],
                    ],
                ],
                'disable_inarray_validator' => true,
            ],
            'attributes' => [
                'id' => 'status',
            ],
        ]);
        // Status
        $this->add([
            'type' => ObjectSelect::class,
            'name' => 'correspondance',
            'options' => [
                'label' => "Correspondance* :",
                'empty_option' => "Sélectionner une correspondance ...",
                'object_manager' => $this->getEntityManager(),
                'target_class' => Correspondance::class,
                'property' => 'libelle',
                'find_method' => [
                    'name' => 'findBy',
                    'params' => [
                        'criteria' => [],
                        'orderBy' => ['libelle' => 'ASC'],
                    ],
                ],
                'disable_inarray_validator' => true,
            ],
            'attributes' => [
                'id' => 'correspondance',
            ],
        ]);
        // Status
        $this->add([
            'type' => ObjectSelect::class,
            'name' => 'corps',
            'options' => [
                'label' => "Corps* :",
                'empty_option' => "Sélectionner un corps ...",
                'object_manager' => $this->getEntityManager(),
                'target_class' => Corps::class,
                'property' => 'libelle',
                'find_method' => [
                    'name' => 'findBy',
                    'params' => [
                        'criteria' => [],
                        'orderBy' => ['libelle' => 'ASC'],
                    ],
                ],
                'disable_inarray_validator' => true,
            ],
            'attributes' => [
                'id' => 'corps',
            ],
        ]);

        // description
        $this->add([
            'name' => 'description',
            'type' => 'textarea',
            'options' => [
                'label' => 'Missions complémentaires : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'class' => 'type2 form-control',
            ]
        ]);
        // button
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer l\'agent',
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
            'nom' => [
                'required' => true,
            ],
            'prenom' => [
                'required' => true,
            ],
            'numeroPoste' => [
                'required' => false,
            ],
            'quotite' => [
                'required' => true,
            ],
            'dateDebut' => [
                'required' => true,
            ],
            'dateFin' => [
                'required' => false,
            ],
            'status' => [
                'required' => true,
            ],
            'correspondance' => [
                'required' => true,
            ],
            'corps' => [
                'required' => true,
            ],
            'missions' => [
                'required' => false,
            ],
        ]));
    }
}