<?php

namespace EntretienProfessionnel\Form\Observation;

use Laminas\Form\Element\Button;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;

class ObservationForm extends Form {

    public function init(): void
    {
        // entretien
        $this->add([
            'name' => 'obs-entretien',
            'type' => Textarea::class,
            'options' => [
                'label' => "Observations sur l'entretien : ",
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'class' => 'type2 form-control obs-entretien',
            ]
        ]);
        // perspective
        $this->add([
            'name' => 'obs-perspective',
            'type' => Textarea::class,
            'options' => [
                'label' => "Observations sur les perspectives de carrière et de mobilité: ",
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'class' => 'type2 form-control obs-perspective',
            ]
        ]);
        // button
        $this->add([
            'type' => Button::class,
            'name' => 'submit',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Ajouter',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ],
        ]);
    }
}