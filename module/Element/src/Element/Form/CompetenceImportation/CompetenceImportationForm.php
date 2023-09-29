<?php

namespace Element\Form\CompetenceImportation;

use Element\Service\CompetenceReferentiel\CompetenceReferentielServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\File;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class CompetenceImportationForm extends Form {
    use CompetenceReferentielServiceAwareTrait;

    public function init(): void
    {
        //file CSV
        $this->add([
            'type' => File::class,
            'name' => 'fichier',
            'options' => [
                'label' => 'Fichier CSV contenant les compétences <span class="icon icon-asterisque" title="Champ obligatoire"></span> :',
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
        ]);
        //referentiel
        $this->add([
            'type' => Select::class,
            'name' => 'referentiel',
            'options' => [
                'label' => "Référentiel de compétence :",
                'empty_option' => "Sélectionner un référentiel pour la compétence ...",
                'value_options' => $this->getCompetenceReferentielService()->getCompetencesReferentielsAsOptions(),
            ],
            'attributes' => [
                'id' => 'referentiel',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
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
            'referentiel'  => ['required' => true, ],
            'mode'  => ['required' => true, ],
        ]));
    }
}