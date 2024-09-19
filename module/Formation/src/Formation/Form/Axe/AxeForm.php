<?php

namespace Formation\Form\Axe;

use Laminas\Form\Element\Button;
use Laminas\Form\Element\Color;
use Laminas\Form\Element\Number;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class AxeForm extends Form
{
    public function init(): void
    {
        //libelle
        $this->add([
            'type' => Text::class,
            'name' => 'libelle',
            'options' => [
                'label' => "Libellé <span class='icon icon-obligatoire' title='champ Obligatoire'></span> :",
                'label_options' => [ 'disable_html_escape' => true,],
            ],
            'attributes' => [
                'id' => 'libelle',
            ],
        ]);
        //description
        $this->add([
            'name' => 'description',
            'type' => Textarea::class,
            'options' => [
                'label' => "Description :",
                'label_options' => [ 'disable_html_escape' => true,],
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
                'label_options' => [ 'disable_html_escape' => true,],
            ],
            'attributes' => [
                'id' => 'ordre',
            ],
        ]);
        //couleur
        $this->add([
            'type' => Color::class,
            'name' => 'couleur',
            'options' => [
                'label' => "Couleur :",
                'label_options' => [ 'disable_html_escape' => true,],
            ],
            'attributes' => [
                'id' => 'couleur',
            ],
        ]);
        //bouton
        $this->add([
            'type' => Button::class,
            'name' => 'bouton',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer',
                'label_options' => [ 'disable_html_escape' => true,],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-success',
            ],
        ]);
        //input
        $this->setInputFilter((new Factory())->createInputFilter([
            'libelle' => ['required' => true,],
            'description' => ['required' => false,],
            'ordre' => ['required' => false,],
            'couleur' => ['required' => false,],
        ]));
    }
}