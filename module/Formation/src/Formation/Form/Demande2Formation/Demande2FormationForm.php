<?php

namespace Formation\Form\Demande2Formation;

use Formation\Service\Axe\AxeServiceAwareTrait;
use Formation\Service\FormationGroupe\FormationGroupeServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Number;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class Demande2FormationForm extends Form {
    use AxeServiceAwareTrait;
    use FormationGroupeServiceAwareTrait;

    public function init(): void
    {
        //groupe
        $axe = $this->getAxeService()->getAxeByLibelle("Formations externes");
        $this->add([
            'type' => Select::class,
            'name' => 'groupe',
            'options' => [
                'label' => "Groupe de formation <span class='icon obligatoire' title='Champ obligatoire'></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
                'empty_option' => 'Sélectionner un groupe ...',
                'value_options' => $this->getFormationGroupeService()->getFormationsGroupesByAxeAsOption($axe),
            ],
            'attributes' => [
                'id'                => 'groupe',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);

        //libelle
        $this->add([
            'type' => Text::class,
            'name' => 'libelle',
            'options' => [
                'label' => "Intitulé du stage <span class='icon obligatoire' title='Champ obligatoire'></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'libelle',
            ],
        ]);
        //volume
        $this->add([
            'type' => Number::class,
            'name' => "volume",
            'options' => [
                'label' => "Volume horaire de la formation <span class='icon icon-asterisque' title='Champ obligatoire pour les volumes horaires' ></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'volume',
            ],
        ]);
        //suivi
        $this->add([
            'type' => Number::class,
            'name' => "suivi",
            'options' => [
                'label' => "Volume suivi par l'agent  <span class='icon icon-asterisque' title='Champ obligatoire pour les volumes horaires' ></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'suivi',
            ],
        ]);

        //-- Bouton ----------------------------------------------------------------------------------------------------

        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer',
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-success',
            ],
        ]);

        //-- Input filter ------------------------------

        $this->setInputFilter((new Factory())->createInputFilter([
            'groupe'            => [ 'required' => true,  ],
            'libelle'            => [ 'required' => true,  ],
            'volume         '    => [ 'required' => true,  ],
            'suivi'              => [ 'required' => true,  ],
        ]));
    }
}