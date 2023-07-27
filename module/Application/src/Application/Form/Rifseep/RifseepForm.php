<?php

namespace Application\Form\Rifseep;

use Laminas\Form\Element\Button;
use Laminas\Form\Element\Number;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class RifseepForm extends Form {

    public function init(): void
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