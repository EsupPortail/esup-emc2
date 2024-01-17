<?php

namespace Formation\Form\SelectionFormation;

use Formation\Service\Formation\FormationServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class SelectionFormationForm extends Form
{
    use FormationServiceAwareTrait;

    public function init(): void
    {

        //select multiple avec groupe
        $this->add([
            'type' => Select::class,
            'name' => 'formations',
            'options' => [
                'label' => "Formations associées :",
                'empty_option' => "Sélectionner la ou les formations ...",
                'value_options' => $this->getFormationService()->getFormationsGroupesAsGroupOptions(),
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
                'id' => 'enregistrer',
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