<?php

namespace Metier\Form\Metier;

use Carriere\Service\Categorie\CategorieServiceAwareTrait;
use Metier\Service\Domaine\DomaineServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class MetierForm extends Form {
    use CategorieServiceAwareTrait;
    use DomaineServiceAwareTrait;

    public function init(): void
    {
        //domaines
        $this->add([
            'type' => Select::class,
            'name' => 'domaines',
            'options' => [
                'label' => "Domaine professionnel <span class='icon icon-obligatoire' title='Champ obligatoire'></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
                'empty_option' => "Sélectionner des domaines ...",
                'value_options' => $this->getDomaineService()->getDomainesAsOptions(),
            ],
            'attributes' => [
                'id' => 'domaines',
                'class'             => 'select2',
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
            'domaines'          => [ 'required' => true,  ],
            'libelle'           => [ 'required' => true,  ],
            'libelle_feminin'   => [ 'required' => false, ],
            'libelle_masculin'  => [ 'required' => false, ],
            'categorie'         => [ 'required' => false, ],
        ]));
    }
}