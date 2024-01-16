<?php

namespace Observation\Form\ObservationInstance;

use Laminas\Form\Element\Button;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use Observation\Service\ObservationType\ObservationTypeServiceAwareTrait;

class ObservationInstanceForm extends Form
{
    use ObservationTypeServiceAwareTrait;

    public function init(): void
    {
        // Type
        $this->add([
            'type' => Select::class,
            'name' => 'observationtype',
            'options' => [
                'label' => "Type d'observation <span class='icon icon-obligatoire' title='Champ obligatoire'></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
                'empty_option' => "Sélectionner un type d'observation ...",
                'value_options' => $this->getObservationTypeService()->getObservationsTypesAsOption(),
            ],
            'attributes' => [
                'id' => 'observationtype',
                'class' => 'bootstrap-selectpicker show-tick',
                'data-live-search' => 'true',
            ],
        ]);
        // Observation
        $this->add([
            'name' => 'observation',
            'type' => Textarea::class,
            'options' => [
                'label' => "Observation  <span class='icon icon-obligatoire' title='Champ obligatoire'></span> : ",
                'label_options' => [ 'disable_html_escape' => true, ],
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'id'                => 'observation',
                'class'             => 'tinymce type2',
            ],
        ]);
        // button
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
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
            'observationtype'           => [ 'required' => true, ],
            'observation'               => [ 'required' => true,  ],
        ]));
    }
}