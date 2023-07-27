<?php

namespace Formation\Form\SessionParametre;


use Laminas\Form\Element\Button;
use Laminas\Form\Element\Radio;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class SessionParametreForm extends Form {


    public function init(): void
    {
        $this->add([
            'type' => Radio::class,
            'name' => 'mail',
            'options' => [
                'label' => "Activation de l'envoi des courriers électronique :",
                'label_options' => [ 'disable_html_escape' => true, ],
                'value_options' => [
                    true => "Oui",
                    false => "Non",
                ],
            ],
            'attributes' => [
                'id' => 'mail',
            ],
        ]);
        $this->add([
            'type' => Radio::class,
            'name' => 'evenement',
            'options' => [
                'label' => "Gestion automatique de la session :",
                'label_options' => [ 'disable_html_escape' => true, ],
                'value_options' => [
                    true => "Oui",
                    false => "Non",
                ],
            ],
            'attributes' => [
                'id' => 'evenement',
            ],
        ]);
        $this->add([
            'type' => Radio::class,
            'name' => 'enquete',
            'options' => [
                'label' => "Activation de l'enquête de retour :",
                'label_options' => [ 'disable_html_escape' => true, ],
                'value_options' => [
                    true => "Oui",
                    false => "Non",
                ],
            ],
            'attributes' => [
                'id' => 'enquete',
            ],
        ]);
        //submit
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer les paramètres',
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ],
        ]);

        //filter
        $this->setInputFilter((new Factory())->createInputFilter([
            'mail' => ['required' => true,],
            'evenement' => ['required' => false,],
            'enquete' => ['required' => false,],
        ]));
    }
}