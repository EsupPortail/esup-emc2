<?php

namespace Formation\Form\SelectionGestionnaire;

use Formation\Service\Formation\FormationServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class SelectionGestionnaireForm extends Form
{
    use FormationServiceAwareTrait;

    public function init(): void
    {
        //gestionnaire
        $this->add([
            'type' => Select::class,
            'name' => 'gestionnaires',
            'options' => [
                'label' => "Gestionnaire·s associé·es :",
                'empty_option' => "Sélectionner les gestionnaires associé·es ...",
                'value_options' => $this->getFormationService()->getGestionnaires(),
            ],
            'attributes' => [
                'id' => 'gestionnaires',
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
                'id' => 'enregistrer',
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ],
        ]);
        //inputfilter
        $this->setInputFilter((new Factory())->createInputFilter([
            'gestionnaires' => ['required' => false,],
        ]));
    }
}