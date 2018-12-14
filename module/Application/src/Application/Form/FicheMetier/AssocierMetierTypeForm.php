<?php

namespace Application\Form\FicheMetier;

use Application\Service\FicheMetier\FicheMetierServiceAwareTrait;;
use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Form;

class AssocierMetierTypeForm extends Form {
    use FicheMetierServiceAwareTrait;

    public function init()
    {
        //Selection FAMILLE
        $this->add([
            'type' => Select::class,
            'name' => 'metier_type',
            'options' => [
                'label' => "Fiche métier type :",
                'value_options' => $this->generateSelectOptions(),
            ],
            'attributes' => [
                'id' => 'metier_type',
            ],
        ]);

//        submit
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Associer un metier type',
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

    private function generateSelectOptions()
    {
        $metiers = $this->getFicheMetierService()->getFichesMetiersTypes();
        $options = [];
        $options[0] = "Sélectionner un métier type ... ";
        foreach ($metiers as $metier) {
            $options[$metier->getId()] = $metier->getMetier()->getLibelle();
        }
        return $options;

    }
}