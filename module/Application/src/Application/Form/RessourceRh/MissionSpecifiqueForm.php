<?php

namespace Application\Form\RessourceRh;

use Application\Service\MissionSpecifique\MissionSpecifiqueServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class MissionSpecifiqueForm extends Form
{
    use MissionSpecifiqueServiceAwareTrait;

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
                'value_options' => $this->getMissionSpecifiqueService()->getMissionsSpecifiquesTypesAsOptions(),
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
                'value_options' => $this->getMissionSpecifiqueService()->getMissionsSpecifiquesThemesAsOptions(),
            ],
            'attributes' => [
                'id' => 'theme',
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
            'libelle' => [ 'required' => true, ],
            'type'    => [ 'required' => false, ],
            'theme'   => [ 'required' => false, ],
        ]));
    }

};
