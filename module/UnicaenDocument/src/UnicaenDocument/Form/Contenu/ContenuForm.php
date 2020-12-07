<?php

namespace UnicaenDocument\Form\Contenu;

use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenDocument\Entity\Db\Content;
use UnicaenDocument\Entity\Db\Macro;
use UnicaenDocument\Service\Macro\MacroServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\Factory;
use Zend\Validator\Callback;

class ContenuForm extends Form {
    use EntityManagerAwareTrait;
    use MacroServiceAwareTrait;


    public function getMacros() {
        return $this->getMacroService()->generateJSON();
    }

    public function init()
    {
        // code
        $this->add([
            'type' => Text::class,
            'name' => 'code',
            'options' => [
                'label' => "Code du contenu * :",
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
        //type
        $this->add([
            'name' => 'type',
            'type' => Select::class,
            'options' => [
                'label' => 'Type * : ',
                'empty_option' => "Sélectionner un type de contenu",
                'value_options' => [
                    Content::TYPE_PDF => "Fichier PDF",
                    Content::TYPE_TXT => "Contenu textuel",
                ],
            ],
        ]);
        //description
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
                'id' => 'description',
                'class' => 'description form-control',
            ]
        ]);
        //complement
        $this->add([
            'name' => 'complement',
            'type' => 'textarea',
            'options' => [
                'label' => 'Complément (nom du fichier, étiquette, ... ) * : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'id' => 'complement',
                'class' => 'form-control complement',
            ]
        ]);
        //contenu
        $this->add([
            'name' => 'contenu',
            'type' => 'textarea',
            'options' => [
                'label' => 'Contenu * : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'id' => 'contenu',
                'class' => 'form-control contenu',
            ]
        ]);
        //css
        $this->add([
            'name' => 'css',
            'type' => 'textarea',
            'options' => [
                'label' => 'Feuille de style (CSS) : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'id' => 'css',
                'class' => 'form-control css',
            ]
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
            'type' => ['required' => true, ],
            'description' => ['required' => false, ],
            'complement' => ['required' => true, ],
            'contenu' => ['required' => true, ],
            'css' => ['required' => false, ],

        ]));
    }

    public function setOldCode($value){
        $this->get('old-code')->setValue($value);
    }
}