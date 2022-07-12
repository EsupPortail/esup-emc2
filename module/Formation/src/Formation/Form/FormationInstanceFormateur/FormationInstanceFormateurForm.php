<?php

namespace Formation\Form\FormationInstanceFormateur;

use Laminas\Form\Element\Button;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use Laminas\Validator\Regex;

class FormationInstanceFormateurForm extends Form
{

    public function init()
    {
        //prenom
        $this->add([
            'type' => Text::class,
            'name' => 'prenom',
            'options' => [
                'label' => "Prénom * :",
            ],
            'attributes' => [
                'id' => 'prenom',
            ],
        ]);
        //nom
        $this->add([
            'type' => Text::class,
            'name' => 'nom',
            'options' => [
                'label' => "Nom * :",
            ],
            'attributes' => [
                'id' => 'nom',
            ],
        ]);
        //nom
        $this->add([
            'type' => Text::class,
            'name' => 'email',
            'options' => [
                'label' => "Adresse électronique * :",
            ],
            'attributes' => [
                'id' => 'email',
            ],
        ]);
        //attachement
        $this->add([
            'type' => Text::class,
            'name' => 'attachement',
            'options' => [
                'label' => "Structure d'attachement :",
            ],
            'attributes' => [
                'id' => 'attachement',
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

        //inputfilter
        $this->setInputFilter((new Factory())->createInputFilter([
            'prenom' => ['required' => true],
            'nom' => ['required' => true],
            'email' => ['required' => true],
            'attachement' => ['required' => false],
        ]));
    }
}