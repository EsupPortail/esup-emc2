<?php

namespace Application\Form\ModifierRattachement;

use Application\Entity\Db\ParcoursDeFormation;
use Carriere\Service\Categorie\CategorieServiceAwareTrait;
use Metier\Service\Metier\MetierServiceAwareTrait;
use Metier\Service\Domaine\DomaineServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use Laminas\Validator\Callback;

class ModifierRattachementForm extends Form
{
    use CategorieServiceAwareTrait;
    use MetierServiceAwareTrait;
    use DomaineServiceAwareTrait;

    public function init()
    {
        $this->add([
            'type' => Select::class,
            'name' => 'type',
            'options' => [
                'label' => "Type de parcours de formations* :",
                'empty_option' => "Sélectionner un type de parcours  ...",
                'value_options' => [
                    ParcoursDeFormation::TYPE_CATEGORIE => ParcoursDeFormation::TYPE_CATEGORIE,
                    ParcoursDeFormation::TYPE_DOMAINE => ParcoursDeFormation::TYPE_DOMAINE,
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
                'label' => "Catégorie (laisser vide si concerne un domaine ou un métier) :",
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
            'name' => 'domaine',
            'options' => [
                'label' => "Domaine (laisser vide si concerne une catégorie ou un métier) :",
                'empty_option' => "Sélectionner un domaine ...",
                'value_options' => $this->getDomaineService()->getDomainesAsOptions(),
            ],
            'attributes' => [
                'id' => 'Select',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);

        $this->add([
            'type' => Select::class,
            'name' => 'metier',
            'options' => [
                'label' => "Métier (laisser vide si concerne une catégorie ou un domaine) :",
                'empty_option' => "Sélectionner un métier ...",
                'value_options' => $this->getMetierService()->getMetiersTypesAsMultiOptions(),
            ],
            'attributes' => [
                'id' => 'metier',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
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
            'type' => [
                'required' => true,
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
            'domaine'             => [ 'required' => false, ],
            'categorie'          => [ 'required' => false, ],
        ]));
    }
}