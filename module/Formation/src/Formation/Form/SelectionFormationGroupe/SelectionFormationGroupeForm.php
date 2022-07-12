<?php

namespace Formation\Form\SelectionFormationGroupe;

use Formation\Service\FormationGroupe\FormationGroupeServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class SelectionFormationGroupeForm extends Form
{
    use FormationGroupeServiceAwareTrait;

    public function init()
    {
        //select multiple avec groupe
        $this->add([
            'type' => Select::class,
            'name' => 'groupes',
            'options' => [
                'label' => "Groupes de formation:",
                'empty_option' => "Sélectionner le ou les groupes de formation",
                'value_options' => $this->getFormationGroupeService()->getFormationsGroupesAsOption(),
            ],
            'attributes' => [
                'id' => 'groupes',
                'class' => 'bootstrap-selectpicker show-tick',
                'data-live-search' => 'true',
                'multiples' => 'multiples',
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
            'groupes' => ['required' => false,],
        ]));
    }
}