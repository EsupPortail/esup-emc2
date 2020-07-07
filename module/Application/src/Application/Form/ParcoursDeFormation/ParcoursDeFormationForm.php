<?php

namespace Application\Form\ParcoursDeFormation;

use Application\Entity\Db\ParcoursDeFormation;
use Application\Service\Formation\FormationServiceAwareTrait;
use Application\Service\Metier\MetierServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;

class ParcoursDeFormationForm extends Form {
    use MetierServiceAwareTrait;
    use FormationServiceAwareTrait;

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
                'value_options' => [
                    1 => 'A',
                    2 => 'B',
                    3 => 'C',
                ],
            ],
            'attributes' => [
                'id' => 'categorie',
                'class'             => 'bootstrap-selectpicker show-tick',
                //'data-live-search'  => 'true',
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
                //'data-live-search'  => 'true',
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
                'value_options' => $this->getFormationService()->getFormationsThemesAsGroupOptions(),
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
    }
}