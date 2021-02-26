<?php

namespace UnicaenParametre\Form\Categorie;

use UnicaenParametre\Service\Categorie\CategorieServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Number;
use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;
use Zend\InputFilter\Factory;
use Zend\Validator\Callback;

class CategorieForm extends Form {
    use CategorieServiceAwareTrait;

    public function init()
    {
        //code
        $this->add([
            'type' => Text::class,
            'name' => 'code',
            'options' => [
                'label' => "Code de la catégorie : ",
                'label_attributes' => [
                    'class' => 'required',
                ],
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
                'label' => "Libellé de la catégorie : ",
                'label_attributes' => [
                    'class' => 'required',
                ],
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
                'label' => "Description de la catégorie : ",
            ],
            'attributes' => [
                'id' => 'description',
                'class' => 'type2',
            ],
        ]);
        //ordre
        $this->add([
            'type' => Number::class,
            'name' => 'ordre',
            'options' => [
                'label' => "Ordre de la catégorie : ",
                'label_attributes' => [
                    'class' => 'required',
                ],
            ],
            'attributes' => [
                'id' => 'ordre',
            ],
        ]);
        //button
        $this->add([
            'type' => Button::class,
            'name' => 'next',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-default',
            ],
        ]);
        //input filter
        $this->setInputFilter((new Factory())->createInputFilter([
            'code'       => [
                'required' => true,
                'validators' => [[
                    'name' => Callback::class,
                    'options' => [
                        'messages' => [
                            Callback::INVALID_VALUE => "Ce code existe déjà",
                        ],
                        'callback' => function ($value, $context = []) {
                            if($value == $context['old-code']) return true;
                            return ($this->getCategorieService()->getCategoriebyCode($value) == null);
                        },
                    ],
                ]],
            ],
            'libelle'     => [     'required' => true, ],
            'description' => [     'required' => false, ],
            'ordre'       => [     'required' => false, ],
        ]));
    }

    public function setOldCode($value){
        $this->get('old-code')->setValue($value);
    }
}