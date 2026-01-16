<?php

namespace Carriere\Form\Mobilite;

use Laminas\Form\Element\Button;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class MobiliteForm extends Form {

    public function init(): void
    {
        //code
        //libelle
        $this->add([
            'type' => Text::class,
            'name' => 'code',
            'options' => [
                'label' => "Code <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'code',
            ],
        ]);
        //libelle
        $this->add([
            'type' => Text::class,
            'name' => 'libelle',
            'options' => [
                'label' => "Libellé <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'libelle',
            ],
        ]);
        //description
        $this->add([
            'type' => Textarea::class,
            'name' => 'description',
            'options' => [
                'label' => "Description :",
            ],
            'attributes' => [
                'id' => 'description',
                'class' => "type2",
            ],
        ]);
        //button
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
            'description'           => [ 'required' => false,  ],
            'libelle'               => [ 'required' => true,  ],
            'code'                  => [ 'required' => true,  ],
        ]));
    }
}