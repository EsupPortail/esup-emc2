<?php

namespace Application\Form\AssocierPoste;

use Application\Entity\Db\Structure;
use Application\Service\Poste\PosteServiceAwareTrait;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Form;

class AssocierPosteForm extends Form {
    use EntityManagerAwareTrait;
    use PosteServiceAwareTrait;

    public function init()
    {
        //Selection obligatoire AFFECTATION
        $this->add([
            'type' => Select::class,
            'name' => 'poste',
            'options' => [
                'label' => "Poste :",
                'empty_option' => "Sélectionner un poste ...",
                'value_options' => $this->generateSelectOptions(),
            ],
            'attributes' => [
                'id' => 'poste',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);

//        submit
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Associer le poste',
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
        $postes = $this->getPosteService()->getPostes();
        $options = [];
        foreach ($postes as $poste) {
            $options[$poste->getId()] = $poste->getNumeroPoste() . " - ". $poste->getDomaine()->getLibelle() . " - ".$poste->getStructure()->getLibelleCourt();
        }
        return $options;
    }

    /**
     * @param Structure $structure
     * @return AssocierPosteForm
     */
    public function reinitWithStructure($structure)
    {
        //agent
        $postes = $this->getPosteService()->getPostesByStructure($structure);
        $posteOptions = [];
        foreach ($postes as $poste) {
            $posteOptions[$poste->getId()] = $poste->getNumeroPoste();
        }
        /** @var Select $this->get('agent') */
        $this->get('poste')->setValueOptions($posteOptions);

        return $this;
    }
}