<?php

namespace EntretienProfessionnel\Form\CampagneConfigurationPresaisie;

use UnicaenRenderer\Service\Macro\MacroServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use Laminas\Validator\Callback;
use UnicaenAutoform\Service\Champ\ChampServiceAwareTrait;
use UnicaenAutoform\Service\Formulaire\FormulaireServiceAwareTrait;

class CampagneConfigurationPresaisieForm extends Form
{
    use FormulaireServiceAwareTrait;
    use ChampServiceAwareTrait;
    use MacroServiceAwareTrait;
    public function init(): void {

        //formulaire-from (suppose du javascript)
        $this->add([
            'type' => Select::class,
            'name' => 'formulaire',
            'options' => [
                'label' => "Formulaire dans lequel faire la pré-saisie<span class='icon icon-obligatoire' title='Champ obligatoire'></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
                'value_options' => $this->getFormulaireService()->getFormulairesAsOptions(),
                'empty_option' => "Choisissez un formulaire ...",
            ],
            'attributes' => [
                'id' => 'formulaire',
            ],
        ]);
        //from
        $this->add([
            'type' => Select::class,
            'name' => 'champ',
            'options' => [
                'label' => "Champ qui sera présaisi<span class='icon icon-obligatoire' title='Champ obligatoire'></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
                'value_options' => $this->getChampService()->getChampsAsOptions(),
                'empty_option' => "Choisissez un champ ...",
            ],
            'attributes' => [
                'id' => 'champ',
            ],
        ]);
        //formulaire-to (suppose du javascript)
        $this->add([
            'type' => Select::class,
            'name' => 'macro',
            'options' => [
                'label' => "Macro qui sera utilisée pour la présaisie<span class='icon icon-obligatoire' title='Champ obligatoire'></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
                //todo préfiltrer le liste avec des variables (car on pourrai les connaitre)
                'value_options' => $this->getMacroService()->getMacrosAsOptions(['agent', 'campagne']),
                'empty_option' => "Choisissez une macro ...",
            ],
            'attributes' => [
                'id' => 'macro',
            ],
        ]);
        //button
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
                'class' => 'btn btn-success',
            ],
        ]);
        //inputfilter
        $this->setInputFilter((new Factory())->createInputFilter([
            'formulaire'         => [ 'required' => true, ],
            'champ'              => [ 'required' => true, ],
            'macro'              => [ 'required' => true, ],
        ]));
    }
}
