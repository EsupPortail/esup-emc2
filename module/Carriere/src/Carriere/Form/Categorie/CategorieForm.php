<?php

namespace Carriere\Form\Categorie;

use Zend\Form\Element\Button;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class CategorieForm extends Form {

    public function init()
    {
        //code
        //libelle
        $this->add([
            'type' => Text::class,
            'name' => 'code',
            'options' => [
                'label' => "Code * :",
            ],
            'attributes' => [
                'id' => 'code',
            ],
        ]);
        //libelle
        //libelle
        $this->add([
            'type' => Text::class,
            'name' => 'libelle',
            'options' => [
                'label' => "Libelle * :",
            ],
            'attributes' => [
                'id' => 'libelle',
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
            'libelle'               => [ 'required' => true,  ],
            'code'                  => [ 'required' => true,  ],
        ]));
    }
}