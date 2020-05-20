<?php

namespace Application\Form\MetierReference;

use Application\Service\Metier\MetierServiceAwareTrait;
use Application\Service\MetierReferentiel\MetierReferentielServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Number;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class MetierReferenceForm extends Form {
    use MetierServiceAwareTrait;
    use MetierReferentielServiceAwareTrait;

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
                'value_options' => $this->getMetierReferentielService()->getMetiersReferentielsAsOptions(),
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