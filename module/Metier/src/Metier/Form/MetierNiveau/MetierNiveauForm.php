<?php

namespace Metier\Form\MetierNiveau;

use Application\Service\Niveau\NiveauServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Select;
use Zend\Form\Form;
use Zend\InputFilter\Factory;
use Zend\Validator\Callback;

class MetierNiveauForm extends Form {
    use NiveauServiceAwareTrait;

    public $niveaux;

    public function init()
    {
        $this->add([
            'type' => Hidden::class,
            'name' => 'metier',
        ]);
        $this->add([
            'type' => Select::class,
            'name' => 'borne_inferieure',
            'options' => [
                'label' => "Niveau le plus bas * :",
                'empty_option' => 'Sélectionner le niveau le plus bas ...',
                'value_options' => $this->getNiveauService()->getNiveauxAsOptions(),
            ],
            'attributes' => [
                'id' => 'borne_inferieure',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);
        $this->add([
            'type' => Select::class,
            'name' => 'borne_superieure',
            'options' => [
                'label' => "Niveau le plus bas * :",
                'empty_option' => 'Sélectionner le niveau le plus élevé ...',
                'value_options' => $this->getNiveauService()->getNiveauxAsOptions(),
            ],
            'attributes' => [
                'id' => 'borne_superieure',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);
        $this->add([
            'type' => Select::class,
            'name' => 'valeur_recommandee',
            'options' => [
                'label' => "Niveau le plus bas * :",
                'empty_option' => 'Sélectionner le niveau recommandé ...',
                'value_options' => $this->getNiveauService()->getNiveauxAsOptions(),
            ],
            'attributes' => [
                'id' => 'valeur_recommandee',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
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
                            $niveau_bas = $this->niveaux[((int) $context["borne_inferieure"])]->getNiveau();
                            $niveau_haut = $this->niveaux[((int) $context["borne_superieure"])]->getNiveau();
                            return ($niveau_bas >= $niveau_haut);
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
                            $niveau_bas = $this->niveaux[((int) $context["borne_inferieure"])]->getNiveau();
                            $niveau_haut = $this->niveaux[((int) $context["borne_superieure"])]->getNiveau();
                            return ($niveau_bas >= $niveau_haut);
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
                            $niveau_bas = $this->niveaux[((int) $context["borne_inferieure"])]->getNiveau();
                            $niveau_haut = $this->niveaux[((int) $context["borne_superieure"])]->getNiveau();
                            $niveau_rec = $this->niveaux[((int) $context["valeur_recommandee"])]->getNiveau();
                            $mini = min($niveau_bas, $niveau_haut);
                            $maxi = max($niveau_bas, $niveau_haut);
                            return (
                                    $maxi >= $niveau_rec
                                AND
                                    $mini <= $niveau_rec
                            );
                        },
                    ],
                ]],
            ],
            'description'           => [ 'required' => false,  ],
        ]));
    }
}