<?php

namespace Metier\Form\Reference;

use Metier\Service\Metier\MetierServiceAwareTrait;
use Metier\Service\Referentiel\ReferentielServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Number;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class ReferenceForm extends Form {
    use MetierServiceAwareTrait;
    use ReferentielServiceAwareTrait;

    public function init()
    {
        //metier
        $this->add([
            'name' => 'metier',
            'type' => Select::class,
            'options' => [
                'label' => 'Métier associé *: ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'empty_option' => 'Sélectionner un métier ...',
                'value_options' => $this->getMetierService()->getMetiersTypesAsMultiOptions(),
            ],
            'attributes' => [
                'id'                => 'metier',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);
        //referentiel
        $this->add([
            'name' => 'referentiel',
            'type' => Select::class,
            'options' => [
                'label' => 'Référentiel associé *: ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'empty_option' => 'Sélectionner un référentiel ...',
                'value_options' => $this->getReferentielService()->getReferentielsAsOptions(),
            ],
            'attributes' => [
                'id'                => 'referentiel',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);
        //code
        $this->add([
            'type' => Text::class,
            'name' => 'code',
            'options' => [
                'label' => "Code dans le référentiel *:",
            ],
            'attributes' => [
                'id' => 'code',
            ],
        ]);
        //lien
        $this->add([
            'type' => Text::class,
            'name' => 'lien',
            'options' => [
                'label' => "Lien vers la fiche dans le référentiel :",
            ],
            'attributes' => [
                'id' => 'lien',
            ],
        ]);
        //page
        $this->add([
            'type' => Number::class,
            'name' => 'page',
            'options' => [
                'label' => "Page dans le document PDF :",
            ],
            'attributes' => [
                'id' => 'page',
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

        //filter
        $this->setInputFilter((new Factory())->createInputFilter([
            'metier'                => [ 'required' => true,  ],
            'referentiel'           => [ 'required' => true,  ],
            'code'                  => [ 'required' => true,  ],
            'lien'                  => [ 'required' => false,  ],
            'page'                  => [ 'required' => false,  ],
        ]));
    }
}