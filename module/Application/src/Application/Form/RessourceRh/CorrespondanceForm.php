<?php

namespace Application\Form\RessourceRh;

use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Form;

class CorrespondanceForm extends Form {

    public function init()
    {
        // reference
        $this->add([
            'type' => Select::class,
            'name' => 'reference',
            'options' => [
                'label' => "Référence :",
                'value_options' => [
                    ''              => 'Choisir une correspondance',
                    'BAP'           => 'Correspondance BAP',
                    'REFERENS'      => 'Correspondance REFERENS',
                    'REME'          => 'Correspondance REME',
                    'bibliothèque'  => 'Correspondance bibliothèque',
                ],
            ],
            'attributes' => [
                'id' => 'reference',
            ],
        ]);
        // libelle
        $this->add([
            'type' => Text::class,
            'name' => 'libelle',
            'options' => [
                'label' => "Libelle :",
            ],
            'attributes' => [
                'id' => 'libelle',
            ],
        ]);
        // description
        $this->add([
            'type' => Text::class,
            'name' => 'description',
            'options' => [
                'label' => "Description :",
            ],
            'attributes' => [
                'id' => 'description',
            ],
        ]);
        // button
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer la correspondance',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ],
        ]);
    }
}