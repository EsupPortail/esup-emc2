<?php

namespace Carriere\Form\Niveau;

use Application\Form\HasDescription\HasDescriptionFieldset;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Number;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class NiveauForm extends Form {

    public function init(): void
    {
        //niveau
        $this->add([
            'type' => Number::class,
            'name' => 'niveau',
            'options' => [
                'label' => "Niveau <span title='Champ obligatoire'></span>:",
                'label_options' => [ 'disable_html_escape' => true, ],
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
                'label' => "Etiquette <span title='Champ obligatoire'></span>:",
                'label_options' => [ 'disable_html_escape' => true, ],
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
                'label' => "Libelle <span title='Champ obligatoire'></span>:",
                'label_options' => [ 'disable_html_escape' => true, ],
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