<?php

namespace Application\Form\CompetenceMaitrise;

use Application\Service\CompetenceMaitrise\CompetenceMaitriseServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Number;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\Factory;
use Zend\Validator\Callback;

class CompetenceMaitriseForm extends Form {
    use CompetenceMaitriseServiceAwareTrait;

    public function init()
    {
        //libelle
        $this->add([
            'type' => Text::class,
            'name' => 'libelle',
            'options' => [
                'label' => "Libelle* :",
            ],
            'attributes' => [
                'id' => 'libelle',
            ],
        ]);
        //niveau
        $this->add([
            'type' => Number::class,
            'name' => 'niveau',
            'options' => [
                'label' => "Niveau * :",
            ],
            'attributes' => [
                'id' => 'niveau',
            ],
        ]);
        $this->add([
            'name' => 'old-niveau',
            'type' => Hidden::class,
            'attributes' => [
                'value' => "",
            ],
        ]);
        // description
        $this->add([
            'name' => 'description',
            'type' => 'textarea',
            'options' => [
                'label' => 'Description : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'class' => 'type2 form-control',
            ]
        ]);
        //submit
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer' ,
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ],
        ]);

        //inputFilter
        $this->setInputFilter((new Factory())->createInputFilter([
            'libelle'            => [ 'required' => true, ],
            'niveau'             => [
                'required' => true,
                'validators' => [[
                    'name' => Callback::class,
                    'options' => [
                        'messages' => [
                            Callback::INVALID_VALUE => "Ce niveau est déjà utilisé",
                        ],
                        'callback' => function ($value, $context = []) {
                            if($value ==  $context['old-niveau']) return true;
                            return ($this->getCompetenceMaitriseService()->getCompetenceMaitriseByNiveau($value) == null);
                        },
                    ],
                ]],
            ],
            'description'        => [ 'required' => false, ],
        ]));
    }
}