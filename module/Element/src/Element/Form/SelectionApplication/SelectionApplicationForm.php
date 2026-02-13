<?php

namespace Element\Form\SelectionApplication;

use Element\Service\Application\ApplicationServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class SelectionApplicationForm extends Form {
    use ApplicationServiceAwareTrait;

    public function init(): void
    {

        //select multiple avec groupe
        $this->add([
            'type' => Select::class,
            'name' => 'applications',
            'options' => [
                'label' => "Applications associées  - sélection multiples possible :",
                'value_options' => $this->getApplicationService()->getApplicationsAsOptions(),
            ],
            'attributes' => [
                'id' => 'applications',
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
        //inputfilter
        $this->setInputFilter((new Factory())->createInputFilter([
            'applications'               => [ 'required' => false,  ],
        ]));
    }
}