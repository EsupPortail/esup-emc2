<?php

namespace EntretienProfessionnel\Form\Campagne;

use DateTime;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Date;
use Zend\Form\Element\Select;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class CampagneForm extends Form
{
    use CampagneServiceAwareTrait;

    public function init()
    {
        //ANNEE (SELECT)
        $current = (new DateTime())->format('Y');
        $array = [];
        for ($delta = -5 ; $delta <= 5 ; $delta++) {
            $string = (((int) $current) - ($delta)) . "/" . (((int) $current) - ($delta-1));
            $array[$string] = $string;
        }
        $this->add([
            'name' => 'annee',
            'type' => Select::class,
            'options' => [
                'label' => 'Année de la campagne * : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'empty_option' => "Sélectionner une année ... ",
                'value_options' => $array,
            ],
            'attributes' => [
                'id'                => 'annee',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ]
        ]);
        //DEBUT (DATE)
        $this->add([
            'name' => 'date_debut',
            'type' => DateTime::class,
            'options' => [
                'label' => 'Date de début de la campagne * : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'format' => 'd/m/Y'
            ],
            'attributes' => [
                'id'                => 'date_debut',
            ]
        ]);
        //FIN (DATE)
        $this->add([
            'name' => 'date_fin',
            'type' => DateTime::class,
            'options' => [
                'label' => 'Date de fin de la campagne * : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'format' => 'd/m/Y'
            ],
            'attributes' => [
                'id'                => 'date_fin',
            ]
        ]);
        //DEBUT (DATE)
        $this->add([
            'name' => 'date_circulaire',
            'type' => DateTime::class,
            'options' => [
                'label' => 'Date de la circulaire : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'format' => 'd/m/Y'
            ],
            'attributes' => [
                'id'                => 'date_circulaire',
            ]
        ]);
        //PRECEDE (SELECT)
        $this->add([
            'name' => 'precede',
            'type' => Select::class,
            'options' => [
                'label' => 'Précédente campagne : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'empty_option' => "Sélectionner une campagne ... ",
                'value_options' => $this->getCampagneService()->getCampagnesAsOptions(),
            ],
            'attributes' => [
                'id'                => 'precede',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ]
        ]);
        //SUBMIT
        $this->add([
            'type' => Button::class,
            'name' => 'bouton',
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
            'annee' => [       'required' => true,   ],
            'date_debut' => [  'required' => true,   ],
            'date_fin' =>   [  'required' => true,   ],
            'date_circulaire' =>   [  'required' => false,   ],
            'precede' =>    [  'required' => false,  ],
        ]));
    }
}