<?php

namespace Application\Form\FicheMetier;

use Application\Service\Activite\ActiviteServiceAwareTrait;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Form;

class ActiviteExistanteForm extends Form {
    use ActiviteServiceAwareTrait;
    use EntityManagerAwareTrait;

    public function init()
    {
        $object = $this->getObject();
        $this->add([
            'type' => Select::class,
            'name' => 'activite',
            'options' => [
                'label' => "Activité :",
                'value_options' => $this->getActiviteService()->getActivitesAsOptions(),
            ],
            'attributes' => [
                'id' => 'activite',
                'class' => 'bootstrap-selectpicker show-tick',
                'data-live-search' => true,
            ],
        ]);
        // button
        $this->add([
            'type' => Button::class,
            'name' => 'submit',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Ajouter',
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