<?php

namespace UnicaenEtat\Form\SelectionEtat;

use UnicaenEtat\Entity\Db\EtatType;
use UnicaenEtat\Service\Etat\EtatServiceAwareTrait;
use UnicaenEtat\Service\EtatType\EtatTypeServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class SelectionEtatForm extends Form {
    use EtatServiceAwareTrait;
    use EtatTypeServiceAwareTrait;

    public function init()
    {
        $this->add([
            'type' => Select::class,
            'name' => 'etat',
            'options' => [
                'label' => "État * :",
                'empty_option' => "Sélectionner un état ...",
                'value_options' => $this->getEtatService()->getEtatsAsOption(),
            ],
            'attributes' => [
                'id' => 'etat',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);
        //submit
        $this->add([
            'type' => Button::class,
            'name' => 'bouton',
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

        $this->setInputFilter((new Factory())->createInputFilter([
            'etat' => [
                'required' => true,
            ],
        ]));
    }

    /**
     * @param string|EtatType $type
     */
    public function reinit($type)
    {
        if (is_string($type)) $type = $this->getEtatTypeService()->getEtatTypeByCode($type);
        $this->get('etat')->setValueOptions($this->getEtatService()->getEtatsAsOption($type));
    }
}