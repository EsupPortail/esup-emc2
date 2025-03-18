<?php

namespace FicheMetier\Controller;

use Application\Service\Agent\AgentServiceAwareTrait;
use Element\Entity\Db\ApplicationElement;
use Element\Entity\Db\CompetenceElement;
use Element\Service\Niveau\NiveauServiceAwareTrait;
use FicheMetier\Service\FicheMetier\FicheMetierServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class GraphiqueController extends AbstractActionController
{
    use AgentServiceAwareTrait;
    use FicheMetierServiceAwareTrait;
    use NiveauServiceAwareTrait;

    public function graphiqueCompetencesAction(): ViewModel
    {
        $ficheMetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $agent = $this->getAgentService()->getRequestedAgent($this);

        $dictionnaire = $this->getFicheMetierService()->getCompetencesDictionnaires($ficheMetier, true);
        $dictionnaire = array_filter($dictionnaire, function ($item) {
            return ($item['entite'])->isClef();
        });
        $labels = [];
        $values = [];

        $valuesFiche = [];
        foreach ($dictionnaire as $entry) {
            /** @var CompetenceElement $element */
            $element = $entry['entite'];
            $labels[] = substr($element->getLibelle(), 0, 200);
            $valuesFiche[] = ($element->getNiveauMaitrise()) ? $element->getNiveauMaitrise()->getNiveau() : "'-'";
        }
        $values[] = [
            'title' => "pré-requis",
            'values' => $valuesFiche,
            'color' => "255,0,0",
        ];

        if ($agent !== null) {
            $valuesAgent = [];
            $competences = $agent->getCompetenceListe();
            foreach ($dictionnaire as $entry) {
                /** @var CompetenceElement $element */
                $element = $entry['entite'];
                $id = $element->getCompetence()->getId();
                $niveau = (isset($competences[$id]) and $competences[$id]->getNiveauMaitrise()) ? $competences[$id]->getNiveauMaitrise()->getNiveau() : "'-'";
                $valuesAgent[] = $niveau;
            }
            $values[] = [
                'title' => "Acquis",
                'values' => $valuesAgent,
                'color' => "0,255,0",
            ];
        }

        $libelle = $ficheMetier->getMetier()->getLibelle();
        $vm = new ViewModel([
            'title' => "Diagramme des compétences pour la fiche métier <strong>" . $libelle . "</strong>",
            'agent' => $agent,
            'label' => $labels,
            'values' => $values,
            'niveaux' => $this->getNiveauService()->getMaitrisesNiveauxAsOptions('Competence','niveau','ASC',false, true),
        ]);
        $vm->setTemplate('fiche-metier/graphique/graphique-radar');
        return $vm;
    }

    public function graphiqueApplicationsAction(): ViewModel
    {
        $ficheMetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $agent = $this->getAgentService()->getRequestedAgent($this);

        $dictionnaire = $this->getFicheMetierService()->getApplicationsDictionnaires($ficheMetier, true);
        $dictionnaire = array_filter($dictionnaire, function ($item) {
            return ($item['entite'])->isClef();
        });
        $labels = [];
        $values = [];

        $valuesFiche = [];
        foreach ($dictionnaire as $entry) {
            /** @var ApplicationElement $element */
            $element = $entry['entite'];
            $labels[] = substr($element->getLibelle(), 0, 200);
            $valuesFiche[] = ($element->getNiveauMaitrise()) ? $element->getNiveauMaitrise()->getNiveau() : "'-'";
        }
        $values[] = [
            'title' => "pré-requis",
            'values' => $valuesFiche,
            'color' => "255,0,0",
        ];

        if ($agent !== null) {
            $valuesAgent = [];
            /** @var ApplicationElement[] $applications */
            $applications = $agent->getApplicationListe();
            foreach ($dictionnaire as $entry) {
                /** @var ApplicationElement $element */
                $element = $entry['entite'];
                $id = $element->getApplication()->getId();
                $niveau = (isset($applications[$id]) and $applications[$id]->getNiveauMaitrise()) ? $applications[$id]->getNiveauMaitrise()->getNiveau() : "'-'";
                $valuesAgent[] = $niveau;
            }
            $values[] = [
                'title' => "Acquis",
                'values' => $valuesAgent,
                'color' => "0,255,0",
            ];
        }

        $libelle = $ficheMetier->getMetier()->getLibelle();
        $vm = new ViewModel([
            'title' => "Diagramme des applications pour la fiche métier <strong>" . $libelle . "</strong>",
            'agent' => $agent,
            'label' => $labels,
            'values' => $values,
            'niveaux' => $this->getNiveauService()->getMaitrisesNiveauxAsOptions('Application','niveau','ASC',false, true),
        ]);
        $vm->setTemplate('fiche-metier/graphique/graphique-radar');
        return $vm;
    }
}