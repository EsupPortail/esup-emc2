<?php

namespace EntretienProfessionnel\Form\CampagneConfigurationRecopie;

use Laminas\Form\Element\Button;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use Laminas\Validator\Callback;
use UnicaenAutoform\Service\Champ\ChampServiceAwareTrait;
use UnicaenAutoform\Service\Formulaire\FormulaireServiceAwareTrait;

class CampagneConfigurationRecopieForm extends Form
{
    use FormulaireServiceAwareTrait;
    use ChampServiceAwareTrait;
    public function init(): void {

        //formulaire-type
        $this->add([
            'type' => Select::class,
            'name' => 'type',
            'options' => [
                'label' => "Type de formulaire <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => [ 'disable_html_escape' => true, ],
                'value_options' => [
                    'CREP' => 'Formulaire de compte-rendu de entretien professionnel',
                    'CREF' => 'Formulaire de compte-rendu de formation',
                ],
                'empty_option' => "Choisissez un type de formulaire ...",
            ],
            'attributes' => [
                'id' => 'type',
            ],
        ]);
        //formulaire-from (suppose du javascript)
        $this->add([
            'type' => Select::class,
            'name' => 'formulaire-from',
            'options' => [
                'label' => "Formulaire depuis lequel recopier :",
                'label_options' => [ 'disable_html_escape' => true, ],
                'value_options' => $this->getFormulaireService()->getFormulairesAsOptions(),
                'empty_option' => "Choisissez un formulaire ...",
            ],
            'attributes' => [
                'id' => 'formulaire-from',
            ],
        ]);
        //from
        $this->add([
            'type' => Select::class,
            'name' => 'champ-from',
            'options' => [
                'label' => "Champ à recopier <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => [ 'disable_html_escape' => true, ],
                'value_options' => $this->getChampService()->getChampsAsOptions(),
                'empty_option' => "Choisissez un champ ...",
            ],
            'attributes' => [
                'id' => 'champ-from',
            ],
        ]);
        //formulaire-to (suppose du javascript)
        $this->add([
            'type' => Select::class,
            'name' => 'formulaire-to',
            'options' => [
                'label' => "Formulaire dans lequel faire la recopie :",
                'label_options' => [ 'disable_html_escape' => true, ],
                'value_options' => $this->getFormulaireService()->getFormulairesAsOptions(),
                'empty_option' => "Choisissez un formulaire ...",
            ],
            'attributes' => [
                'id' => 'formulaire-to',
            ],
        ]);
        //to
        $this->add([
            'type' => Select::class,
            'name' => 'champ-to',
            'options' => [
                'label' => "Champ à remplir <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => [ 'disable_html_escape' => true, ],
                'value_options' => $this->getChampService()->getChampsAsOptions(),
                'empty_option' => "Choisissez un champ ...",
            ],
            'attributes' => [
                'id' => 'champ-to',
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
            'type'                  => [ 'required' => true, ],
            'formulaire-from'       => [ 'required' => false, ],
            'champ-from'            => [
                'required' => true,
                'validators' => [[
                    'name' => Callback::class,
                    'options' => [
                        'messages' => [
                            Callback::INVALID_VALUE => "Les types des deux champs sont incompatibles",
                        ],
                        'callback' => function ($value, $context = []) {
                            $from = $this->getChampService()->getChamp($context['champ-from']);
                            $to   = $this->getChampService()->getChamp($context['champ-to']);
                            if ($from?->getType() !== $to?->getType()) return false;
                            return true;
                        },
                        //'break_chain_on_failure' => true,
                    ],
                ]],
            ],
            'formulaire-to'         => [ 'required' => false, ],
            'champ-to'              => [ 'required' => true, ],
        ]));
    }
}
