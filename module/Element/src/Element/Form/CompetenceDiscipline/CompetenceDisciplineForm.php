<?php

namespace Element\Form\CompetenceDiscipline;

use Element\Service\Competence\CompetenceServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class CompetenceDisciplineForm extends Form {
    use CompetenceServiceAwareTrait;

    public function init(): void
    {

        //libelle
        $this->add([
            'type' => Text::class,
            'name' => 'libelle',
            'options' => [
                'label' => "Libellé   <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
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
                'label' => "Description : ",
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'description',
                'class' => 'tinymce',
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

        //filter
        $this->setInputFilter((new Factory())->createInputFilter([
            'libelle'               => [ 'required' => true,  ],
            'description'           => [ 'required' => false,  ],
        ]));
    }
}