<?php

namespace Application\Form\CompetenceType;

use Application\Service\Competence\CompetenceServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class CompetenceTypeForm extends Form {
    use CompetenceServiceAwareTrait;

    public function init() {

        //libelle
        $this->add([
            'type' => Text::class,
            'name' => 'libelle',
            'options' => [
                'label' => "Libelle :",
            ],
            'attributes' => [
                'id' => 'libelle',
            ],
        ]);
        //libelle
        $this->add([
            'type' => Text::class,
            'name' => 'ordre',
            'options' => [
                'label' => "Ordre :",
            ],
            'attributes' => [
                'id' => 'ordre',
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
            'ordre'                 => [ 'required' => true,  ],
        ]));
    }
}