<?php

namespace UnicaenEtat\Form\ActionType;

use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenPrivilege\Entity\Db\Privilege;
use Zend\Form\Element\Button;
use Zend\Form\Element\Color;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\Factory;
use Zend\Validator\Callback;

class ActionTypeForm extends Form {
    use EntityManagerAwareTrait;

    public function setOldCode($value){
        $this->get('old-code')->setValue($value);
    }

    public function init()
    {
        //code
        $this->add([
            'type' => Text::class,
            'name' => 'code',
            'options' => [
                'label' => "Code* :",
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
        //icone
        $this->add([
            'type' => Text::class,
            'name' => 'icone',
            'options' => [
                'label' => "Icone :",
            ],
            'attributes' => [
                'id' => 'icone',
            ],
        ]);
        //couleur
        $this->add([
            'type' => Color::class,
            'name' => 'couleur',
            'options' => [
                'label' => "Couleur :",
            ],
            'attributes' => [
                'id' => 'couleur',
            ],
        ]);
        //button
        $this->add([
            'type' => Button::class,
            'name' => 'bouton',
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
                            return ($this->getEntityManager()->getRepository(Privilege::class)->findOneBy(['code'=>$value],[]) == null);
                        },
                        //'break_chain_on_failure' => true,
                    ],
                ]],
            ],
            'old-code' => ['required' => false, ],
            'libelle'  => ['required' => true, ],
            'couleur'  => ['required' => false, ],
            'icone'    => ['required' => false, ],
        ]));
    }
}