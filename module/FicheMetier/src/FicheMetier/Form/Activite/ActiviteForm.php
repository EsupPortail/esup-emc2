<?php

namespace FicheMetier\Form\Activite;

use Laminas\Form\Element\Button;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use Referentiel\Service\Referentiel\ReferentielServiceAwareTrait;

class ActiviteForm extends Form
{
    use ReferentielServiceAwareTrait;

    public function init(): void
    {
        //libelle
        $this->add([
            'type' => Text::class,
            'name' => 'libelle',
            'options' => [
                'label' => "Libellé <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'libelle',
            ],
        ]);
        //description
//        $this->add([
//            'name' => 'description',
//            'type' => Textarea::class,
//            'options' => [
//                'label' => 'Description  : ',
//                'label_attributes' => [
//                    'class' => 'control-label',
//                ],
//            ],
//            'attributes' => [
//                'id'                => 'description',
//                'class'             => 'tinymce',
//            ],
//        ]);
        //referentiel
        $this->add([
            'type' => Select::class,
            'name' => 'referentiel',
            'options' => [
                'label' => "Référentiel <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => [ 'disable_html_escape' => true, ],
                'empty_option' => 'Sélectionner un référentiel ...',
                'value_options' => $this->getReferentielService()->getReferentielsAsOptions(),
            ],
            'attributes' => [
                'id' => 'referentiel',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);
        //idOrig
        $this->add([
            'type' => Text::class,
            'name' => 'identifiant',
            'options' => [
                'label' => "Identifiant dans le référentiel <span class='icon icon-information' title='Si aucun identifiant de renseigné, EMC2 donnera une valeur numérique à la mission.'></span>:",
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'identifiant',
            ],
        ]);
        // button
        $this->add([
            'type' => Button::class,
            'name' => 'submit',
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
            'libelle' => ['required' => true,],
//            'description' => ['required' => false,],
            'referentiel' => ['required' => true,],
            'identifiant' => ['required' => false,],
        ]));
    }
}
