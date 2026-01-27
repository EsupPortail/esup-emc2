<?php

namespace EntretienProfessionnel\Form\Campagne;

use DateTime;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Date;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use UnicaenAutoform\Service\Formulaire\FormulaireServiceAwareTrait;

class CampagneForm extends Form
{
    use CampagneServiceAwareTrait;
    use FormulaireServiceAwareTrait;

    public function init(): void
    {
        //ANNEE (SELECT)
        $current = (new DateTime())->format('Y');
        $array = [];
        for ($delta = -1 ; $delta <= 5 ; $delta++) {
            $string = (((int) $current) - ($delta)) . "/" . (((int) $current) - ($delta-1));
            $array[$string] = $string;
        }
        $this->add([
            'name' => 'annee',
            'type' => Select::class,
            'options' => [
                'label' => "Année de la campagne <span class='icon icon-obligatoire' title='Champ obligatoire'></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
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
            'type' => Date::class,
            'options' => [
                'label' => "Date de début de la campagne <span class='icon icon-obligatoire' title='Champ obligatoire'></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
                'label_attributes' => [
                    'class' => 'control-label',
                ],
//                'format' => 'd/m/Y'
            ],
            'attributes' => [
                'id'                => 'date_debut',
            ]
        ]);
        //FIN (DATE)
        $this->add([
            'name' => 'date_fin',
            'type' => Date::class,
            'options' => [
                'label' => "Date de fin de la campagne <span class='icon icon-obligatoire' title='Champ obligatoire'></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
                'label_attributes' => [
                    'class' => 'control-label',
                ],
//                'format' => 'd/m/Y'
            ],
            'attributes' => [
                'id'                => 'date_fin',
            ]
        ]);
        //DEBUT (DATE)
        $this->add([
            'name' => 'date_circulaire',
            'type' => Date::class,
            'options' => [
                'label' => "Date de la circulaire : ",
                'label_attributes' => [
                    'class' => 'control-label',
                ],
//                'format' => 'd/m/Y'
            ],
            'attributes' => [
                'id'                => 'date_circulaire',
            ]
        ]);
        //DEBUT (DATE)
        $this->add([
            'name' => 'date_en_poste',
            'type' => Date::class,
            'options' => [
                'label' => "Date pour prise de poste <span class='icon icon-information' title=\"L'agent·e doit être en poste à la date donnée\"></span> <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => [ 'disable_html_escape' => true, ],
                'label_attributes' => [
                    'class' => 'control-label',
                ],
//                'format' => 'd/m/Y'
            ],
            'attributes' => [
                'id'                => 'date_en_poste',
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

        /** Gestion du CREP et du CREF ********************************************************************************/

        $this->add([
            'name' => 'formulaire_crep',
            'type' => Select::class,
            'options' => [
                'label' => 'Formulaire pour la partie CREP <span class="icon icon-obligatoire"></span> : ',
                'label_options' => [ 'disable_html_escape' => true, ],
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'empty_option' => "Sélectionner un formulaire ... ",
                'value_options' => $this->getFormulaireService()->getFormulairesAsOptions(),
            ],
            'attributes' => [
                'id'                => 'formulaire_crep',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ]
        ]);
        $this->add([
            'name' => 'formulaire_cref',
            'type' => Select::class,
            'options' => [
                'label' => 'Formulaire pour la partie CREF  <span class="icon icon-obligatoire"></span> : ',
                'label_options' => [ 'disable_html_escape' => true, ],
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'empty_option' => "Sélectionner un formulaire ... ",
                'value_options' => $this->getFormulaireService()->getFormulairesAsOptions(),
            ],
            'attributes' => [
                'id'                => 'formulaire_cref',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ]
        ]);

        /** Autre *****************************************************************************************************/

        //SUBMIT
        $this->add([
            'type' => Button::class,
            'name' => 'bouton',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer',
                'label_options' => [ 'disable_html_escape' => true, ],
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
            'date_en_poste' =>   [  'required' => true,   ],
            'date_circulaire' =>   [  'required' => false,   ],
            'precede' =>    [  'required' => false,  ],
            'formulaire_crep' =>    [  'required' => true,  ],
            'formulaire_cref' =>    [  'required' => true,  ],
        ]));
    }
}