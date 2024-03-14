<?php

namespace Formation\Form\DemandeExterne;

use Laminas\Form\Element\Button;
use Laminas\Form\Element\Email;
use Laminas\Form\Element\Radio;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use Laminas\Validator\Regex;
use UnicaenApp\Form\Element\Date;

class DemandeExterneForm extends Form
{

    public function init(): void
    {
        //-- Stage demandé -----------------------------

        // libelle
        $this->add([
            'type' => Text::class,
            'name' => 'libelle',
            'options' => [
                'label' => "Intitulé du stage <span class='icon obligatoire' title='Champ obligatoire'></span> :",
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'id' => 'libelle',
            ],
        ]);

        // organisme
        $this->add([
            'type' => Text::class,
            'name' => 'organisme',
            'options' => [
                'label' => "Organisme de formation <span class='icon obligatoire' title='Champ obligatoire'></span> :",
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'id' => 'organisme',
            ],
        ]);

        // organisme
        $this->add([
            'type' => Email::class,
            'name' => 'contact',
            'options' => [
                'label' => "Adresse électronique de contact de l'organisme <span class='icon obligatoire' title='Champ obligatoire'></span> :",
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'id' => 'contact',
            ],
        ]);

        $this->add([
            'type' => Textarea::class,
            'name' => 'pourquoi',
            'options' => [
                'label' => "Pourquoi avez-vous choisi cet organisme ? ",
            ],
            'attributes' => [
                'id' => 'pourquoi',
                'class' => 'tinymce',
            ],
        ]);

        $this->add([
            'type' => Text::class,
            'name' => 'montant',
            'options' => [
                'label' => "Montant des frais associés : ",
            ],
            'attributes' => [
                'id' => 'montant',
                'class' => 'tinymce',
            ],
        ]);

        // lieu
        $this->add([
            'type' => Text::class,
            'name' => 'lieu',
            'options' => [
                'label' => "Lieu (ville) <span class='icon obligatoire' title='Champ obligatoire'></span> :",
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'id' => 'lieu',
            ],
        ]);


        // debut
        $this->add([
            'type' => Date::class,
            'name' => 'debut',
            'options' => [
                'label' => "Date de début <span class='icon obligatoire' title='Champ obligatoire'></span> :",
                'label_options' => ['disable_html_escape' => true,],
//                'format' => HasPeriodeFieldset::format,
            ],
            'attributes' => [
                'id' => 'debut',
            ],
        ]);

        // fin
        $this->add([
            'type' => Date::class,
            'name' => 'fin',
            'options' => [
                'label' => "Date de fin <span class='icon obligatoire' title='Champ obligatoire'></span> :",
                'label_options' => ['disable_html_escape' => true,],
//                'format' => 'd/m/Y'
            ],
            'attributes' => [
                'id' => 'fin',
            ],
        ]);

        // type
        $this->add([
            'type' => Radio::class,
            'name' => 'modalite',
            'options' => [
                'label' => "Modalité de la session de formation  <span class='icon obligatoire' title='Champ obligatoire'></span> :",
                'label_options' => ['disable_html_escape' => true,],
                'value_options' => [
                    "présentiel" => "Je suivrai la formation en présentiel",
                    "distanciel" => "Je suivrai la formation en distanciel",
                ],
            ],
            'attributes' => [
                'id' => 'modalite',
            ],
        ]);
        //-- Motivations -------------------------------

        $this->add([
            'type' => Textarea::class,
            'name' => 'motivation',
            'options' => [
                'label' => "Motivation <span class='icon obligatoire' title='Champ obligatoire'></span> :",
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'id' => 'motivation',
                'class' => 'tinymce',
            ],
        ]);

        //-- Cofinancement -----------------------------

        // type
        $this->add([
            'type' => Radio::class,
            'name' => 'prise-en-charge',
            'options' => [
                'label' => "Votre composante accepte-t-elle de prendre en charge les frais de mission de l’agent ?  <span class='icon obligatoire' title='Champ obligatoire'></span> :",
                'label_options' => ['disable_html_escape' => true,],
                'value_options' => [
                    true => "Oui",
                    false => "Non",
                ],
            ],
            'attributes' => [
                'id' => 'prise-en-charge',
            ],
        ]);

        // libelle
        $this->add([
            'type' => Text::class,
            'name' => 'cofinanceur',
            'options' => [
                'label' => "Nom, prénom du directeur du service, de la composante ou du laboratoire <span class='icon icon-information' title='Champ obligatoire si cofinancenement'></span> :",
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'id' => 'cofinanceur',
            ],
        ]);

        //-- Bouton ----------------------------------------------------------------------------------------------------

        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer',
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ],
        ]);

        //-- Input filter ------------------------------

        $this->setInputFilter((new Factory())->createInputFilter([
            'libelle' => ['required' => true,],
            'organisme' => ['required' => true,],
            'contact' => ['required' => true,],
            'pourquoi' => ['required' => false,],
            'montant' => [
                'required' => true,
                'validators' => [
                    [
                        'name' => Regex::class,
                        'options' => [
                            'pattern' => '/^\d+(\.\d+)?$/',
                            'messages' => [
                                Regex::NOT_MATCH => "Veuillez saisir une valeur numérique",
                            ],
                            //'break_chain_on_failure' => true,
                        ],
                    ],
                ],
            ],
            'lieu' => ['required' => true,],
            'debut' => ['required' => true,],
            'fin' => ['required' => true,],
            'modalite' => ['required' => true,],

            'motivation' => ['required' => true,],

            'prise-en-charge' => ['required' => true,],
            'cofinanceur' => ['required' => false,],
        ]));
    }
}