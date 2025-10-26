<?php

namespace FicheMetier\Form\CodeEmploiType;


use Carriere\Service\FonctionType\FonctionTypeServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class CodeEmploiTypeForm extends Form
{
    use FonctionTypeServiceAwareTrait;
    public function init(): void
    {
        // code fonction
        $this->add([
            'type' => Select::class,
            'name' => 'code_fonction',
            'options' => [
                'label' => "Code Fonction <span class='icon icon-obligatoire' title='Obligatoire'></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
                'empty_option' => 'Sélectionner une fiche métier ...',
                'value_options' => $this->getFonctionTypeService()->getFonctionsTypesAsOptions(),
            ],
            'attributes' => [
                'id' => 'code_fonction',
                'class' => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);
        // complement
        $this->add([
            'type' => Text::class,
            'name' => 'complement',
            'options' => [
                'label' => "Complément <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'complement',
            ],
        ]);
        // action
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
            'code_fonction' => [ 'required' => true, ],
            'complement' => [ 'required' => true, ],
        ]));
    }
}
