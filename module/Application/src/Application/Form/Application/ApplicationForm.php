<?php

namespace Application\Form\Application;

use Application\Form\HasDescription\HasDescriptionFieldset;
use Application\Service\Application\ApplicationGroupeServiceAwareTrait;
use Formation\Service\Formation\FormationServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class ApplicationForm extends Form {
    use FormationServiceAwareTrait;
    use ApplicationGroupeServiceAwareTrait;

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
        //groupe
        $this->add([
            'name' => 'groupe',
            'type' => Select::class,
            'options' => [
                'label' => "Groupe de l'application : ",
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'empty_option' => 'Sélectionner un groupe ...',
                'value_options' => $this->getApplicationGroupeService()->getApplicationsGroupesAsOption(),
            ],
            'attributes' => [
                'class' => 'description form-control',
                'style' => 'height:300px;',
            ]
        ]);
        // description
        $this->add([
            'name' => 'HasDescription',
            'type' => HasDescriptionFieldset::class,
            'attributes' => [
                'id' => 'description',
            ],
        ]);
        // url
        $this->add([
            'type' => Text::class,
            'name' => 'url',
            'options' => [
                'label' => "Adresse de l'application :",
            ],
            'attributes' => [
                'id' => 'url',
            ],
        ]);
        // formation
        $this->add([
            'type' => Select::class,
            'name' => 'formations',
            'options' => [
                'label' => "Formations associées :",
                'empty_option' => "Sélectionner la ou les formations ...",
                'value_options' => $this->getFormationService()->getFormationsAsOptions(),
            ],
            'attributes' => [
                'id' => 'formations',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
                'multiple'          => 'multiple',
            ],
        ]);
        // button
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer l\'application',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ],
        ]);

        //filter
        $this->setInputFilter((new Factory())->createInputFilter([
            'libelle'               => [ 'required' => true,  ],
            'groupe'                => [ 'required' => false,  ],
            'description'           => [ 'required' => false,  ],
            'url'                   => [ 'required' => false,  ],
            'formations'            => [ 'required' => false,  ],
        ]));
    }
}