<?php

namespace Application\Form\ModifierNiveau;

use Application\Service\Niveau\NiveauServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Number;
use Zend\Form\Element\Select;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

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