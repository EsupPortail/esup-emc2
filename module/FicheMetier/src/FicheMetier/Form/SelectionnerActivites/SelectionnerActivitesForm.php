<?php

namespace FicheMetier\Form\SelectionnerActivites;

use FicheMetier\Service\Activite\ActiviteServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class SelectionnerActivitesForm extends Form
{
    use ActiviteServiceAwareTrait;

    public function init(): void
    {
        $this->add([
            'type' => Select::class,
            'name' => 'activites',
            'options' => [
                'label' => "Activité·s <span class='icon icon-obligation' title='Champ obligatoire'></span> :",
                'label_options' => ['disable_html_escape' => true,],
                'empty_option' => "Sélectionner une ou plusieurs activités",
                'value_options' => $this->getActiviteService()->getActivitesAsOptions(),
            ],
            'attributes' => [
                'id' => 'activites',
                'class' => 'bootstrap-selectpicker show-tick',
                'data-live-search' => 'true',
                'multiple' => 'multiple',
            ],
        ]);

        // button
        $this->add([
            'type' => Button::class,
            'name' => 'enregistrer',
            'options' => [
                'label' => 'Valider',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ],
        ]);

        //inputFilter
        $this->setInputFilter((new Factory())->createInputFilter([
            'activites' => ['required' => false,],
        ]));
    }
}

