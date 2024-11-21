<?php

namespace EntretienProfessionnel\Form\Recours;

use Laminas\Form\Element\Button;
use Laminas\Form\Element\Date;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class RecoursForm extends Form
{

    public function init(): void
    {
        //date procedure
        $this->add([
            'type' => Date::class,
            'name' => 'date_procedure',
            'options' => [
                'label' => "Date de la procédure <span class='icon icon-obligatoire text-danger' title='Champ obligatoire'></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
//                'format' => 'd/m/Y',
            ],
            'attributes' => [
                'id' => 'date_procedure',
            ],
        ]);
        //commentaire
        $this->add([
            'name' => 'commentaire',
            'type' => Textarea::class,
            'options' => [
                'label' => 'Commentaire  : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'id'                => 'commentaire',
                'class'             => 'tinymce type2',
            ],
        ]);
        // button
        $this->add([
            'type' => Button::class,
            'name' => 'enregistrer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer',
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ],
        ]);

        //inputFIlter
        $this->setInputFilter((new Factory())->createInputFilter([
            'date_procedure'     => [ 'required' => true,  ],
            'commentaire'        => [ 'required' => false,  ],
        ]));
    }
}