<?php

namespace Application\Form\SelectionCompetence;

use Element\Service\Competence\CompetenceServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class SelectionCompetenceForm extends Form {
    use CompetenceServiceAwareTrait;

    public function init() {

        //select multiple avec groupe
        $this->add([
            'type' => Select::class,
            'name' => 'competences',
            'options' => [
                'label' => "Compétences associées :",
                'empty_option' => "Sélectionner la ou les compétences ...",
//                'value_options' => $this->getCompetenceService()->getCompetencesAsGroupOptions(),
            ],
            'attributes' => [
                'id' => 'competences',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
                'multiple'          => 'multiple',
            ],
        ]);
        //button
        $this->add([
            'type' => Button::class,
            'name' => 'enregistrer',
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
        //inputfilter
        $this->setInputFilter((new Factory())->createInputFilter([
            'competences'               => [ 'required' => false,  ],
        ]));
    }
}