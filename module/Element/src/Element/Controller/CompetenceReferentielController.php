<?php

namespace Element\Controller;

use Element\Entity\Db\CompetenceReferentiel;
use Element\Form\CompetenceReferentiel\CompetenceReferentielFormAwareTrait;
use Element\Service\Competence\CompetenceServiceAwareTrait;
use Element\Service\CompetenceElement\CompetenceElementServiceAwareTrait;
use Element\Service\CompetenceReferentiel\CompetenceReferentielServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class CompetenceReferentielController extends AbstractActionController
{
    use CompetenceServiceAwareTrait;
    use CompetenceReferentielServiceAwareTrait;
    use CompetenceElementServiceAwareTrait;
    use CompetenceReferentielFormAwareTrait;

    public function indexAction(): ViewModel
    {
        $referentiels = $this->getCompetenceReferentielService()->getCompetencesReferentiels();

        return new ViewModel([
            'referentiels' => $referentiels,
        ]);
    }

    public function afficherAction(): ViewModel
    {
        $referentiel = $this->getCompetenceReferentielService()->getRequestedCompetenceReferentiel($this);
        return new ViewModel([
            'title' => "Affichage d'un référentiel de compétences",
            'referentiel' => $referentiel,
        ]);
    }

    public function ajouterAction(): ViewModel
    {
        $referentiel = new CompetenceReferentiel();
        $form = $this->getCompetenceReferentielForm();
        $form->setAttribute('action', $this->url()->fromRoute('element/competence-referentiel/ajouter', [], [], true));
        $form->bind($referentiel);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCompetenceReferentielService()->create($referentiel);
                exit();
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'un référentiel de compétences",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction(): ViewModel
    {
        $referentiel = $this->getCompetenceReferentielService()->getRequestedCompetenceReferentiel($this);
        $form = $this->getCompetenceReferentielForm();
        $form->setAttribute('action', $this->url()->fromRoute('element/competence-referentiel/modifier', ['competence-referentiel' => $referentiel->getId()], [], true));
        $form->bind($referentiel);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCompetenceReferentielService()->update($referentiel);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/default-form');
        $vm->setVariables([
            'title' => "Modification d'un référentiel de compétences",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction(): Response
    {
        $referentiel = $this->getCompetenceReferentielService()->getRequestedCompetenceReferentiel($this);
        $this->getCompetenceReferentielService()->historise($referentiel);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('element/competence-referentiel', [], [], true);
    }

    public function restaurerAction(): Response
    {
        $referentiel = $this->getCompetenceReferentielService()->getRequestedCompetenceReferentiel($this);
        $this->getCompetenceReferentielService()->restore($referentiel);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('element/competence-referentiel', [], [], true);
    }

    public function supprimerAction(): ViewModel
    {
        $referentiel = $this->getCompetenceReferentielService()->getRequestedCompetenceReferentiel($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getCompetenceReferentielService()->delete($referentiel);
            exit();
        }

        $vm = new ViewModel();
        if ($referentiel !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du référentiel de compétences  " . $referentiel->getLibelleCourt(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('element/competence-referentiel/supprimer', ["competence-referentiel" => $referentiel->getId()], [], true),
            ]);
        }
        return $vm;
    }

    public function viderAction(): ViewModel
    {
        $referentiel = $this->getCompetenceReferentielService()->getRequestedCompetenceReferentiel($this);

        $comptences = $this->getCompetenceService()->getCompetencesByRefentiel($referentiel);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") {
                foreach ($comptences as $competence) $this->getCompetenceService()->delete($competence);
            }
            exit();
        }


        $used = [];
        foreach ($comptences as $competence) {
            $elements = $this->getCompetenceElementService()->getElementsByCompetence($competence);
            if (!empty($elements)) $used[$competence->getId()] = $elements;
        }
        $warning = null;
        if (!empty($used)) {
            $warning  = "Parmi les ".count($comptences)." compétences du référentiel ".$referentiel->getLibelleCourt()
                ." ".count($used)." compétences sont utilisées.<br> Vider le référentiel cassera ces usages. <br><br> Liste des compétences utilisées : <ul>";
            foreach ($used as $competence) {
                $warning .= "<li>".current($competence)->getCompetence()->getLibelle()."</li>";
            }
            $warning .= "</ul>";
        }
        $vm = new ViewModel();
        if ($referentiel !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Vidage du référentiel de compétences  " . $referentiel->getLibelleCourt(),
                'text' => "Le vidage est défénitif et supprimera définitivement les compétences de celui-ci.",
                'warning' => $warning,
                'action' => $this->url()->fromRoute('element/competence-referentiel/vider', ["competence-referentiel" => $referentiel->getId()], [], true),
            ]);
        }
        return $vm;
    }
}