<?php

namespace FicheMetier\Form\Raison;

use Laminas\Form\Element\Button;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class RaisonForm extends Form
{
    public function init(): void
    {
        // description
        $this->add([
            'name' => 'raison',
            'type' => 'textarea',
            'options' => [
                'label' => 'Raison : ',
                'label_attributes' => [ 'class' => 'control-label', ],
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'class' => 'type2 form-control',
                'id' => 'raison',
            ]
        ]);

        // button
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
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

        $this->setInputFilter((new Factory())->createInputFilter([
            'raison'               => [ 'required' => false,  ],
        ]));
    }
}