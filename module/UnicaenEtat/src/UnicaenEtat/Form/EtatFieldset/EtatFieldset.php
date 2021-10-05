<?php

namespace UnicaenEtat\Form\EtatFieldset;

use UnicaenEtat\Entity\Db\Etat;
use UnicaenEtat\Service\Etat\EtatServiceAwareTrait;
use Zend\Form\Element\Select;
use Zend\Form\Fieldset;

class EtatFieldset extends Fieldset
{
    use EtatServiceAwareTrait;

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
    }

    /**
     * @param Etat[] $etats
     */
    public function resetEtats(array $etats)
    {
        $options = [];
        /** @var Etat $etat */
        foreach ($etats as $etat) {
            $options[$etat->getId()] = $this->getEtatService()->optionify($etat);
        }

        /** @var Select $select */
        $select = $this->get('etat');
        $select->setValueOptions($options);
    }
}