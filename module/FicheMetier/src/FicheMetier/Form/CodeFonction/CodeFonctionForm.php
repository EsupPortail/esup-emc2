<?php

namespace FicheMetier\Form\CodeFonction;

use Carriere\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;
use Carriere\Service\NiveauFonction\NiveauFonctionServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class CodeFonctionForm extends Form
{
    use NiveauFonctionServiceAwareTrait;
    use FamilleProfessionnelleServiceAwareTrait;

    public function init(): void
    {
        //Niveau de fonction
        $this->add([
            'type' => Select::class,
            'name' => 'niveau_fonction',
            'options' => [
                'label' => "Niveau de fonction <span title='Champ obligatoire'></span>:",
                'label_options' => ['disable_html_escape' => true,],
                'empty_option' => 'Sélectionner le niveau de fonction ...',
                'value_options' => $this->getNiveauFonctionService()->getNiveauxFonctionsAsOptions(),
            ],
            'attributes' => [
                'id' => 'niveau_fonction',
                'class' => 'bootstrap-selectpicker show-tick',
                'data-live-search' => 'true',
            ],
        ]);
        //Famille professionnelle
        $this->add([
            'type' => Select::class,
            'name' => 'famille',
            'options' => [
                'label' => "Famille professionnelle <span title='Champ obligatoire'></span>:",
                'label_options' => ['disable_html_escape' => true,],
                'empty_option' => 'Sélectionner une famille professionnelle ...',
                'value_options' => $this->getFamilleProfessionnelleService()->getFamillesProfessionnellesAsOptions(),
            ],
            'attributes' => [
                'id' => 'famille',
                'class' => 'bootstrap-selectpicker show-tick',
                'data-live-search' => 'true',
            ],
        ]);
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
            'niveau_fonction' => ['required' => false,],
            'famille' => ['required' => false,],
        ]));
    }

}