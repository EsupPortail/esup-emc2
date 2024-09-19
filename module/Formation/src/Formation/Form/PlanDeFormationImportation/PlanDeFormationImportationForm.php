<?php

namespace Formation\Form\PlanDeFormationImportation;

use Formation\Service\PlanDeFormation\PlanDeFormationServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\File;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class PlanDeFormationImportationForm extends Form {
    use PlanDeFormationServiceAwareTrait;

    public function init(): void
    {
        //plan de formation
        $this->add([
            'type' => Select::class,
            'name' => 'plan-de-formation',
            'options' => [
                'label' => 'Plan de formation  <span class="icon icon-asterisque" title="Champ obligatoire"></span> :',
                'label_options' => [ 'disable_html_escape' => true, ],
                'empty_option' => "Sélectionner un plan de formation ...",
                'value_options' => $this->getPlanDeFormationService()->getPlansDeFormationAsOption(),
            ],
            'attributes' => [
                'id' => 'plan-de-formation',
            ],
        ]);

        //file CSV
        $this->add([
            'type' => File::class,
            'name' => 'fichier',
            'options' => [
                'label' => 'Tableaux des formations au format CSV <span class="icon icon-asterisque" title="Champ obligatoire"></span> :',
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
        //annee
//        $this->add([
//            'type' => Select::class,
//            'name' => 'annee',
//            'options' => [
//                'label' => 'Année asscocié  <span class="icon icon-asterisque" title="Champ obligatoire"></span> :',
//                'label_options' => [ 'disable_html_escape' => true, ],
//                'value_options' => [
//                    '' => "Prévisualisation",
//                    'import' => "Importation",
//                ]
//            ],
//            'attributes' => [
//                'id' => 'annee',
//            ],
//        ]);

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
                'class' => 'btn btn-success',
            ],
        ]);

        $this->setInputFilter((new Factory())->createInputFilter([
            'fichier' => [ 'required' => true,  ],
            'mode'  => ['required' => true, ],
        ]));
    }
}