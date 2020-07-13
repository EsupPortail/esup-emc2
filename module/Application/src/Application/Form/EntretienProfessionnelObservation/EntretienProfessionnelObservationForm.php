<?php

namespace Application\Form\EntretienProfessionnelObservation;

use Zend\Form\Element\Button;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;

class EntretienProfessionnelObservationForm extends Form {

    public function init()
    {
        // entretien
        $this->add([
            'name' => 'obs-entretien',
            'type' => Textarea::class,
            'options' => [
                'label' => "Observation sur l'entretien : ",
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'id' =>  'obs-entretien',
                'class' => 'type2 form-control',
            ]
        ]);
        // perspective
        $this->add([
            'name' => 'obs-perspective',
            'type' => Textarea::class,
            'options' => [
                'label' => "Observation sur les perspectives : ",
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'id' => 'obs-perspective',
                'class' => 'type2 form-control',
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