<?php

namespace Application\Form\Activite;

use Application\Service\Application\ApplicationServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class ActiviteForm extends Form {
    use ApplicationServiceAwareTrait;

    public function init()
    {
        // libelle
        $this->add([
            'type' => Text::class,
            'name' => 'libelle',
            'options' => [
                'label' => "Libelle :",
            ],
            'attributes' => [
                'id' => 'libelle',
            ],
        ]);
        // description
        $this->add([
            'name' => 'description',
            'type' => 'textarea',
            'options' => [
                'label' => 'Description : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'class' => 'type2 form-control',
            ]
        ]);
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
        // button
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer l\'activité',
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
            'libelle'               => [ 'required' => true,  ],
            'description'           => [ 'required' => true,  ],
            'applications'           => [ 'required' => false,  ],
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