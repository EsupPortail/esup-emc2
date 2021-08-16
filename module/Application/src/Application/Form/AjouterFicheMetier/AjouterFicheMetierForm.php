<?php

namespace Application\Form\AjouterFicheMetier;

use Application\Entity\Db\Agent;
use Application\Entity\Db\FicheMetier;
use Application\Service\FicheMetier\FicheMetierServiceAwareTrait;
use Metier\Entity\Db\MetierReference;
use Metier\Entity\Db\Reference;
use Metier\Service\Domaine\DomaineServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Checkbox;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Select;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class AjouterFicheMetierForm extends Form {
    use FicheMetierServiceAwareTrait;
    use DomaineServiceAwareTrait;

    private $previous;

    /**
     * @return mixed
     */
    public function getPrevious()
    {
        return $this->previous;
    }

    /**
     * @param mixed $previous
     * @return AjouterFicheMetierForm
     */
    public function setPrevious($previous)
    {
        $this->previous = $previous;
        return $this;
    }

    public function init()
    {
        //Fiche Métier
        $this->add([
            'type' => Select::class,
            'name' => 'fiche_type',
            'options' => [
                'label' => "Fiche métier :",
                'empty_option' => 'Sélectionner une fiche métier ...',
                'value_options' => $this->generateFicheTypeOptions(),
            ],
            'attributes' => [
                'id' => 'fiche_type',
                'class' => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);

        $this->add([
            'type' => Hidden::class,
            'name' => 'old',
            'attributes' => [
                'value' => $this->previous,
            ],
        ]);

        //Quotite
        $this->add([
            'type' => Select::class,
            'name' => 'quotite',
            'options' => [
                'label' => "Quotité travaillée sur la fiche métier* :",
                'empty_option' => 'Préciser la quotité associée ...',
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

        $this->setInputFilter((new Factory())->createInputFilter([
            'fiche_type'        => [ 'required' => true,  ],
            'quotite'           => [ 'required' => true,  ],
            'old'               => [ 'required' => false,  ],
        ]));
    }

    private function generateFicheTypeOptions()
    {
        $options = [];
        $fichesMetiers = $this->getFicheMetierService()->getFichesMetiersValides();

        $dictionnaire = [];
        foreach ($fichesMetiers as $ficheMetier) {
            $domaines = $ficheMetier->getMetier()->getDomaines();
            foreach ($domaines as $domaine) {
                $dictionnaire[($domaine) ? $domaine->getLibelle() : "Sans domaine"][] = $ficheMetier;
            }
        }

        ksort($dictionnaire);
        foreach ($dictionnaire as $clef => $listing) {
            $optionsoptions = [];
            /** @var FicheMetier $fiche */
            foreach ($listing as $fiche) {
                $references = [];
                /** @var Reference $reference */
                foreach ($fiche->getMetier()->getReferences() as $reference) {
                    $references[] = $reference->getTitre();
                }
                $str_references = implode(", ", $references);
                $optionsoptions[$fiche->getId()] = $fiche->getMetier()->getLibelle() . (!empty($references)?" (".$str_references.")":"") ;
            }
            $array = [
                'label' => $clef,
                'options' => $optionsoptions,
            ];
            $options[] = $array;
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

    /**
     * @param Agent $agent
     */
    public function reinitWithAgent(Agent $agent)
    {
        /** @var Select $ficheSelect */
        $ficheSelect = $this->get('fiche_type');

        $niveau = $agent->getMeilleurNiveau()->getNiveau();
        if ($niveau === null) return;

        /** @var array $fiches */
        $fiches = $this->getFicheMetierService()->getFichesMetiersWithNiveau($niveau);

        $options = [];
        $dictionnaire = [];
        foreach ($fiches as $ficheMetier) {
            $domaines = $ficheMetier->getMetier()->getDomaines();
            foreach ($domaines as $domaine) {
                $dictionnaire[($domaine) ? $domaine->getLibelle() : "Sans domaine"][] = $ficheMetier;
            }
        }

        ksort($dictionnaire);
        foreach ($dictionnaire as $clef => $listing) {
            $optionsoptions = [];
            /** @var FicheMetier $fiche */
            foreach ($listing as $fiche) {
                $metier = $fiche->getMetier();
                $references = [];
                /** @var Reference $reference */
                foreach ($metier->getReferences() as $reference) {
                    $references[] = $reference->getTitre();
                }
                $str_references = implode(", ", $references);
                $niveaux = "";
//                if ($metier->getNiveaux()) $niveaux .= " [".$metier->getNiveaux()->getBorneInferieure().":".$metier->getNiveaux()->getBorneSuperieure()."]";
//                $niveaux .= " >>> ".$agent->getMeilleurNiveau();
                $optionsoptions[$fiche->getId()] = $metier->getLibelle() . (!empty($references)?" (".$str_references.")":"") . $niveaux;
            }
            $array = [
                'label' => $clef,
                'options' => $optionsoptions,
            ];
            $options[] = $array;
        }

        $ficheSelect->setValueOptions($options);
    }
}