<?php

namespace Formation\Form\ActionCoutPrevisionnel;

use Formation\Service\Formation\FormationServiceAwareTrait;
use Formation\Service\PlanDeFormation\PlanDeFormationServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Number;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class ActionCoutPrevisionnelForm extends Form
{
    use FormationServiceAwareTrait;
    use PlanDeFormationServiceAwareTrait;

    public function init(): void
    {
        //action
        $this->add([
            'type' => Select::class,
            'name' => 'action',
            'options' => [
                'label' => "Action :",
                'empty_option' => 'Sélectionner une action de formation ...',
                'value_options' => $this->getFormationService()->getFormationsAsOptions(),
            ],
            'attributes' => [
                'id'                => 'action',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);
        //plan
        $this->add([
            'type' => Select::class,
            'name' => 'plan',
            'options' => [
                'label' => "Plan de formation à associer :",
                'empty_option' => 'Aucun plan',
                'value_options' => $this->getPlanDeFormationService()->getPlansDeFormationAsOption(),
            ],
            'attributes' => [
                'id'                => 'plan',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);
        //cout
        $this->add([
            'type' => Number::class,
            'name' => 'cout',
            'options' => [
                'label' => "Coût par session <span class='icon icon-obligatoire'></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'cout',
            ],
        ]);
        //nb
        $this->add([
            'type' => Number::class,
            'name' => 'nombre',
            'options' => [
                'label' => "Nombre de sessions prévues <span class='icon icon-obligatoire'></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'nombre',
            ],
        ]);
        //button
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer',
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-success',
            ],
        ]);
        //inputfilter
        $this->setInputFilter((new Factory())->createInputFilter([
            'action'               => [ 'required' => true,  ],
            'plan'                 => [ 'required' => false,  ],
            'cout'                 => [ 'required' => true,  ],
            'nombre'               => [ 'required' => true,  ],
        ]));
    }
}