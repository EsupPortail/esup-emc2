<?php

namespace Carriere\Form\Niveau;

use Application\Form\HasDescription\HasDescriptionFieldset;
use Zend\Form\Element\Button;
use Zend\Form\Element\Number;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class NiveauForm extends Form {

    public function init()
    {
        //niveau
        $this->add([
            'type' => Number::class,
            'name' => 'niveau',
            'options' => [
                'label' => "Niveau * :",
            ],
            'attributes' => [
                'id' => 'niveau',
                'min'  => '1',
                'max'  => '5',
                'step' => '1',
            ],
        ]);
        //libelle
        $this->add([
            'type' => Text::class,
            'name' => 'etiquette',
            'options' => [
                'label' => "Etiquette * :",
            ],
            'attributes' => [
                'id' => 'etiquette',
            ],
        ]);
        //niveau
        $this->add([
            'type' => Text::class,
            'name' => 'libelle',
            'options' => [
                'label' => "Libelle * :",
            ],
            'attributes' => [
                'id' => 'libelle',
            ],
        ]);
        // description
        $this->add([
            'name' => 'HasDescription',
            'type' => HasDescriptionFieldset::class,
            'attributes' => [
                'id' => 'description',
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
            'niveau'                => [ 'required' => true,  ],
            'etiquette'             => [ 'required' => true,  ],
            'libelle'               => [ 'required' => true,  ],
            'description'           => [ 'required' => false,  ],
        ]));
    }
}