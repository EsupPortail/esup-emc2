<?php

namespace Application\Form\AgentFormation;

use Application\Service\Formation\FormationServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Date;
use Zend\Form\Element\Select;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class AgentFormationForm extends Form {
    use FormationServiceAwareTrait;

    public function init()
    {
        //formation
        $this->add([
            'name' => 'formation',
            'type' => Select::class,
            'options' => [
                'label' => 'Formation * : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'empty_option' => "Sélectionner une formation ... ",
                'value_options' => $this->getFormationService()->getFormationsDisponiblesAsOptions(),
            ],
            'attributes' => [
                'id'                => 'formation',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ]
        ]);
        //date
        $this->add([
            'name' => 'date',
            'type' => Date::class,
            'options' => [
                'label' => 'Date de la formation * : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'format' => 'd/m/Y',
            ],
            'attributes' => [
                'id'                => 'date',
            ]
        ]);
        // button
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
            'formation' => [
                'required' => true,
            ],
            'date' => [
                'required' => true,
            ],
        ]));
    }
}