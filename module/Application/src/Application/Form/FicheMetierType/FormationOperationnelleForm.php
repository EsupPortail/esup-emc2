<?php

namespace Application\Form\FicheMetierType;

use Zend\Form\Element\Button;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class FormationOperationnelleForm extends Form {

    public function init()
    {
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
                'class' => 'description form-control',
            ]
        ]);
        // formation
        $this->add([
            'name' => 'formation',
            'type' => 'textarea',
            'options' => [
                'label' => 'Formation : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'class' => 'formation form-control',
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
            'description' => [
                'required' => false,
            ],
            'formation' => [
                'required' => false,
            ],
        ]));
    }
}