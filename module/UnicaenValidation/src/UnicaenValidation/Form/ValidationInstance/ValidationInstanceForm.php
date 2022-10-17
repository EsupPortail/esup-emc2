<?php

namespace UnicaenValidation\Form\ValidationInstance;

use UnicaenValidation\Service\ValidationType\ValidationTypeServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class ValidationInstanceForm extends Form {
    use ValidationTypeServiceAwareTrait;

    public function init() {

        //type
        $this->add([
            'type' => Select::class,
            'name' => 'type',
            'options' => [
                'label' => "Type de validation* :",
                'empty_option' => "Choisir un type de validation",
                'value_options' => $this->getValidationTypeService()->getValidationsTypesAsOptions(),
            ],
            'attributes' => [
                'id'                => 'type',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);
        //valeur
        $this->add([
            'name' => 'valeur',
            'type' => Text::class,
            'options' => [
                'label' => 'Refus (laisser vide si acceptation) : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'id'                => 'valeur',
            ]
        ]);
        //entityclass
        $this->add([
            'name' => 'entityclass',
            'type' => Text::class,
            'options' => [
                'label' => "Classe de l'entité validée : ",
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'id'                => 'entityclass',
            ]
        ]);
        //classid
        $this->add([
            'name' => 'entityid',
            'type' => Text::class,
            'options' => [
                'label' => "Identifiant de l'entité validée : ",
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'id'                => 'entityid',
            ]
        ]);
        //submit
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer' ,
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ],
        ]);

        //inputfilter
        $this->setInputFilter((new Factory())->createInputFilter([
            'type'               => [ 'required' => true,  ],
            'valeur'             => [ 'required' => false,  ],
            'entityclass'        => [ 'required' => false,  ],
            'entityid'           => [ 'required' => false,  ],
        ]));
    }
}