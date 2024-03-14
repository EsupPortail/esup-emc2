<?php

namespace Formation\Form\FormationInstance;

use Formation\Entity\Db\FormationInstance;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Number;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use Laminas\Validator\Regex;

class FormationInstanceForm extends Form
{

    public function init(): void
    {
        /** Complement */
        $this->add([
            'name' => 'description',
            'type' => Textarea::class,
            'options' => [
                'label' => 'Description :',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'id' => 'description',
                'class' => 'form-control description type2',
            ],
        ]);
        /** Taille liste principale */
        $this->add([
            'type' => Number::class,
            'name' => 'principale',
            'options' => [
                'label' => "Nombre de place en liste principale * :",
            ],
            'attributes' => [
                'id' => 'principale',
            ],
        ]);
        /** Taille liste complementaire */
        $this->add([
            'type' => Number::class,
            'name' => 'complementaire',
            'options' => [
                'label' => "Nombre de place en liste complémentaire * :",
            ],
            'attributes' => [
                'id' => 'complementaire',
            ],
        ]);
        /** Taille liste complementaire */
        $this->add([
            'type' => Select::class,
            'name' => 'type',
            'options' => [
                'label' => "Type de formation :",
                'empty_option' => "Sélectionner un type ...",
                'value_options' => [
                    FormationInstance::TYPE_INTERNE => 'Formation interne',
                    FormationInstance::TYPE_EXTERNE => 'Formation externe',
                    FormationInstance::TYPE_REGIONALE => 'Formation régionale',
                ],
            ],
            'attributes' => [
                'id' => 'type',
            ],
        ]);
        /** Taille liste complementaire */
        $this->add([
            'type' => Select::class,
            'name' => 'inscription',
            'options' => [
                'label' => "Inscriptions directes par les agents :",
                'empty_option' => "Sélectionner un type d'inscription ...",
                'value_options' => [
                    false => 'Non, les agents sont inscrits par les gestionnaires de formation',
                    true => 'Oui, les agents peuvent s\'inscrire directement dans l\'application',
                ],
            ],
            'attributes' => [
                'id' => 'inscription',
            ],
        ]);
        /** Taille liste complementaire */
        $this->add([
            'type' => Text::class,
            'name' => 'lieu',
            'options' => [
                'label' => "Lieu de la formation * :",
            ],
            'attributes' => [
                'id' => 'lieu',
            ],
        ]);
        //cout ht
        $this->add([
            'type' => Text::class,
            'name' => 'cout_ht',
            'options' => [
                'label' => "Coût HT :",
            ],
            'attributes' => [
                'id' => 'cout_ht',
            ],
        ]);
        //cout ht
        $this->add([
            'type' => Text::class,
            'name' => 'cout_ttc',
            'options' => [
                'label' => "Coût TTC :",
            ],
            'attributes' => [
                'id' => 'cout_ttc',
            ],
        ]);

        //button
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
            'description' => ['required' => false,],
            'principale' => ['required' => true,],
            'complementaire' => ['required' => true,],
            'inscription' => ['required' => true,],
            'lieu' => ['required' => true,],
            'type' => ['required' => true,],
            'cout_ht' => [
                'required' => false,
                'validators' => [
                    [
                        'name' => Regex::class,
                        'options' => [
                            'pattern' => '/^\d+(\.\d+)?$/',
                            'messages' => [
                                Regex::NOT_MATCH => "Veuillez saisir une valeur correcte",
                            ],
                            //'break_chain_on_failure' => true,
                        ],
                    ],
                ],
            ],
            'cout_ttc' => [
                'required' => false,
                'validators' => [
                    [
                        'name' => Regex::class,
                        'options' => [
                            'pattern' => '/^\d+(\.\d+)?$/',
                            'messages' => [
                                Regex::NOT_MATCH => "Veuillez saisir une valeur correcte",
                            ],
                            //'break_chain_on_failure' => true,
                        ],
                    ],
                ],
            ],
        ]));
    }
}