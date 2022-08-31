<?php

namespace Formation\Form\Formation;

use Application\Form\HasDescription\HasDescriptionFieldset;
use Formation\Service\FormationGroupe\FormationGroupeServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Radio;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class FormationForm extends Form
{
    use FormationGroupeServiceAwareTrait;

    public function init()
    {

        //libelle
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
        //groupe
        $this->add([
            'name' => 'groupe',
            'type' => Select::class,
            'options' => [
                'label' => 'Groupe de la formation : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'empty_option' => 'Sélectionner un groupe ...',
                'value_options' => $this->getFormationGroupeService()->getFormationsGroupesAsOption(),
            ],
            'attributes' => [
                'class' => 'description form-control show-tick',
                'data-live-search'  => 'true',
                'style' => 'height:300px;',
            ]
        ]);
        //description
        $this->add([
            'name' => 'HasDescription',
            'type' => HasDescriptionFieldset::class,
            'attributes' => [
                'id' => 'description',
            ],
        ]);
        //lien
        $this->add([
            'type' => Text::class,
            'name' => 'lien',
            'options' => [
                'label' => "Lien :",
            ],
            'attributes' => [
                'id' => 'lien',
            ],
        ]);

        // type
        $this->add([
            'type' => Radio::class,
            'name' => 'affichage',
            'options' => [
                'label' => "Affichage dans le 'plan de formation à venir'  <span class='icon obligatoire' title='Champ obligatoire'></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
                'value_options' => [
                    true => "Oui",
                    false => "Non",
                ],
            ],
            'attributes' => [
                'id' => 'affichage',
            ],
        ]);
        //submit
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer l\'activité',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ],
        ]);

        //filter
        $this->setInputFilter((new Factory())->createInputFilter([
            'libelle' => ['required' => true,],
            'groupe' => ['required' => false,],
            'description' => ['required' => false,],
            'lien' => ['required' => false,],
            'affichage' => ['required' => true,],
        ]));
    }
}