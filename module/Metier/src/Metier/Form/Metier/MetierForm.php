<?php

namespace Metier\Form\Metier;

use Application\Service\Categorie\CategorieServiceAwareTrait;
use Metier\Service\Domaine\DomaineServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Number;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class MetierForm extends Form {
    use CategorieServiceAwareTrait;
    use DomaineServiceAwareTrait;

    public function init()
    {
        //domaines
        $this->add([
            'type' => Select::class,
            'name' => 'domaines',
            'options' => [
                'label' => "Domaine professionnel* :",
                'empty_option' => "Sélectionner des domaines ...",
                'value_options' => $this->getDomaineService()->getDomainesAsOptions(),
            ],
            'attributes' => [
                'id' => 'domaines',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
                'multiple'          => 'multiple',
            ],
        ]);
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
        //niveau :: number
        $this->add([
            'type' => Number::class,
            'name' => 'niveau',
            'options' => [
                'label' => "Niveau :",
            ],
            'attributes' => [
                'id' => 'niveau',
                'min'  => '1',
                'max'  => '10',
                'step' => '1',
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
            'categorie'         => [ 'required' => false,  ],
            'niveau'         => [ 'required' => false,  ],
        ]));
    }
}