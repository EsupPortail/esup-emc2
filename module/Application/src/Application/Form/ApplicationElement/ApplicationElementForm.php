<?php

namespace Application\Form\ApplicationElement;

use Application\Service\Application\ApplicationServiceAwareTrait;
use Application\Service\CompetenceMaitrise\CompetenceMaitriseServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Number;
use Zend\Form\Element\Select;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class ApplicationElementForm extends Form {
    use ApplicationServiceAwareTrait;
    use CompetenceMaitriseServiceAwareTrait;

    public function init()
    {
        //competence
        $this->add([
            'name' => 'application',
            'type' => Select::class,
            'options' => [
                'label' => 'Application * : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'empty_option' => "Sélectionner une application ... ",
                'value_options' => $this->getApplicationService()->getApplicationsAsOptions(),
            ],
            'attributes' => [
                'id'                => 'application',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ]
        ]);
        //niveau
        $this->add([
            'name' => 'niveau',
            'type' => Select::class,
            'options' => [
                'label' => 'Niveau  : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'empty_option' => "Sélectionner un niveau ... ",
                'value_options' => $this->getCompetenceMaitriseService()->getCompetencesMaitrisesAsOptions(),
            ],
            'attributes' => [
                'id'                => 'niveau',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
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
            'application'   => [ 'required' => true, ],
            'niveau'        => [ 'required' => false, ],
        ]));
    }
}