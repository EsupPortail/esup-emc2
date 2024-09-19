<?php

namespace Formation\Form\Session;

use Formation\Entity\Db\Session;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\DateTime;
use Laminas\Form\Element\Number;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use Laminas\Validator\Regex;
use UnicaenApp\Form\Element\Date;

class SessionForm extends Form
{

    public function init(): void
    {
        /** Complement */
        $this->add([
            'name' => 'description',
            'type' => Textarea::class,
            'options' => [
                'label' => 'Description :',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'id' => 'description',
                'class' => 'tinymce',
            ],
        ]);
        /** Taille liste principale */
        $this->add([
            'type' => Number::class,
            'name' => 'principale',
            'options' => [
                'label' => "Nombre de place en liste principale * :",
            ],
            'attributes' => [
                'id' => 'principale',
            ],
        ]);
        /** Taille liste complementaire */
        $this->add([
            'type' => Number::class,
            'name' => 'complementaire',
            'options' => [
                'label' => "Nombre de place en liste complémentaire * :",
            ],
            'attributes' => [
                'id' => 'complementaire',
            ],
        ]);
        /** Cloture Inscription */
        //jour
        $this->add([
            'type' => Date::class,
            'name' => 'date_cloture_inscription',
            'options' => [
                'label' => "Date de clôture des inscriptions :",
                'label_options' => [ 'disable_html_escape' => true, ],
                'format' => 'd/m/Y',
            ],
            'attributes' => [
                'id' => 'date_cloture_inscription',
            ],
        ]);
        /** Taille liste complementaire */
        $this->add([
            'type' => Select::class,
            'name' => 'type',
            'options' => [
                'label' => "Type de formation :",
                'empty_option' => "Sélectionner un type ...",
                'value_options' => [
                    Session::TYPE_INTERNE => 'Formation interne',
                    Session::TYPE_EXTERNE => 'Formation externe',
                    Session::TYPE_REGIONALE => 'Formation régionale',
                ],
            ],
            'attributes' => [
                'id' => 'type',
            ],
        ]);
        /** Taille liste complementaire */
        $this->add([
            'type' => Select::class,
            'name' => 'inscription',
            'options' => [
                'label' => "Inscriptions directes par les agents :",
                'empty_option' => "Sélectionner un type d'inscription ...",
                'value_options' => [
                    false => 'Non, les agents sont inscrits par les gestionnaires de formation',
                    true => 'Oui, les agents peuvent s\'inscrire directement dans l\'application',
                ],
            ],
            'attributes' => [
                'id' => 'inscription',
            ],
        ]);
        /** Taille liste complementaire */
        $this->add([
            'type' => Text::class,
            'name' => 'lieu',
            'options' => [
                'label' => "Lieu de la formation * :",
            ],
            'attributes' => [
                'id' => 'lieu',
            ],
        ]);
        //cout ht
        $this->add([
            'type' => Text::class,
            'name' => 'cout_ht',
            'options' => [
                'label' => "Coût HT :",
            ],
            'attributes' => [
                'id' => 'cout_ht',
            ],
        ]);
        //cout ht
        $this->add([
            'type' => Text::class,
            'name' => 'cout_ttc',
            'options' => [
                'label' => "Coût TTC :",
            ],
            'attributes' => [
                'id' => 'cout_ttc',
            ],
        ]);
        //cout ht
        $this->add([
            'type' => Text::class,
            'name' => 'cout_vacation',
            'options' => [
                'label' => "Coût vacation :",
            ],
            'attributes' => [
                'id' => 'cout_vacation',
            ],
        ]);
        //recette ttc
        $this->add([
            'type' => Text::class,
            'name' => 'recette_ttc',
            'options' => [
                'label' => "Recette TTC :",
            ],
            'attributes' => [
                'id' => 'recette_ttc',
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
            'description' => ['required' => false,],
            'principale' => ['required' => true,],
            'complementaire' => ['required' => true,],
            'date_cloture_inscription' => ['required' => false,],
            'inscription' => ['required' => true,],
            'lieu' => ['required' => true,],
            'type' => ['required' => true,],
            'cout_vacation' => [
                'required' => false,
                'validators' => [
                    [
                        'name' => Regex::class,
                        'options' => [
                            'pattern' => '/^\d+(\.\d+)?$/',
                            'messages' => [
                                Regex::NOT_MATCH => "Veuillez saisir une valeur correcte",
                            ],
                            //'break_chain_on_failure' => true,
                        ],
                    ],
                ],
            ],
            'cout_ht' => [
                'required' => false,
                'validators' => [
                    [
                        'name' => Regex::class,
                        'options' => [
                            'pattern' => '/^\d+(\.\d+)?$/',
                            'messages' => [
                                Regex::NOT_MATCH => "Veuillez saisir une valeur correcte",
                            ],
                            //'break_chain_on_failure' => true,
                        ],
                    ],
                ],
            ],
            'cout_ttc' => [
                'required' => false,
                'validators' => [
                    [
                        'name' => Regex::class,
                        'options' => [
                            'pattern' => '/^\d+(\.\d+)?$/',
                            'messages' => [
                                Regex::NOT_MATCH => "Veuillez saisir une valeur correcte",
                            ],
                            //'break_chain_on_failure' => true,
                        ],
                    ],
                ],
            ],
            'recette_ttc' => [
                'required' => false,
                'validators' => [
                    [
                        'name' => Regex::class,
                        'options' => [
                            'pattern' => '/^\d+(\.\d+)?$/',
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