<?php

namespace Formation\Form\StagiaireExterne;

use Laminas\Form\Element\Button;
use Laminas\Form\Element\Date;
use Laminas\Form\Element\Email;
use Laminas\Form\Element\Radio;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class StagiaireExterneForm extends Form {

    public function init(): void
    {
        //prenom
        $this->add([
            'type' => Text::class,
            'name' => 'prenom',
            'options' => [
                'label' => "Prénom <span class='icon icon-obligatoire text-danger' title='Champ obligatoire'></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
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
                'label' => "Nom d'usage<span class='icon icon-obligatoire text-danger' title='Champ obligatoire'></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'nom',
            ],
        ]);
        //date naissance
        $this->add([
            'type' => Date::class,
            'name' => 'date_naissance',
            'options' => [
                'label' => "Date de naissance <span class='icon icon-information text-info' title='Utile pour les enquètes ministérielles'></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
//                'format' => 'd/m/Y',
            ],
            'attributes' => [
                'id' => 'date_naissance',
            ],
        ]);
        //sexe
        $this->add([
            'type' => Radio::class,
            'name' => 'sexe',
            'options' => [
                'label' => "Sexe <span class='icon icon-information text-info' title='Utile pour les enquètes ministérielles'></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
                'value_options' => ['F' => 'Femme', 'H' => 'Homme', 'N' => 'Neutre']
            ],
            'attributes' => [
                'id' => 'sexe',
            ],
        ]);

        //structure
        $this->add([
            'type' => Text::class,
            'name' => 'structure',
            'options' => [
                'label' => "Nom de la structure du stagiaire  :",
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'structure',
            ],
        ]);
        //mail
        $this->add([
            'type' => Email::class,
            'name' => 'email',
            'options' => [
                'label' => "Adresse électronique <span class='icon icon-obligatoire text-danger' title='Champ obligatoire'></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'email',
            ],
        ]);
        //login
        $this->add([
            'type' => Text::class,
            'name' => 'login',
            'options' => [
                'label' => "Nom du compte  :",
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'login',
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
                'class' => 'btn btn-success',
            ],
        ]);

        $this->setInputFilter((new Factory())->createInputFilter([
            'prenom'         => [ 'required' => true,  ],
            'nom'            => [ 'required' => true,  ],
            'date_naissance' => [ 'required' => false,  ],
            'sexe'           => [ 'required' => false,  ],
            'structure'      => [ 'required' => false,  ],
            'email'          => [ 'required' => true,  ],
            'login'          => [ 'required' => false,  ],
        ]));
    }
}