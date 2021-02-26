<?php

namespace UnicaenParametre\Form\Parametre;

use UnicaenParametre\Entity\Db\Categorie;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Number;
use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;
use Zend\InputFilter\Factory;
use Zend\Validator\Callback;

class ParametreForm extends Form {
    use ParametreServiceAwareTrait;

    public function init() {
        //code
        $this->add([
            'type' => Text::class,
            'name' => 'code',
            'options' => [
                'label' => "Code du paramètre : ",
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
        $this->add([
            'name' => 'categorie-code',
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
                'label' => "Libellé du paramètre : ",
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
        //possibles
        $this->add([
            'type' => Text::class,
            'name' => 'possibles',
            'options' => [
                'label' => "Valeurs possibles (String, Boolean, Number, énumération avec '|' comme séparateur) : ",
            ],
            'attributes' => [
                'id' => 'possibles',
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
                            Callback::INVALID_VALUE => "Ce code existe déjà pour ce paramètre/catégorie",
                        ],
                        'callback' => function ($value, $context = []) {
                            if($context['categorie-code'] . "-" . $value ==  $context['old-code']) return true;
                            return ($this->getParametreService()->getParametreByCode($context['categorie-code'], $value) == null);
                        },
                    ],
                ]],
            ],
            'libelle'    => [     'required' => true, ],
            'possibles'  => [     'required' => false, ],
            'ordre'      => [     'required' => true, ],
        ]));
    }

    public function setOldCode($value){
        $this->get('old-code')->setValue($value);
    }

    /**
     * @param Categorie|null $categorie
     */
    public function setCategorie(?Categorie $categorie){
        if ($categorie !== null) {
            $this->get('categorie-code')->setValue($categorie->getCode());
        }
    }
}