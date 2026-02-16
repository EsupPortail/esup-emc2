<?php

namespace FicheMetier\Form\CodeEmploiType;

use Laminas\Form\Element\Button;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class CodeEmploiTypeForm extends Form
{
    public function init(): void
    {
        // description
        $this->add([
            'name' => 'code-emploi-type',
            'type' => Text::class,
            'options' => [
                'label' => 'Code des emploi-types associés : ',
                'label_attributes' => [ 'class' => 'control-label', ],
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'code-emploi-type',
            ]
        ]);

        // button
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => 'Valider',
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
            'code-emploi-type'               => [ 'required' => false,  ],
        ]));
    }
}