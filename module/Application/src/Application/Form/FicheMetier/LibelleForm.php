<?php

namespace Application\Form\FicheMetier;

use Metier\Service\Metier\MetierServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;

class LibelleForm extends Form
{
    use MetierServiceAwareTrait;

    public function init()
    {
        // libelle
        $this->add([
            'type' => Select::class,
            'name' => 'metier',
            'options' => [
                'label' => "Libellé du métier :",
                'empty_option' => "Sélectionner un metier ...",
                'value_options' =>
                //$this->getOptions(),
                    $this->getMetierService()->getMetiersTypesAsMultiOptions(),
            ],
            'attributes' => [
                'id' => 'metier',
                'class' => 'bootstrap-selectpicker show-tick',
                'data-live-search' => 'true',
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
    }
}