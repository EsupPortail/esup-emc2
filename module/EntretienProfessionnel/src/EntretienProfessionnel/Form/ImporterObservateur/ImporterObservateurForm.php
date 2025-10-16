<?php

namespace EntretienProfessionnel\Form\ImporterObservateur;

use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\File;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class ImporterObservateurForm extends Form
{
    use CampagneServiceAwareTrait;

    public function init(): void
    {
        //CAMPAGNE (SELECT)
        $this->add([
            'name' => 'campagne',
            'type' => Select::class,
            'options' => [
                'label' => "Campagne  <span class='icon icon-obligatoire text-danger' title='Champ obligatoire'></span> : ",
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'label_options' => [ 'disable_html_escape' => true, ],
                'empty_option' => "Sélectionner une campagne ... ",
                'value_options' => $this->getCampagneService()->getCampagnesAsOptions(),
            ],
            'attributes' => [
                'id'                => 'campagne',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ]
        ]);
        //file CSV
        $this->add([
            'type' => File::class,
            'name' => 'fichier',
            'options' => [
                'label' => 'Chaînes hiérachique au format CSV <span class="icon icon-asterisque" title="Champ obligatoire"></span> :',
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
        ]);
        //mode
        $this->add([
            'type' => Select::class,
            'name' => 'mode',
            'options' => [
                'label' => 'Mode  <span class="icon icon-asterisque" title="Champ obligatoire"></span> :',
                'label_options' => [ 'disable_html_escape' => true, ],
                'value_options' => [
                    'preview' => "Prévisualisation",
                    'import' => "Importation",
                ]
            ],
            'attributes' => [
                'id' => 'mode',
            ],
        ]);
        //Submit
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => 'Traiter le ficher',
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ],
        ]);

        $this->setInputFilter((new Factory())->createInputFilter([
            'campagne' => [ 'required' => true ],
            'fichier' => [ 'required' => true,  ],
            'mode'  => ['required' => true, ],
        ]));
    }
}