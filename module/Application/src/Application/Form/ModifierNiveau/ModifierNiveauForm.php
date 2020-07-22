<?php

namespace Application\Form\ModifierNiveau;

use Zend\Form\Element\Button;
use Zend\Form\Element\Number;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class ModifierNiveauForm extends Form {

    public function init()
    {
        //niveau :: number
        $this->add([
            'type' => Number::class,
            'name' => 'niveau',
            'options' => [
                'label' => "Niveau :",
            ],
            'attributes' => [
                'id' => 'niveau',
                'min'  => '1',
                'max'  => '10',
                'step' => '1',
            ],
        ]);
        //button
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
            'niveau'             => [ 'required' => false,  ],
        ]));
    }
}