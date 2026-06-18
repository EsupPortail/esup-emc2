<?php

namespace Structure\Form\Contact;


use Laminas\Form\Element\Button;
use Laminas\Form\Element\Email;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Url;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenContact\Service\Type\TypeServiceAwareTrait;

class ContactForm extends Form
{
    use TypeServiceAwareTrait;
    use StructureServiceAwareTrait;

    public function init(): void
    {
        //type
        $this->add([
            'type' => Select::class,
            'name' => 'type',
            'options' => [
                'label' => "Type de contact <span class='icon icon-obligatoire' title='Champ obligatoire'></span> :",
                'label_options' => ['disable_html_escape' => true,],
                'empty_option' => "Sélectionner un type de contact ...",
                'value_options' => $this->getTypeService()->getTypesAsOptions(),
            ],
            'attributes' => [
                'id' => 'metier',
                'class' => 'bootstrap-selectpicker show-tick',
                'data-live-search' => 'true',
            ],
        ]);
        //service
        $this->add([
            'type' => Text::class,
            'name' => 'service',
            'options' => [
                'label' => "Service :",
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'id' => 'service',
            ],
        ]);
        //nom
        $this->add([
            'type' => Text::class,
            'name' => 'denomination',
            'options' => [
                'label' => "Dénomination :",
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'id' => 'denomination',
            ],
        ]);
        //telephone
        $this->add([
            'type' => Text::class,
            'name' => 'telephone',
            'options' => [
                'label' => "Téléphone :",
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'id' => 'telephone',
            ],
        ]);
        //email
        $this->add([
            'type' => Email::class,
            'name' => 'email',
            'options' => [
                'label' => "Adresse électronique <span class='icon icon-obligatoire' title='Champ obligatoire'></span> :",
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'id' => 'email',
            ],
        ]);
        //url
        $this->add([
            'type' => Url::class,
            'name' => 'url',
            'options' => [
                'label' => "Site internet / plateforme :",
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'id' => 'url',
            ],
        ]);
        //structures
        $this->add([
            'type' => Select::class,
            'name' => 'structures',
            'options' => [
                'label' => "Structures <span class='icon icon-info' title='Sélection multiple possible'></span> :",
                'label_options' => ['disable_html_escape' => true,],
                'value_options' => $this->getStructureService()->getStructuresAsOptionGroup(),
            ],
            'attributes' => [
                'id' => 'structures',
                'multiple' => 'multiple'
            ],
        ]);
        //button
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer',
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-success',
            ],
        ]);

        //inputFilter
        $this->setInputFilter((new Factory())->createInputFilter([
            'type' => ['required' => true,],
            'service' => ['required' => false,],
            'denomination' => ['required' => false,],
            'telephone' => ['required' => false,],
            'structures' => ['required' => false,],
            'email' => ['required' => true,],
            'url' => ['required' => false,],
        ]));
    }
}