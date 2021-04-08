<?php

//TODO prévoir le cas de l'édition ou le nom et le code peuvent rester identiques

namespace UnicaenPrivilege\Form\CategoriePrivilege;

use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenPrivilege\Entity\Db\CategoriePrivilege;
use Zend\Form\Element\Button;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\Factory;
use Zend\Validator\Callback;

class CategoriePrivilegeForm extends Form {
    use EntityManagerAwareTrait;

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
        $this->add([
            'name' => 'old-libelle',
            'type' => Hidden::class,
            'attributes' => [
                'value' => "",
            ],
        ]);
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
        //namespace
        $this->add([
            'type' => Text::class,
            'name' => 'namespace',
            'options' => [
                'label' => "Namespace associé :",
            ],
            'attributes' => [
                'id' => 'namespace',
            ],
        ]);
        //ordre
        $this->add([
            'type' => Text::class,
            'name' => 'ordre',
            'options' => [
                'label' => "Ordre dans la liste :",
            ],
            'attributes' => [
                'id' => 'ordre',
            ],
        ]);
        //submit
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
            'libelle' => [
                'required' => true,
                'validators' => [[
                    'name' => Callback::class,
                    'options' => [
                        'messages' => [
                            Callback::INVALID_VALUE => "Ce libellé existe déjà",
                        ],
                        'callback' => function ($value, $context = []) {
                            if($value == $context['old-libelle']) return true;
                            return ($this->getEntityManager()->getRepository(CategoriePrivilege::class)->findOneBy(['libelle'=>$value],[]) == null);
                        },
                        //'break_chain_on_failure' => true,
                    ],
                ]],
            ],
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
                            return ($this->getEntityManager()->getRepository(CategoriePrivilege::class)->findOneBy(['code'=>$value],[]) == null);
                        },
                        //'break_chain_on_failure' => true,
                    ],
                ]],
            ],
            'old-libelle' => ['required' => false, ],
            'old-code' => ['required' => false, ],
            'namespace' => ['required' => false, ],
            'ordre' => ['required' => false, ],
        ]));
    }

    public function setOldLibelle($value){
        $this->get('old-libelle')->setValue($value);
    }

    public function setOldCode($value){
        $this->get('old-code')->setValue($value);
    }
}