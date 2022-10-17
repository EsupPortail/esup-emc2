<?php

namespace Carriere\Form\ModifierNiveau;

use Carriere\Service\Niveau\NiveauServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class ModifierNiveauForm extends Form {
    use NiveauServiceAwareTrait;

    public function init()
    {
        //niveau :: number
        $this->add([
            'type' => Select::class,
            'name' => 'niveau',
            'options' => [
                'label' => "Niveau :",
                'empty_option' => "Sélectionner un niveau",
                'value_options' => $this->getNiveauService()->getNiveauxAsOptions(),
            ],
            'attributes' => [
                'id' => 'niveau',
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