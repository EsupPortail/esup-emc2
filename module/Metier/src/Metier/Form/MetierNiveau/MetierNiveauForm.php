<?php

namespace Metier\Form\MetierNiveau;

use Zend\Form\Element\Button;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Number;
use Zend\Form\Form;
use Zend\InputFilter\Factory;
use Zend\Validator\Callback;

class MetierNiveauForm extends Form {

    public function init()
    {
        $this->add([
            'type' => Hidden::class,
            'name' => 'metier',
        ]);
        $this->add([
            'type' => Number::class,
            'name' => 'borne_inferieure',
            'options' => [
                'label' => "Niveau le plus bas * :",
            ],
            'attributes' => [
                'id' => 'borne_inferieure',
                'min'  => '1',
                'max'  => '5',
                'step' => '1',
            ],
        ]);
        $this->add([
            'type' => Number::class,
            'name' => 'borne_superieure',
            'options' => [
                'label' => "Niveau le plus élévé * :",
            ],
            'attributes' => [
                'id' => 'borne_superieure',
                'min'  => '1',
                'max'  => '5',
                'step' => '1',
            ],
        ]);
        $this->add([
            'type' => Number::class,
            'name' => 'valeur_recommandee',
            'options' => [
                'label' => "Valeur recommandée * :",
            ],
            'attributes' => [
                'id' => 'valeur_recommandee',
                'min'  => '1',
                'max'  => '5',
                'step' => '1',
            ],
        ]);
        // description
        $this->add([
            'name' => 'description',
            'type' => 'textarea',
            'options' => [
                'label' => 'Description : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'class' => 'type2 form-control',
            ]
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

        $this->setInputFilter((new Factory())->createInputFilter([
            'metier'                => [ 'required' => true,  ],
            'borne_inferieure'      => [
                'required' => true,
                'validators' => [[
                    'name' => Callback::class,
                    'options' => [
                        'messages' => [
                            Callback::INVALID_VALUE => "La borne inférieure est incompatible avec la borne supérieure",
                        ],
                        'callback' => function ($value, $context = []) {
                            return ((int) $context["borne_inferieure"]) >= ((int) $context["borne_superieure"]);
                        },
                    ],
                ]],
            ],
            'borne_superieure'      => [
                'required' => true,
                'validators' => [[
                    'name' => Callback::class,
                    'options' => [
                        'messages' => [
                            Callback::INVALID_VALUE => "La borne supérieure est incompatible avec la borne inférieure",
                        ],
                        'callback' => function ($value, $context = []) {
                            return ((int) $context["borne_inferieure"]) >= ((int) $context["borne_superieure"]);
                        },
                    ],
                ]],
            ],
            'valeur_recommandee'    => [
                'required' => true,
                'validators' => [[
                    'name' => Callback::class,
                    'options' => [
                        'messages' => [
                            Callback::INVALID_VALUE => "La valeur recommandée doit être comprise entre la borne inférieure et la borne supérieure",
                        ],
                        'callback' => function ($value, $context = []) {
                            $mini = min(((int) $context["borne_inferieure"]), ((int) $context["borne_superieure"]));
                            $maxi = max(((int) $context["borne_inferieure"]), ((int) $context["borne_superieure"]));
                            return (
                                    $maxi>= ((int) $context["valeur_recommandee"])
                                AND
                                    $mini <= ((int) $context["valeur_recommandee"])
                            );
                        },
                    ],
                ]],
            ],
            'description'           => [ 'required' => false,  ],
        ]));
    }
}