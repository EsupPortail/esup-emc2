<?php

namespace FicheMetier\Form\FicheMetierImportation;

use FicheMetier\Controller\ImportController;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\File;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use Referentiel\Service\Referentiel\ReferentielServiceAwareTrait;

class FicheMetierImportationForm extends Form {


    use ReferentielServiceAwareTrait;

    public function init(): void
    {
        //file CSV
        $this->add([
            'type' => File::class,
            'name' => 'fichier',
            'options' => [
                'label' => 'Fiche métier au format CSV <span class="icon icon-asterisque" title="Champ obligatoire"></span> :',
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'accept' => '.csv',
            ]
        ]);
        //referentiel
        $this->add([
            'type' => Select::class,
            'name' => 'referentiel',
            'options' => [
                'label' => 'Référentiel  <span class="icon icon-asterisque" title="Champ obligatoire"></span> :',
                'label_options' => [ 'disable_html_escape' => true, ],
                'empty_option' => 'Sélectionnez un référentiel',
                'value_options' => $this->getReferentielService()->getReferentielsAsOptions(),
            ],
            'attributes' => [
                'id' => 'referentiel',
            ],
        ]);
        //referentiel
        $this->add([
            'type' => Select::class,
            'name' => 'format',
            'options' => [
                'label' => 'Format du fichier  <span class="icon icon-asterisque" title="Champ obligatoire"></span> :',
                'label_options' => [ 'disable_html_escape' => true, ],
                'empty_option' => 'Sélectionnez un format',
                'value_options' => [
                    ImportController::FORMAT_REFERENS3 => 'Format REFERENS3',
                    ImportController::FORMAT_RMFP => 'Format RMFP',
                ],
            ],
            'attributes' => [
                'id' => 'format',
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
            'fichier' => [ 'required' => true,  ],
            'format'  => ['required' => true, ],
            'mode'  => ['required' => true, ],
        ]));
    }
}