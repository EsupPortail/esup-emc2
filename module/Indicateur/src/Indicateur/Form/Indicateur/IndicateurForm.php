<?php

namespace Indicateur\Form\Indicateur;

use Indicateur\Entity\Db\Indicateur;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class IndicateurForm extends Form {

    public function init()
    {
        // libelle
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
        // description
        $this->add([
            'name' => 'description',
            'type' => 'textarea',
            'options' => [
                'label' => 'Description : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'class' => 'type2 form-control',
            ]
        ]);
        // view_id
        $this->add([
            'type' => Text::class,
            'name' => 'view_id',
            'options' => [
                'label' => "Identifiant de la vue* :",
            ],
            'attributes' => [
                'id' => 'libelle',
            ],
        ]);
        // entity
        $this->add([
            'type' => Select::class,
            'name' => 'entity',
            'options' => [
                'label' => "Entity associé* :",
                'empty_option' => "Sélectionner une entité ...",
                'value_options' => [
                   Indicateur::ENTITY_LIBRE       => 'Libre',
                   Indicateur::ENTITY_ADAPTATIF   => 'Adaptatif',
                   Indicateur::ENTITY_AGENT       => 'Agent',
                   Indicateur::ENTITY_STRUCTURE   => 'Structure',
                ]
            ],
            'attributes' => [
                'id' => 'formations',
                'class'             => 'bootstrap-selectpicker show-tick',
            ],
        ]);
        // requete
        $this->add([
            'name' => 'requete',
            'type' => 'textarea',
            'options' => [
                'label' => 'Requête* : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'id' => 'requete',
                'class' => 'form-control',
                'style' => 'min-height:250px;',
            ]
        ]);
        // submit
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
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
            'libelle'               => [ 'required' => true,  ],
            'description'           => [ 'required' => false,  ],
            'view_id'               => [ 'required' => true,  ],
            'entity'                => [ 'required' => true,  ],
            'requete'               => [ 'required' => true,  ],
        ]));
    }
}