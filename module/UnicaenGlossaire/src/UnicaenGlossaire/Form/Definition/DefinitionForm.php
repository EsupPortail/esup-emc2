<?php

namespace UnicaenGlossaire\Form\Definition;

use UnicaenGlossaire\Service\Definition\DefinitionServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;
use Zend\InputFilter\Factory;
use Zend\Validator\Callback;

class DefinitionForm extends Form {
    use DefinitionServiceAwareTrait;

    public function init()
    {
        //terme
        $this->add([
            'type' => Text::class,
            'name' => 'terme',
            'options' => [
                'label' => "Terme * :",
            ],
            'attributes' => [
                'id' => 'terme',
            ],
        ]);
        $this->add([
            'name' => 'old-terme',
            'type' => Hidden::class,
            'attributes' => [
                'value' => "",
            ],
        ]);
        //definition
        $this->add([
            'type' => Textarea::class,
            'name' => 'description',
            'options' => [
                'label' => "Définition :",
            ],
            'attributes' => [
                'id' => 'description',
                'class' => "type1",
            ],
        ]);
        //terme
        $this->add([
            'type' => Text::class,
            'name' => 'alternatives',
            'options' => [
                'label' => "Écritures alternatives (séparer les écritures par des ';') :",
            ],
            'attributes' => [
                'id' => 'alternatives',
            ],
        ]);
        //bouton
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
            'terme' => [
                'required' => true,
                'validators' => [[
                    'name' => Callback::class,
                    'options' => [
                        'messages' => [
                            Callback::INVALID_VALUE => "Ce terme existe déjà",
                        ],
                        'callback' => function ($value, $context = []) {
                            if($value == $context['old-terme']) return true;
                            return ($this->getDefinitionService()->getDefinitionByTerme($value) == null);
                        },
                        //'break_chain_on_failure' => true,
                    ],
                ]],
            ],
            'description' => [
                'required' => true,
            ],
            'alternatives' => [
                'required' => false,
            ],
        ]));
    }

    public function setOldTerme($value){
        $this->get('old-terme')->setValue($value);
    }
}