<?php

namespace Formation\Form\SelectionFormation;

use Formation\Service\Formation\FormationServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class SelectionFormationForm extends Form
{
    use FormationServiceAwareTrait;

    public function init()
    {

        //select multiple avec groupe
        $this->add([
            'type' => Select::class,
            'name' => 'formations',
            'options' => [
                'label' => "Formations associées :",
                'empty_option' => "Sélectionner la ou les formations ...",
                'value_options' => $this->getFormationService()->getFormationsThemesAsGroupOptions(),
            ],
            'attributes' => [
                'id' => 'formations',
                'class' => 'bootstrap-selectpicker show-tick',
                'data-live-search' => 'true',
                'multiple' => 'multiple',
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
            'formations' => ['required' => false,],
        ]));
    }
}