<?php

namespace Metier\Form\Domaine;

use Laminas\Form\Element\Button;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class DomaineForm extends Form
{

    public function init(): void
    {
        // libelle
        $this->add([
            'type' => Text::class,
            'name' => 'libelle',
            'options' => [
                'label' => "Libelle <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'id' => 'libelle',
            ],
        ]);
        //fonction
        $this->add([
            'type' => Select::class,
            'name' => 'fonction',
            'options' => [
                'label' => "Fonction :",
                'empty_option' => "Sélectionner une fonction ...",
                'value_options' => [
                    'Soutien' => 'Soutien',
                    'Support' => 'Support',
                ],
            ],
            'attributes' => [
                'id' => 'fonction',
                'class' => 'bootstrap-selectpicker show-tick',
                'data-live-search' => 'true',
            ],
        ]);

        // button
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer',
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ],
        ]);

        $this->setInputFilter((new Factory())->createInputFilter([
            'libelle' => ['required' => true,],
            'fonction' => ['required' => false,],
        ]));
    }
}