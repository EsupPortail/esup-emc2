<?php

namespace EmploiRepere\Form\EmploiRepereCodeFonctionFicheMetier;

use EmploiRepere\Service\EmploiRepere\EmploiRepereServiceAwareTrait;
use FicheMetier\Service\CodeFonction\CodeFonctionServiceAwareTrait;
use FicheMetier\Service\FicheMetier\FicheMetierServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class EmploiRepereCodeFonctionFicheMetierForm extends Form
{
    use EmploiRepereServiceAwareTrait;
    use CodeFonctionServiceAwareTrait;
    use FicheMetierServiceAwareTrait;

    public function init(): void
    {
        //select (ER)
        $this->add([
            'type' => Select::class,
            'name' => 'emploi-repere',
            'options' => [
                'label' => "Emploi-Repère :",
                'empty_option' => "Sélectionner un emploi-repère",
                'value_options' =>
                    $this->getEmploiRepereService()->getEmploisReperesAsOptions(),
            ],
            'attributes' => [
                'id' => 'emploi-repere',
                'class' => 'bootstrap-selectpicker show-tick',
                'data-live-search' => 'true',
            ],
        ]);

        //select (CF)
        $this->add([
            'type' => Select::class,
            'name' => 'code-fonction',
            'options' => [
                'label' => "Code fonction :",
                'empty_option' => "Sélectionner un code fonction",
                'value_options' =>
                    $this->getCodeFonctionService()->getCodesFonctionsAsOptions(),
            ],
            'attributes' => [
                'id' => 'code-fonction',
                'class' => 'bootstrap-selectpicker show-tick',
                'data-live-search' => 'true',
            ],
        ]);
        //select (FM)
        $this->add([
            'type' => Select::class,
            'name' => 'fiche-metier',
            'options' => [
                'label' => "Fiche Métier :",
                'empty_option' => "Sélectionner un fiche métier",
                'value_options' =>
                    $this->getFicheMetierService()->getFichesMetiersAsOptionGroup(),
            ],
            'attributes' => [
                'id' => 'fiche métier',
                'class' => 'bootstrap-selectpicker show-tick',
                'data-live-search' => 'true',
            ],
        ]);
        //button
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

        //inputfilter
        $this->setInputFilter((new Factory())->createInputFilter([
            'emploi-repere'          => [ 'required' => true, ],
            'code-fonction'          => [ 'required' => true, ],
            'fiche-metier'           => [ 'required' => true, ],
        ]));



    }
}
