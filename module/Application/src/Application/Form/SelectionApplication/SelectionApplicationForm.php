<?php

namespace Application\Form\SelectionApplication;

use Application\Service\Application\ApplicationServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class SelectionApplicationForm extends Form {
    use ApplicationServiceAwareTrait;

    public function init() {

        //select multiple avec groupe
        $this->add([
            'type' => Select::class,
            'name' => 'applications',
            'options' => [
                'label' => "Applications associées :",
                'empty_option' => "Sélectionner la ou les applications ...",
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
        //inputfilter
        $this->setInputFilter((new Factory())->createInputFilter([
            'applications'               => [ 'required' => false,  ],
        ]));
    }
}