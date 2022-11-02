<?php

namespace Application\Form\FicheMetierImportation;

use Laminas\Form\Element\Button;
use Laminas\Form\Element\File;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class FicheMetierImportationForm extends Form {


    public function init()
    {
        //file CSV
        $this->add([
            'type' => File::class,
            'name' => 'fichier',
            'options' => [
                'label' => 'Fiche métier au format CSV <span class="icon icon-star" title="Champ obligatoire"></span> :',
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
        ]);
        //mode
        $this->add([
            'type' => Select::class,
            'name' => 'mode',
            'options' => [
                'label' => 'Mode  <span class="icon icon-star" title="Champ obligatoire"></span> :',
                'label_options' => [ 'disable_html_escape' => true, ],
                'value_options' => [
                    'preview' => "Prévisualisation",
                    'import' => "Importation",
                ]
            ],
            'attributes' => [
                'id' => 'mode',
            ],
        ]);
        //Submit
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => 'Traiter le ficher',
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ],
        ]);

        $this->setInputFilter((new Factory())->createInputFilter([
            'fichier' => [ 'required' => true,  ],
            'mode'  => ['required' => true, ],
        ]));
    }
}