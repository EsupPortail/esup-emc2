<?php

namespace Application\Form\FicheMetier;

use Application\Service\Application\ApplicationServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class ApplicationsForm extends Form {
    use ApplicationServiceAwareTrait;

    public function init()
    {
        // applications
        $this->add([
            'name' => 'applications',
            'type' => Select::class,
            'options' => [
                'label' => 'Applications : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'value_options' => $this->getApplicationService()->getApplicationsAsOptions(),
            ],
            'attributes' => [
                'class' => 'description form-control',
                'style' => 'height:300px;',
                'multiple' => 'multiple',
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
            'applications' => [
                'required' => false,
            ],
        ]));
    }
}