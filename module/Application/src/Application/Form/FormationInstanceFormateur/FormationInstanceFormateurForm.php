<?php

namespace Application\Form\FormationInstanceFormateur;

use Zend\Form\Element\Button;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\Factory;
use Zend\Validator\Regex;

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
        //volume
        $this->add([
            'type' => Text::class,
            'name' => 'volume',
            'options' => [
                'label' => "Volume horaire dispensé :",
            ],
            'attributes' => [
                'id' => 'volume',
            ],
        ]);
        //montant
        $this->add([
            'type' => Text::class,
            'name' => 'montant',
            'options' => [
                'label' => "Montant horaire :",
            ],
            'attributes' => [
                'id' => 'montant',
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
            'prenom' => [ 'required' => true],
            'nom'    => [ 'required' => true],
            'attachement'    => [ 'required' => false],
            'volume'              => [
                'required' => false,
                'validators' => [
                    [
                        'name' => Regex::class,
                        'options' => [
                            'pattern' => '/[0-9]*\.?[0-9]*/',
                            'messages' => [
                                Regex::NOT_MATCH => "Veuillez saisir une valeur correcte",
                            ],
                            //'break_chain_on_failure' => true,
                        ],
                    ],
                ],
            ],
            'montant'        => [
                'required' => false,
                'validators' => [
                    [
                        'name' => Regex::class,
                        'options' => [
                            'pattern' => '/[0-9]*\.?[0-9]*/',
                            'messages' => [
                                Regex::NOT_MATCH => "Veuillez saisir une valeur correcte",
                            ],
                            //'break_chain_on_failure' => true,
                        ],
                    ],
                ],
            ],
        ]));
    }
}