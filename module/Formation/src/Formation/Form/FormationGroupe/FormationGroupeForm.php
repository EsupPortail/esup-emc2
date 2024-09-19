<?php

namespace Formation\Form\FormationGroupe;

use Formation\Service\Axe\AxeServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Number;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class FormationGroupeForm extends Form
{
    use AxeServiceAwareTrait;

    public function init(): void
    {
        //libelle
        $this->add([
            'type' => Text::class,
            'name' => 'libelle',
            'options' => [
                'label' => "Libellé <span class='icon icon-obligatoire' title='champ Obligatoire'></span> :",
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'id' => 'libelle',
            ],
        ]);
        //axe
        $this->add([
            'type' => Select::class,
            'name' => 'axe',
            'options' => [
                'label' => "Axe associé :",
                'empty_option' => "Sélectionner un axe ...",
                'value_options' => $this->getAxeService()->getAxesAsOptions(),
            ],
            'attributes' => [
                'id' => 'axe',
                'class' => 'bootstrap-selectpicker show-tick',
                'data-live-search' => 'true',
            ],
        ]);
        //description
        $this->add([
            'name' => 'description',
            'type' => Textarea::class,
            'options' => [
                'label' => "Description :",
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'id' => 'description',
                'class' => 'tinymce',
            ],
        ]);
        //ordre
        $this->add([
            'type' => Number::class,
            'name' => 'ordre',
            'options' => [
                'label' => "Ordre :",
            ],
            'attributes' => [
                'id' => 'ordre',
            ],
        ]);
        //bouton
        $this->add([
            'type' => Button::class,
            'name' => 'bouton',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-success',
            ],
        ]);
        //input
        $this->setInputFilter((new Factory())->createInputFilter([
            'libelle' => ['required' => true,],
            'axe' => ['required' => false,],
            'description' => ['required' => false,],
            'ordre' => ['required' => false,],
        ]));
    }
}