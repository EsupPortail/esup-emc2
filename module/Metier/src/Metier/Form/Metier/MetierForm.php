<?php

namespace Metier\Form\Metier;

use Carriere\Service\Categorie\CategorieServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use Metier\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;

class MetierForm extends Form {
    use CategorieServiceAwareTrait;
    use FamilleProfessionnelleServiceAwareTrait;

    public function init(): void
    {
        //Famille
        $this->add([
            'type' => Select::class,
            'name' => 'familles',
            'options' => [
                'label' => "Familles professionnelles <span class='icon icon-info text-info' title='Sélection multiple possible'></span> <span class='icon icon-obligatoire' title='Champ obligatoire'></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
                'empty_option' => "Sélectionner des familles professionnelles ...",
                'value_options' => $this->getFamilleProfessionnelleService()->getFamillesProfessionnellesAsOptions(),
            ],
            'attributes' => [
                'id' => 'familles',
                'class'             => 'bootstrap-selectpicker show-tick',
                //'data-live-search'  => 'true',
                'multiple'          => 'multiple',
            ],
        ]);
        // libelle
        $this->add([
            'type' => Text::class,
            'name' => 'libelle',
            'options' => [
                'label' => "Libelle par défaut <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'libelle',
            ],
        ]);
        // libelle
        $this->add([
            'type' => Text::class,
            'name' => 'libelle_feminin',
            'options' => [
                'label' => "Libelle féminin :",
            ],
            'attributes' => [
                'id' => 'libelle_feminin',
            ],
        ]);
        // libelle
        $this->add([
            'type' => Text::class,
            'name' => 'libelle_masculin',
            'options' => [
                'label' => "Libelle masculin :",
            ],
            'attributes' => [
                'id' => 'libelle_masculin',
            ],
        ]);
        //categorie
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
                //'data-live-search'  => 'true',
            ],
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

        //inputFIlter
        $this->setInputFilter((new Factory())->createInputFilter([
            'familles'          => [ 'required' => false,  ],
            'libelle'           => [ 'required' => true,  ],
            'libelle_feminin'   => [ 'required' => false, ],
            'libelle_masculin'  => [ 'required' => false, ],
            'categorie'         => [ 'required' => false, ],
        ]));
    }
}