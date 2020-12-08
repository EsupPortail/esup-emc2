<?php

namespace UnicaenNote\Form\Type;

use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenNote\Entity\Db\Type;
use Zend\Form\Element\Button;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;
use Zend\InputFilter\Factory;
use Zend\Validator\Callback;

class TypeForm extends Form {
    use EntityManagerAwareTrait;

    public function init()
    {
        //code
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
        //old-code
        $this->add([
            'name' => 'old-code',
            'type' => Hidden::class,
            'attributes' => [
                'value' => "",
            ],
        ]);
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
        //style
        $this->add([
            'type' => Text::class,
            'name' => 'style',
            'options' => [
                'label' => "Style :",
            ],
            'attributes' => [
                'id' => 'style',
            ],
        ]);
        //description
        $this->add([
            'name' => 'description',
            'type' => Textarea::class,
            'options' => [
                'label' => 'Description : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'class' => 'description form-control',
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

        $this->setInputFilter((new Factory())->createInputFilter([
            'libelle' => [ 'required' => true, ],
            'code' => [
                'required' => true,
                'validators' => [[
                    'name' => Callback::class,
                    'options' => [
                        'messages' => [
                            Callback::INVALID_VALUE => "Ce code existe déjà",
                        ],
                        'callback' => function ($value, $context = []) {
                            if($value == $context['old-code']) return true;
                            return ($this->getEntityManager()->getRepository(Type::class)->findOneBy(['code'=>$value],[]) == null);
                        },
                        //'break_chain_on_failure' => true,
                    ],
                ]],
            ],
            'old-code' => ['required' => false, ],
            'style' => ['required' => false, ],
            'description' => ['required' => false, ],
        ]));
    }



    public function setOldCode($value){
        $this->get('old-code')->setValue($value);
    }
}