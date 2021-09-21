<?php

namespace UnicaenDocument\Form\Macro;

use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenDocument\Entity\Db\Macro;
use UnicaenPrivilege\Entity\Db\CategoriePrivilege;
use Zend\Form\Element\Button;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;
use Zend\InputFilter\Factory;
use Zend\Validator\Callback;

class MacroForm extends Form {
    use EntityManagerAwareTrait;

    public function init()
    {
        // code
        $this->add([
            'type' => Text::class,
            'name' => 'code',
            'options' => [
                'label' => "Code de la macro * :",
            ],
            'attributes' => [
                'id' => 'code',
            ],
        ]);
        $this->add([
            'name' => 'old-code',
            'type' => Hidden::class,
            'attributes' => [
                'value' => "",
            ],
        ]);
        // variable
        $this->add([
            'type' => Text::class,
            'name' => 'variable',
            'options' => [
                'label' => "Variable associée * :",
            ],
            'attributes' => [
                'id' => 'variable',
            ],
        ]);
        // méthode
        $this->add([
            'type' => Text::class,
            'name' => 'methode',
            'options' => [
                'label' => "Méthode associée * :",
            ],
            'attributes' => [
                'id' => 'methode',
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
        // button
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

        $this->setInputFilter((new Factory())->createInputFilter([
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
                            return ($this->getEntityManager()->getRepository(Macro::class)->findOneBy(['code'=>$value],[]) == null);
                        },
                        //'break_chain_on_failure' => true,
                    ],
                ]],
            ],
            'old-code' => ['required' => false, ],
            'variable' => ['required' => true, ],
            'methode' => ['required' => true, ],
            'description' => ['required' => false, ],
        ]));
    }

    public function setOldCode($value){
        $this->get('old-code')->setValue($value);
    }

};
