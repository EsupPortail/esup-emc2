<?php

namespace Formation\Form\InscriptionFrais;

use Laminas\Form\Element\Button;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use Laminas\Validator\Regex;

class InscriptionFraisForm extends Form
{

    public function init(): void
    {
        //repas
        $this->add([
            'type' => Text::class,
            'name' => 'repas',
            'options' => [
                'label' => "Frais de repas :",
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
                'label' => "Frais d'hébergement :",
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
                'label' => "Frais de transport :",
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
            'repas' => [
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
            'hebergement' => [
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
            'transport' => [
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