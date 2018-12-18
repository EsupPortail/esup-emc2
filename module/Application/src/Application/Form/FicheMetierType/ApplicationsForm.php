<?php

namespace Application\Form\FicheMetierType;

use Application\Service\Application\ApplicationServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class ApplicationsForm extends Form {
    use ApplicationServiceAwareTrait;

    public function init()
    {
        // description
        $this->add([
            'name' => 'applications',
            'type' => Select::class,
            'options' => [
                'label' => 'Applications : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'value_options' => $this->getApplicationOptions()
            ],
            'attributes' => [
                'class' => 'description form-control',
                'style' => 'height:300px;',
                'multiple' => 'multiple',
            ]
        ]);
        // formation
        $this->add([
            'name' => 'formation',
            'type' => 'textarea',
            'options' => [
                'label' => 'Formation : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'class' => 'formation form-control',
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
            'description' => [
                'required' => false,
            ],
            'formation' => [
                'required' => false,
            ],
        ]));
    }

    private function getApplicationOptions()
    {
        $applications = $this->getApplicationService()->getApplications('libelle');
        $options = [];
        foreach ($applications as $application) {
            $options[$application->getId()] = $application->getLibelle();
        }
        return $options;
    }
}