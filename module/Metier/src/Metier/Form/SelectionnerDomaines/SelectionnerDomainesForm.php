<?php

namespace Metier\Form\SelectionnerDomaines;

use Laminas\Form\Element\Button;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use Metier\Service\Domaine\DomaineServiceAwareTrait;

class SelectionnerDomainesForm extends Form
{
    use DomaineServiceAwareTrait;

    public function init()
    {
        //multiselect
        $this->add([
            'type' => Select::class,
            'name' => 'domaines',
            'options' => [
                'label' => "Domaines :",
                'empty_option' => "Sélectionner un ou plusieurs domaines ...",
                'value_options' => $this->getDomaineService()->getDomainesAsOptions(),
            ],
            'attributes' => [
                'id' => 'domaines',
                'multiple'          => 'multiple',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);
        //submit
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
        //inputfilter
        $this->setInputFilter((new Factory())->createInputFilter([
            'domaines'               => [ 'required' => false,  ],
        ]));
    }
}