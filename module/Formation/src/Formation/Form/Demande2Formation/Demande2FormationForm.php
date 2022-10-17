<?php

namespace Formation\Form\Demande2Formation;

use Laminas\Form\Element\Button;
use Laminas\Form\Element\Number;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class Demande2FormationForm extends Form {

    public function init() {

        //libelle
        $this->add([
            'type' => Text::class,
            'name' => 'libelle',
            'options' => [
                'label' => "Intitulé du stage <span class='icon obligatoire' title='Champ obligatoire'></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'libelle',
            ],
        ]);
        //volume
        $this->add([
            'type' => Number::class,
            'name' => "volume",
            'options' => [
                'label' => "Volume horaire de la formation <span class='icon icon-asterisque' title='Champ obligatoire pour les volumes horaires' ></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'volume',
            ],
        ]);
        //suivi
        $this->add([
            'type' => Number::class,
            'name' => "suivi",
            'options' => [
                'label' => "Volume suivi par l'agent  <span class='icon icon-asterisque' title='Champ obligatoire pour les volumes horaires' ></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'suivi',
            ],
        ]);

        //-- Bouton ----------------------------------------------------------------------------------------------------

        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer',
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ],
        ]);

        //-- Input filter ------------------------------

        $this->setInputFilter((new Factory())->createInputFilter([
            'libelle'            => [ 'required' => true,  ],
            'volume         '    => [ 'required' => true,  ],
            'suivi'              => [ 'required' => true,  ],
        ]));
    }
}