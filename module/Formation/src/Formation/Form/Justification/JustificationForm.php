<?php

namespace Formation\Form\Justification;

use Application\Form\HasDescription\HasDescriptionFieldset;
use Laminas\Form\Element\Button;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class JustificationForm extends Form {


    public function init(): void
    {
        //description
        $this->add([
            'name' => 'HasDescription',
            'type' => HasDescriptionFieldset::class,
            'attributes' => [
                'id' => 'description',
            ],
        ]);
        $this->get('HasDescription')->get('description')->setLabel("Motivation :");
        //bouton
        $this->add([
            'type' => Button::class,
            'name' => 'bouton',
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
            'HasDescription' => [ 'required' => true,  ],
        ]));
    }

}