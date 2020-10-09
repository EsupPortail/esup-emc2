<?php

namespace Application\Form\FormationInstanceFrais;

use Zend\Form\Element\Button;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\I18n\Validator\IsFloat;
use Zend\InputFilter\Factory;
use Zend\Validator\Regex;

class FormationInstanceFraisForm extends Form {

    public function init()
    {
        //repas
        $this->add([
            'type' => Text::class,
            'name' => 'repas',
            'options' => [
                'label' => "Fais de repas :",
            ],
            'attributes' => [
                'id' => 'repas',
            ],
        ]);
        //hébergement
        $this->add([
            'type' => Text::class,
            'name' => 'hebergement',
            'options' => [
                'label' => "Fais d'hébergement :",
            ],
            'attributes' => [
                'id' => 'hebergement',
            ],
        ]);
        //transport
        $this->add([
            'type' => Text::class,
            'name' => 'transport',
            'options' => [
                'label' => "Fais de transport :",
            ],
            'attributes' => [
                'id' => 'transport',
            ],
        ]);
        //Bouton
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
            'repas'              => [
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
            'hebergement'        => [
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
            'transport'          => [
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