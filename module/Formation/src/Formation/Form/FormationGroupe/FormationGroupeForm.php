<?php

namespace Formation\Form\FormationGroupe;

use Application\Form\HasDescription\HasDescriptionFieldset;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Number;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class FormationGroupeForm extends Form
{

    public function init()
    {
        //libelle
        $this->add([
            'type' => Text::class,
            'name' => 'libelle',
            'options' => [
                'label' => "Libelle* :",
            ],
            'attributes' => [
                'id' => 'libelle',
            ],
        ]);
        //description
        $this->add([
            'name' => 'HasDescription',
            'type' => HasDescriptionFieldset::class,
            'attributes' => [
                'id' => 'description',
            ],
        ]);
        //ordre
        $this->add([
            'type' => Number::class,
            'name' => 'ordre',
            'options' => [
                'label' => "Ordre :",
            ],
            'attributes' => [
                'id' => 'ordre',
            ],
        ]);
        //couleur
//        $this->add([
//            'type' => Color::class,
//            'name' => 'couleur',
//            'options' => [
//                'label' => "Couleur :",
//            ],
//            'attributes' => [
//                'id' => 'couleur',
//            ],
//        ]);
        //bouton
        $this->add([
            'type' => Button::class,
            'name' => 'bouton',
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
        //input
        $this->setInputFilter((new Factory())->createInputFilter([
            'libelle' => ['required' => true,],
            'description' => ['required' => false,],
            'ordre' => ['required' => false,],
//            'couleur'   => [ 'required' => false, ],
        ]));
    }
}