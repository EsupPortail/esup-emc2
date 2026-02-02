<?php

namespace FicheMetier\Form\MissionPrincipale;

use Carriere\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;
use Carriere\Service\Niveau\NiveauServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use Laminas\Validator\Callback;
use Referentiel\Service\Referentiel\ReferentielServiceAwareTrait;

class MissionPrincipaleForm extends Form
{
    use FamilleProfessionnelleServiceAwareTrait;
    use NiveauServiceAwareTrait;
    use ReferentielServiceAwareTrait;

    public array $niveaux = [];

    public function init(): void
    {
        //libelle *
        $this->add([
            'type' => Text::class,
            'name' => 'libelle',
            'options' => [
                'label' => "Libellé <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'id' => 'libelle',
            ],
        ]);
        //description
        $this->add([
            'name' => 'description',
            'type' => Textarea::class,
            'options' => [
                'label' => 'Description  : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'id'                => 'description',
                'class'             => 'tinymce',
            ],
        ]);
        //famille professionnelle
        $this->add([
            'type' => Select::class,
            'name' => 'familleprofessionnelle',
            'options' => [
                'label' => "Famille·s professionnelle·s <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => [ 'disable_html_escape' => true, ],
                'empty_option' => "Sélectionner une famille professionnelle",
                'value_options' =>
                    $this->getFamilleProfessionnelleService()->getFamillesProfessionnellesAsOptions(),
            ],
            'attributes' => [
                'id' => 'familleprofessionnelle',
                'class' => 'bootstrap-selectpicker show-tick',
                'data-live-search' => 'true',
                'multiple' => 'multiple',
            ],
        ]);
        //Niveau de carrière
        $this->add([
            'type' => Select::class,
            'name' => 'borne_inferieure',
            'options' => [
                'label' => "Niveau le plus élevé :",
                'label_options' => ['disable_html_escape' => true,],
                'empty_option' => 'Sélectionner le niveau le plus élevé ...',
                'value_options' => $this->getNiveauService()->getNiveauxAsOptions(),
            ],
            'attributes' => [
                'id' => 'borne_inferieure',
                'class' => 'bootstrap-selectpicker show-tick',
                'data-live-search' => 'true',
            ],
        ]);
        $this->add([
            'type' => Select::class,
            'name' => 'borne_superieure',
            'options' => [
                'label' => "Niveau le plus bas :",
                'label_options' => ['disable_html_escape' => true,],
                'empty_option' => 'Sélectionner le niveau le plus bas ...',
                'value_options' => $this->getNiveauService()->getNiveauxAsOptions(),
            ],
            'attributes' => [
                'id' => 'borne_superieure',
                'class' => 'bootstrap-selectpicker show-tick',
                'data-live-search' => 'true',
            ],
        ]);
        //referentiel
        $this->add([
            'type' => Select::class,
            'name' => 'referentiel',
            'options' => [
                'label' => "Référentiel <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => [ 'disable_html_escape' => true, ],
                'empty_option' => 'Sélectionner un référentiel ...',
                'value_options' => $this->getReferentielService()->getReferentielsAsOptions(),
            ],
            'attributes' => [
                'id' => 'referentiel',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);
        //idOrig
        $this->add([
            'type' => Text::class,
            'name' => 'identifiant',
            'options' => [
                'label' => "Identifiant dans le référentiel <span class='icon icon-information' title='Si aucun identifiant de renseigné, EMC2 donnera une valeur numérique à la mission.'></span>:",
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'identifiant',
            ],
        ]);
        $this->add([
            'type' => Button::class,
            'name' => 'submit',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer',
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ],
        ]);

        //inputFIlter
        $this->setInputFilter((new Factory())->createInputFilter([
            'libelle' => ['required' => true,],
            'description' => ['required' => false,],
            'familleprofessionnelle' => ['required' => true,],
            'borne_inferieure' => [
                'required' => false,
//                'validators' => [[
//                    'name' => Callback::class,
//                    'options' => [
//                        'messages' => [
//                            Callback::INVALID_VALUE => "La borne supérieure est incompatible avec la borne inférieure",
//                        ],
//                        'callback' => function ($value, $context = []) {
//                            $niveau_bas = $this->niveaux[((int) $context["borne_inferieure"])]->getNiveau();
//                            $niveau_haut = $this->niveaux[((int) $context["borne_superieure"])]->getNiveau();
//                            return ($niveau_bas <= $niveau_haut);
//                        },
//                    ],
//                ]],
            ],
            'borne_superieure' => [
                'required' => false,
                'validators' => [[
                    'name' => Callback::class,
                    'options' => [
                        'messages' => [
                            Callback::INVALID_VALUE => "La borne inférieure est incompatible avec la borne supérieure",
                        ],
                        'callback' => function ($value, $context = []) {
                            $niveau_bas = (isset($context["borne_inferieure"]) and $context["borne_inferieure"] !== '') ? $this->niveaux[((int)$context["borne_inferieure"])]->getNiveau() : null;
                            if ($niveau_bas === null) return true;
                            $niveau_haut = (isset($context["borne_superieure"]) and $context["borne_superieure"] !== '') ? $this->niveaux[((int)$context["borne_superieure"])]->getNiveau() : null;
                            if ($niveau_haut === null) return true;
                            return ($niveau_bas <= $niveau_haut);
                        },
                    ],
                ]],
            ],
            'referentiel' => ['required' => true,],
            'identifiant' => ['required' => false,],
        ]));
    }
}
