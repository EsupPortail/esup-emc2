<?php

namespace Application\Form\MissionSpecifique;

use Application\Service\MissionSpecifique\MissionSpecifiqueServiceAwareTrait;
use Application\Service\MissionSpecifiqueTheme\MissionSpecifiqueThemeServiceAwareTrait;
use Application\Service\MissionSpecifiqueType\MissionSpecifiqueTypeServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class MissionSpecifiqueForm extends Form
{
    use MissionSpecifiqueServiceAwareTrait;
    use MissionSpecifiqueThemeServiceAwareTrait;
    use MissionSpecifiqueTypeServiceAwareTrait;

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
        // type
        $this->add([
            'type' => Select::class,
            'name' => 'type',
            'options' => [
                'label' => "Type de la mission :",
                'empty_option' => "Sélectionner le type de la mission ...",
                'value_options' => $this->getMissionSpecifiqueTypeService()->getMissionsSpecifiquesTypesAsOptions(),
            ],
            'attributes' => [
                'id' => 'type',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);
        // theme
        $this->add([
            'type' => Select::class,
            'name' => 'theme',
            'options' => [
                'label' => "Theme de la mission :",
                'empty_option' => "Sélectionner le thème de la mission ...",
                'value_options' => $this->getMissionSpecifiqueThemeService()->getMissionsSpecifiquesThemesAsOptions(),
            ],
            'attributes' => [
                'id' => 'theme',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
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
            'libelle' => [ 'required' => true, ],
            'type'    => [ 'required' => false, ],
            'theme'   => [ 'required' => false, ],
            'description'   => [ 'required' => false, ],
        ]));
    }

};
