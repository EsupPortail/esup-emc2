<?php

namespace Application\Form\ParcoursDeFormation;

use Application\Entity\Db\ParcoursDeFormation;
use Carriere\Service\Categorie\CategorieServiceAwareTrait;
use Formation\Service\Formation\FormationServiceAwareTrait;
use Metier\Service\Metier\MetierServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use Laminas\Validator\Callback;

class ParcoursDeFormationForm extends Form {
    use CategorieServiceAwareTrait;
    use MetierServiceAwareTrait;
    use FormationServiceAwareTrait;

    public function init()
    {
        // libelle
        $this->add([
            'type' => Text::class,
            'name' => 'libelle',
            'options' => [
                'label' => "Libelle :",
            ],
            'attributes' => [
                'id' => 'libelle',
            ],
        ]);

        $this->add([
            'type' => Select::class,
            'name' => 'type',
            'options' => [
                'label' => "Type de parcours de formations* :",
                'empty_option' => "Sélectionner un type de parcours  ...",
                'value_options' => [
                    ParcoursDeFormation::TYPE_CATEGORIE => ParcoursDeFormation::TYPE_CATEGORIE,
                    ParcoursDeFormation::TYPE_METIER => ParcoursDeFormation::TYPE_METIER,
                ],
            ],
            'attributes' => [
                'id' => 'type',
                'class'             => 'bootstrap-selectpicker show-tick',
                //'data-live-search'  => 'true',
            ],
        ]);

        $this->add([
            'type' => Select::class,
            'name' => 'categorie',
            'options' => [
                'label' => "Catégorie (laisser vide si concerne un métier) :",
                'empty_option' => "Sélectionner une catégorie ...",
                'value_options' => $this->getCategorieService()->getCategorieAsOption(),
            ],
            'attributes' => [
                'id' => 'categorie',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);

        $this->add([
            'type' => Select::class,
            'name' => 'metier',
            'options' => [
                'label' => "Métier (laisser vide si concerne une catégorie) :",
                'empty_option' => "Sélectionner un métier ...",
                'value_options' => $this->getMetierService()->getMetiersTypesAsMultiOptions(),
            ],
            'attributes' => [
                'id' => 'metier',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
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

        //formation ...
        $this->add([
            'type' => Select::class,
            'name' => 'formations',
            'options' => [
                'label' => "Formations * :",
                'empty_option' => "Sélectionner des formations ...",
                'value_options' => $this->getFormationService()->getFormationsGroupesAsGroupOptions(),
            ],
            'attributes' => [
                'id' => 'formations',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
                'multiple'          => 'multiple',
            ],
        ]);

        //submit
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

        //inputFilter
        $this->setInputFilter((new Factory())->createInputFilter([
            'libelle'            => [ 'required' => true, ],
            'description'        => [ 'required' => false, ],
            'type'               => [ 'required' => true,
                'validators' => [[
                    'name' => Callback::class,
                    'options' => [
                        'messages' => [
                            Callback::INVALID_VALUE => "Le champ de référence (Catégorie ou métier) doit être saisi",
                        ],
                        'callback' => function ($value, $context = []) {
                            if($context['type'] === ParcoursDeFormation::TYPE_CATEGORIE) return $context['categorie'] !== '';
                            if($context['type'] === ParcoursDeFormation::TYPE_METIER) return $context['metier'] !== '';
                            return $value !== '';
                        },
                        //'break_chain_on_failure' => true,
                    ],
                ]],
            ],
            'metier'             => [ 'required' => false, ],
            'categorie'          => [ 'required' => false, ],
            'formations'         => [ 'required' => false, ],
        ]));

    }


}