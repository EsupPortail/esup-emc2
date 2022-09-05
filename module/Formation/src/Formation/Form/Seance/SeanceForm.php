<?php

namespace Formation\Form\Seance;

use Formation\Entity\Db\Seance;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\DateTime;
use Laminas\Form\Element\Number;
use Laminas\Form\Element\Radio;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use Laminas\Validator\Callback;

class SeanceForm extends Form
{

    public function init()
    {
        //type
        $this->add([
                'type' => Select::class,
                'name' => 'type',
                'options' => [
                    'label' => "Type de séance <span class='icon icon-asterisque' title='Champ obligatoire' ></span> :",
                    'label_options' => [ 'disable_html_escape' => true, ],
                    'value_options' => [
                        Seance::TYPE_SEANCE => "Séance de formation",
                        Seance::TYPE_VOLUME => "Volume horaire",
                    ],
                ],
                'attributes' => [
                    'id' => 'type',
                ],
            ]

        );

        //jour
        $this->add([
            'type' => DateTime::class,
            'name' => 'jour',
            'options' => [
                'label' => "Jour de la formation * :",
                'format' => 'd/m/Y',
            ],
            'attributes' => [
                'id' => 'jour',
            ],
        ]);
        //debut
        $this->add([
            'type' => DateTime::class,
            'name' => 'debut',
            'options' => [
                'label' => "Début de la journée * :",
                'format' => 'H:i',
            ],
            'attributes' => [
                'id' => 'debut',
            ],
        ]);
        //fin
        $this->add([
            'type' => DateTime::class,
            'name' => 'fin',
            'options' => [
                'label' => "Fin de la journée * :",
                'format' => 'H:i'
            ],
            'attributes' => [
                'id' => 'fin',
            ],
        ]);
        //number
        $this->add([
            'type' => Number::class,
            'name' => "volume",
            'options' => [
                'label' => "Volume horaire de la formation <span class='icon icon-asterisque' title='Champ obligatoire pour les volumes horaires' ></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'volume',
            ],
        ]);
        //salle
        $this->add([
            'type' => Text::class,
            'name' => "lieu",
            'label_options' => [ 'disable_html_escape' => true, ],
            'options' => [
                'label' => "Lieu de la formation ou lien de visoconférence * :",
            ],
            'attributes' => [
                'id' => 'lieu',
            ],
        ]);
        //submit
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

        //inputFilter
        $this->setInputFilter((new Factory())->createInputFilter([
            'type' => ['required' => true,
                'validators' => [[
                    'name' => Callback::class,
                    'options' => [
                        'messages' => [
                            Callback::INVALID_VALUE => "Une information obligatoire est manquante",
                        ],
                        'callback' => function ($value, $context = []) {
                            if($context['type'] === Seance::TYPE_VOLUME) return $context['volume'] !== '';
                            if($context['type'] === Seance::TYPE_SEANCE) return ($context['jour'] !== '' AND $context['debut'] !== '' AND $context['fin'] !== '');
                            return true;
                        },
                        //'break_chain_on_failure' => true,
                    ],
                ]],],
            'jour' => ['required' => false,

            ],
            'debut' => ['required' => false,],
            'fin' => ['required' => false,],
            'volume' => ['required' => false,],
            'lieu' => ['required' => true,],
        ]));
    }
}