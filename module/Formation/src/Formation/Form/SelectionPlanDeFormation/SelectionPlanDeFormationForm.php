<?php

namespace Formation\Form\SelectionPlanDeFormation;

use Formation\Service\PlanDeFormation\PlanDeFormationServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class SelectionPlanDeFormationForm extends Form
{
    use PlanDeFormationServiceAwareTrait;

    public function init(): void
    {

        //select multiple avec groupe
        $this->add([
            'type' => Select::class,
            'name' => 'plan',
            'options' => [
                'label' => "Plan de formation :",
                'empty_option' => "Sélectionner un plan de formation ...",
                'value_options' => $this->getPlanDeFormationService()->getPlansDeFormationAsOption(),
            ],
            'attributes' => [
                'id' => 'plan',
                'class' => 'bootstrap-selectpicker show-tick',
                'data-live-search' => 'true',
            ],
        ]);
        //button
        $this->add([
            'type' => Button::class,
            'name' => 'enregistrer',
            'options' => [
                'label' => 'Sélectionner',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-success',
            ],
        ]);
        //inputfilter
        $this->setInputFilter((new Factory())->createInputFilter([
            'plan' => ['required' => false,],
        ]));
    }
}