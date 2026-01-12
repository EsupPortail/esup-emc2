<?php

namespace FicheMetier\Form\SelectionnerMissionPrincipale;

use FicheMetier\Service\MissionPrincipale\MissionPrincipaleServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class SelectionnerMissionPrincipaleForm extends Form
{
    use MissionPrincipaleServiceAwareTrait;

    public function init(): void
    {
        $this->add([
            'type' => Select::class,
            'name' => 'missions',
            'options' => [
                'label' => "Mission·s principale·s <span class='icon icon-obligation' title='Champ obligatoire'></span> :",
                'label_options' => ['disable_html_escape' => true,],
                'empty_option' => "Sélectionner une ou plusieurs missions principales",
                'value_options' => $this->getMissionPrincipaleService()->getMissionsPrincipalesAsOptions(),
            ],
            'attributes' => [
                'id' => 'missions',
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
                'label' => '<i class="fas fa-clone"></i> Sélectionner',
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
            'missions' => ['required' => false,],
        ]));
    }
}
