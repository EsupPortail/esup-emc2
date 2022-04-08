<?php

namespace Application\Form\Rifseep;

use Zend\Form\Element\Button;
use Zend\Form\Element\Number;
use Zend\Form\Element\Select;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class RifseepForm extends Form {

    public function init()
    {
        // specificite
        $this->add([
            'name' => 'rifseep',
            'type' => Select::class,
            'options' => [
                'label' => "Groupe de RIFSEEP :",
                'value_options' => [
                    null => "Inconnu",
                    1 => "Groupe 1",
                    2 => "Groupe 2",
                    3 => "Groupe 3",
                ],
            ],
            'attributes' => [
                'id' => 'rifseep',
                'class' => 'bootstrap-selectpicker show-tick',
                'data-live-search' => 'true',
            ],
        ]);
        $this->add([
            'name' => 'nbi',
            'type' => Number::class,
            'options' => [
                'label' => "Nombre de points de NBI :",
            ],
            'attributes' => [
                'id' => 'nbi',
                'min'  => '0',
                'max'  => '50',
                'step' => '1',
            ],
        ]);
        // button
        $this->add([
            'type' => Button::class,
            'name' => 'submit',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Ajouter',
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
            'rifseep'               => [ 'required' => false,  ],
            'nbi'                   => [ 'required' => false,  ],
        ]));
    }
}