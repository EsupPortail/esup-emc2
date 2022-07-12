<?php

namespace Application\Form\ConfigurationFicheMetier;

use Laminas\Form\Element\Button;
use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class ConfigurationFicheMetierForm extends Form {

    public function init()
    {
        //type
        $this->add([
            'type' => Hidden::class,
            'name' => 'type',
            'options' => [],
            'attributes' => [
                'id' => 'type',
                'readonly' => true,
            ],
        ]);
        //operation
        $this->add([
            'type' => Hidden::class,
            'name' => 'operation',
            'options' => [],
            'attributes' => [
                'id' => 'operation',
                'readonly' => true,
            ],
        ]);
        //id
        $this->add([
            'type' => Select::class,
            'name' => 'select',
            'options' => [],
            'attributes' => [
                'id' => 'select',
                'class' => 'selectpicker form-control',
                'data-live-search' => true,
            ],
        ]);
        // button
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

        $this->setInputFilter((new Factory())->createInputFilter([
            'select'           => [ 'required' => true,  ],
            'type'             => [ 'required' => true,  ],
            'operation'        => [ 'required' => false,  ],
        ]));
    }
}