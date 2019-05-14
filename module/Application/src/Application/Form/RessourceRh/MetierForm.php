<?php

namespace Application\Form\RessourceRh;

use Application\Entity\Db\MetierFamille;
use Application\Service\RessourceRh\RessourceRhServiceAwareTrait;
use DoctrineModule\Form\Element\ObjectSelect;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Form;

class MetierForm extends Form {
    use RessourceRhServiceAwareTrait;

    public function init()
    {
        //Famille professionnelle
        $this->add([
            'type' => Select::class,
            'name' => 'famille',
            'options' => [
                'label' => "Famille professionnelle* :",
                'empty_option' => "Sélectionner une famille ...",
                'value_options' => $this->getRessourceRhService()->getMetiersFamillesAsOptions(),
            ],
            'attributes' => [
                'id' => 'famille',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);
        //Domaine
        $this->add([
            'type' => Select::class,
            'name' => 'domaine',
            'options' => [
                'label' => "Domaine* :",
                'empty_option' => "Sélectionner un domaine ...",
                'value_options' => $this->getRessourceRhService()->getDomainesAsOptions(),
            ],
            'attributes' => [
                'id' => 'domaine',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
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
        // button
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer le métier',
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