<?php

namespace Application\Form\Agent;

use UnicaenApp\Form\Element\Date;
use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class AgentForm extends Form {

    public function init()
    {
        // prenom
        $this->add([
            'type' => Text::class,
            'name' => 'prenom',
            'options' => [
                'label' => "Prénom* :",
            ],
            'attributes' => [
                'id' => 'prenom',
            ],
        ]);
        // nom
        $this->add([
            'type' => Text::class,
            'name' => 'nom',
            'options' => [
                'label' => "Nom* :",
            ],
            'attributes' => [
                'id' => 'nom',
            ],
        ]);
        // date de début
        $this->add([
            'type' => Date::class,
            'name' => 'dateDebut',
            'options' => [
                'label' => "Date de début* :",
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'format' => 'd/m/Y'
            ],
            'attributes' => [
                'id' => 'dateDebut',
                'class' => 'form-control'
            ],
        ]);

        // date de fin
        $this->add([
            'type' => Date::class,
            'name' => 'dateFin',
            'options' => [
                'label' => "Date de fin :",
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'format' => 'd/m/Y'
            ],
            'attributes' => [
                'id' => 'dateFin',
                'class' => 'form-control'
            ],
        ]);
        // quotite
        $this->add([
            'type' => Select::class,
            'name' => 'quotite',
            'options' => [
                'label' => "Quotite travaillée* :",
                'value_options' => [
                    '100' => '100 % ',
                    '90'  => ' 90 % ',
                    '80'  => ' 80 % ',
                    '70'  => ' 70 % ',
                    '60'  => ' 60 % ',
                    '50'  => ' 50 % ',
                    '40'  => ' 40 % ',
                    '30'  => ' 30 % ',
                    '20'  => ' 20 % ',
                ],
            ],
            'attributes' => [
                'id' => 'quotite',
            ],
        ]);
        // button
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer l\'activité',
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
            'nom' => [
                'required' => true,
            ],
            'prenom' => [
                'required' => true,
            ],
            'quotite' => [
                'required' => true,
            ],
            'dateDebut' => [
                'required' => true,
            ],
            'dateFin' => [
                'required' => false,
            ],
        ]));
    }
}