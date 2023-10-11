<?php

namespace Element\Form\Application;

use Application\Form\HasDescription\HasDescriptionFieldset;
use Element\Service\ApplicationTheme\ApplicationThemeServiceAwareTrait;
use Formation\Service\Formation\FormationServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class ApplicationForm extends Form {
    use FormationServiceAwareTrait;
    use ApplicationThemeServiceAwareTrait;

    public function init(): void
    {
        // libelle
        $this->add([
            'type' => Text::class,
            'name' => 'libelle',
            'options' => [
                'label' => "Libelle <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => [ 'disable_html_escape' => true, ],
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
                'value_options' => $this->getApplicationThemeService()->getApplicationsGroupesAsOption(),
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
        ]));
    }
}