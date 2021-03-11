<?php

namespace Formation\Form\FormationInstance;

use Formation\Entity\Db\FormationInstance;
use Zend\Form\Element\Button;
use Zend\Form\Element\Number;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class FormationInstanceForm extends Form
{

    public function init()
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
        /** taille liste principale */
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
        /** taille liste complementaire */
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
        /** taille liste complementaire */
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
        /** taille liste complementaire */
        $this->add([
            'type' => Select::class,
            'name' => 'inscription',
            'options' => [
                'label' => "Inscriptions directes par les agents :",
                'empty_option' => "Sélectionner un type d'inscription ...",
                'value_options' => [
                    false => 'Non, les agents sont inscrits par les gestionnaires de formation',
                    true => 'Oui, les agents peuvent s\'inscrire direcement dans l\'application',
                ],
            ],
            'attributes' => [
                'id' => 'inscription',
            ],
        ]);
        /** taille liste complementaire */
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
        ]));
    }
}