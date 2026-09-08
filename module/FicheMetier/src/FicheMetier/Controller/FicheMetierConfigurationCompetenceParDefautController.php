<?php

namespace FicheMetier\Controller;

use Element\Form\SelectionCompetence\SelectionCompetenceFormAwareTrait;
use Element\Service\Competence\CompetenceServiceAwareTrait;
use FicheMetier\Entity\Db\FicheMetierConfigurationCompetenceParDefaut;
use FicheMetier\Provider\Parametre\FicheMetierParametres;
use FicheMetier\Service\FicheMetier\FicheMetierServiceAwareTrait;
use FicheMetier\Service\FicheMetierConfigurationCompetenceParDefaut\FicheMetierConfigurationCompetenceParDefautServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\View\Model\ViewModel;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;

/** @method FlashMessenger flashMessenger() */

/**
 * On ne proposera pas de modification, car cela ne fait pas de sens pour le moment (la seule information saisie est
 * la compétence).
 * Ainsi, on pourra aussi faire l'ajout de plusieurs competences
 */

class FicheMetierConfigurationCompetenceParDefautController extends AbstractActionController
{
    use CompetenceServiceAwareTrait;
    use FicheMetierServiceAwareTrait;
    use FicheMetierConfigurationCompetenceParDefautServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use SelectionCompetenceFormAwareTrait;

    public function indexAction(): ViewModel
    {
        $competences = $this->getFicheMetierConfigurationCompetenceParDefautService()->getFicheMetierConfigurationCompetencesParDefaut(true);
        $parametres        =  $this->getParametreService()->getParametresByCategorieCode(FicheMetierParametres::TYPE);

        $vm = new ViewModel([
            'competences' => $competences,
            'parametres'   => $parametres,
        ]);
        $vm->setTemplate('fiche-metier/configuration/competences-par-defaut');
        return $vm;
    }

    public function ajouterAction(): ViewModel
    {
        $form = $this->getSelectionCompetenceForm();
        $form->setAttribute('action',$this->url()->fromRoute('fiche-metier/configuration/competences-par-defaut/ajouter', [], [], true));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();

            if (isset($data['competences'])) {
                foreach ($data['competences'] as $competenceId) {
                    $competence = $this->getCompetenceService()->getCompetence($competenceId);
                    if (!$this->getFicheMetierConfigurationCompetenceParDefautService()->hasCompetence($competence))
                    {
                        $defaut = new FicheMetierConfigurationCompetenceParDefaut();
                        $defaut->setCompetence($competence);
                        $this->getFicheMetierConfigurationCompetenceParDefautService()->create($defaut);
                    }
                }
            }
            exit();
        }

        $vm = new ViewModel([
            'title' => "Ajouter des competences par défaut",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function supprimerAction(): ViewModel
    {
        $competenceParDefaut = $this->getFicheMetierConfigurationCompetenceParDefautService()->getRequestedFicheMetierConfigurationCompetenceParDefaut($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getFicheMetierConfigurationCompetenceParDefautService()->delete($competenceParDefaut);
            exit();
        }

        $vm = new ViewModel();
        if ($competenceParDefaut !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de l'competence par défaut" . $competenceParDefaut->getCompetence()->getLibelle(),
                'text' => "La suppression est définitive, êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('fiche-metier/configuration/competences-par-defaut/supprimer', ["competence-par-defaut" => $competenceParDefaut->getId()], [], true),
            ]);
        }
        return $vm;
    }

    public function reappliquerAction(): ViewModel
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") {
                $fiches = $this->getFicheMetierService()->getFichesMetiers();
                foreach ($fiches as $fiche) {
                    $this->getFicheMetierConfigurationCompetenceParDefautService()->applyDefault($fiche);
                }
                $this->flashMessenger()->addSuccessMessage("Ré-application terminée");
                exit();
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/confirmation');
        $vm->setVariables([
            'title' => "Réapplication des compétences par défaut sur les fiches métiers",
            'text' => "La réapplication modifiera toutes les fiches métiers. Êtes-vous sûr&middot;e de vouloir continuer ?",
            'action' => $this->url()->fromRoute('fiche-metier/configuration/competences-par-defaut/reappliquer', [], [], true),
        ]);
        return $vm;

    }

}