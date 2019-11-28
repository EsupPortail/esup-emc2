<?php

namespace Application\Form\ConfigurationFicheMetier;

use Zend\Form\Element\Button;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

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