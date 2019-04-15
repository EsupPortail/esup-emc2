<?php

namespace Application\Form\AjouterFicheMetier;

use Application\Service\FicheMetier\FicheMetierServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Checkbox;
use Zend\Form\Element\Select;
use Zend\Form\Form;

class AjouterFicheMetierForm extends Form {
    use FicheMetierServiceAwareTrait;

    public function init()
    {
        //Fiche Type
        $this->add([
            'type' => Select::class,
            'name' => 'fiche_type',
            'options' => [
                'label' => "Fiche type :",
                'value_options' => $this->generateFicheTypeOptions(),
            ],
            'attributes' => [
                'id' => 'fiche_type',
            ],
        ]);

        //Quotite
        //Fiche Type
        $this->add([
            'type' => Select::class,
            'name' => 'quotite',
            'options' => [
                'label' => "Quotité* :",
                'value_options' => $this->generateQuotiteOptions(),
            ],
            'attributes' => [
                'id' => 'quotite',
            ],
        ]);

        //Principale
        $this->add([
            'type' => Checkbox::class,
            'name' => 'est_principale',
            'options' => [
                'label' => "est la fiche principale",
            ],
            'attributes' => [
                'id' => 'est_principale',
            ],
        ]);

//        submit
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer l\'association',
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

    private function generateFicheTypeOptions()
    {
        $fiches = $this->getFicheMetierService()->getFichesMetiersTypes();
        $options = [];
        $options[0] = "Sélectionner une fiche type ... ";
        foreach ($fiches as $fiche) {
            $options[$fiche->getId()] = $fiche->getMetier()->getLibelle();
        }
        return $options;
    }

    private function generateQuotiteOptions()
    {
        $options = [];
        for($i = 20; $i <= 100; $i+=10) {
            $options[$i] = $i. '%';
        }
        return $options;

    }
}