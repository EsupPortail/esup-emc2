<?php

namespace Application\Controller;

use Application\Entity\Db\Activite;
use Application\Entity\Db\ActiviteDescription;
use Application\Form\Activite\ActiviteForm;
use Application\Form\Activite\ActiviteFormAwareTrait;
use Application\Form\ModifierDescription\ModifierDescriptionFormAwareTrait;
use Application\Form\ModifierLibelle\ModifierLibelleFormAwareTrait;
use Application\Form\SelectionApplication\SelectionApplicationFormAwareTrait;
use Application\Form\SelectionCompetence\SelectionCompetenceFormAwareTrait;
use Application\Form\SelectionFormation\SelectionFormationFormAwareTrait;
use Application\Service\Activite\ActiviteServiceAwareTrait;
use Application\Service\ActiviteDescription\ActiviteDescriptionServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ActiviteController  extends AbstractActionController {
    /** Traits associé aux services */
    use ActiviteServiceAwareTrait;
    use ActiviteDescriptionServiceAwareTrait;
    /** Traits associé aux formulaires */
    use ActiviteFormAwareTrait;
    use ModifierDescriptionFormAwareTrait;
    use ModifierLibelleFormAwareTrait;
    use SelectionApplicationFormAwareTrait;
    use SelectionCompetenceFormAwareTrait;
    use SelectionFormationFormAwareTrait;

    public function indexAction()
    {
        /** @var Activite[] $activites */
        $activites = $this->getActiviteService()->getActivites('id');

        return new ViewModel([
            'activites' => $activites,
        ]);
    }

    public function creerAction()
    {
        /** @var Activite $activite */
        $activite = new Activite();

        /** @var ActiviteForm $form */
        $form = $this->getActiviteForm();
        $form->setAttribute('action', $this->url()->fromRoute('activite/creer',[],[], true));
        $form->bind($activite);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $activite->setLibelle(null);
                $this->getActiviteService()->create($activite);
                $this->getActiviteService()->updateLibelle($activite, $data);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajouter une activité',
            'form' => $form,
        ]);
        return $vm;
    }

    public function afficherAction()
    {
        $activite = $this->getActiviteService()->getRequestedActivite($this, 'activite');

        return new ViewModel([
            'title' => 'Visualisation d\'une activité',
            'mode' => 'afficher',
            'activite' => $activite,
        ]);
    }

    public function modifierAction()
    {
        $activite = $this->getActiviteService()->getRequestedActivite($this);

        $vm = new ViewModel();
        $vm->setTemplate('application/activite/afficher');
        $vm->setVariables([
           'mode' => 'modifier-activite',
           'activite' => $activite,
        ]);
        return $vm;
    }

    public function historiserAction()
    {
        $activite = $this->getActiviteService()->getRequestedActivite($this);

        $this->getActiviteService()->historise($activite);
        return $this->redirect()->toRoute('activite');
    }

    public function restaurerAction()
    {
        $activite = $this->getActiviteService()->getRequestedActivite($this);

        $this->getActiviteService()->restore($activite);
        return $this->redirect()->toRoute('activite');
    }


    public function detruireAction()
    {
        /** @var Activite $activite */
        $activite = $this->getActiviteService()->getRequestedActivite($this, 'activite');

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getActiviteService()->delete($activite);
            exit();
        }

        $vm = new ViewModel();
        if ($activite !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la mission principale [" . $activite->getLibelle() . "]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('activite/detruire', ["activite" => $activite->getId()], [], true),
            ]);
        }
        return $vm;
    }



    public function modifierLibelleAction() {
        $activite = $this->getActiviteService()->getRequestedActivite($this);

        $form = $this->getModifierLibelleForm();
        $form->setAttribute('action', $this->url()->fromRoute('activite/modifier-libelle', ["activite" => $activite->getId()], [], true));
        $form->bind($activite);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $this->getActiviteService()->updateLibelle($activite, $data);
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modifier le libellé de l'activité",
            'form' => $form,
        ]);
        return $vm;
    }

    public function ajouterDescriptionAction() {
        $activite = $this->getActiviteService()->getRequestedActivite($this);
        $description = new ActiviteDescription();

        $form = $this->getModifierLibelleForm();
        $form->setAttribute('action', $this->url()->fromRoute('activite/ajouter-description', ['activite' => $activite->getId()], [], true));
        $form->bind($description);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $libelle = null;
            if (isset($data['libelle'])) $libelle = $data['libelle'];

            if ($libelle !== null AND trim($libelle) !== "") {
                $description->setActivite($activite);
                $description->setDescription($libelle);
                $this->getActiviteDescriptionService()->create($description);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'une description",
            'form' => $form,
        ]);
        return $vm;
    }

    public function ajouterDescriptionsAction()
    {
        $activite = $this->getActiviteService()->getRequestedActivite($this);
        $form = $this->getModifierDescriptionForm();
        $form->setAttribute('action', $this->url()->fromRoute('activite/ajouter-descriptions', ['activite' => $activite->getId()], [], true));

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $descriptions = explode("<li>", $data['description']);
            $descriptions = array_map(function($string) {return strip_tags($string);}, $descriptions);
            $descriptions = array_map(function($string) {return str_replace("\r","",$string);}, $descriptions);
            $descriptions = array_map(function($string) {return str_replace("\n","",$string);}, $descriptions);
            $descriptions = array_map(function($string) {return html_entity_decode($string);}, $descriptions);
            $descriptions = array_filter($descriptions, function($string) {return trim($string) != '';});

            foreach ($descriptions as $description) {
                $activiteDescription = new ActiviteDescription();
                $activiteDescription->setActivite($activite);
                $activiteDescription->setDescription($description);
                $this->getActiviteDescriptionService()->create($activiteDescription);
            }

        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajout de plusieurs descriptions',
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierDescriptionAction() {
        $description = $this->getActiviteDescriptionService()->getRequestedActiviteDescription($this);

        $form = $this->getModifierLibelleForm();
        $form->setAttribute('action', $this->url()->fromRoute('activite/modifier-description', ['description' => $description->getId()], [], true));
        $form->bind($description);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $libelle = null;
            if (isset($data['libelle'])) $libelle = $data['libelle'];

            if ($libelle !== null AND trim($libelle) !== "") {
                $newdescription = new ActiviteDescription();
                $newdescription->setActivite($description->getActivite());
                $newdescription->setDescription($libelle);
                $this->getActiviteDescriptionService()->historise($description);
                $this->getActiviteDescriptionService()->create($newdescription);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'une description",
            'form' => $form,
        ]);
        return $vm;
    }

    public function supprimerDescriptionAction() {
        $description = $this->getActiviteDescriptionService()->getRequestedActiviteDescription($this);
        $activite = $description->getActivite();

        $this->getActiviteDescriptionService()->historise($description);

        return $this->redirect()->toRoute('activite/modifier', ['activite' => $activite->getId()], [], true);
    }

    public function modifierApplicationAction() {
        $activite = $this->getActiviteService()->getRequestedActivite($this);

        $form = $this->getSelectionApplicationForm();
        $form->setAttribute('action', $this->url()->fromRoute('activite/modifier-application', ['activite' => $activite->getId()], [], true));
        $form->bind($activite);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $this->getActiviteService()->updateApplications($activite, $data);
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Sélection des applications associées à l'activité",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierCompetenceAction() {
        $activite = $this->getActiviteService()->getRequestedActivite($this);

        $form = $this->getSelectionCompetenceForm();
        $form->setAttribute('action', $this->url()->fromRoute('activite/modifier-competence', ['activite' => $activite->getId()], [], true));
        $form->bind($activite);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $this->getActiviteService()->updateCompetences($activite, $data);
            exit();
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Sélection des compétences associées à l'activité",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierFormationAction() {
        $activite = $this->getActiviteService()->getRequestedActivite($this);

        $form = $this->getSelectionFormationForm();
        $form->setAttribute('action', $this->url()->fromRoute('activite/modifier-formation', ['activite' => $activite->getId()], [], true));
        $form->bind($activite);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $this->getActiviteService()->updateFormations($activite, $data);
            exit();
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Sélection des formations associées à l'activité",
            'form' => $form,
        ]);
        return $vm;
    }

    /**
     * Action convertissant les anciennes description d'activité en nouvelles versions
     */
    public function convertAction()
    {
        $activite = $this->getActiviteService()->getRequestedActivite($this, 'activite');

        $descriptions = $activite->getDescription();

        /** retirer le <ul></ul> */
        $descriptions = str_replace(["\n","\r"],["",""], $descriptions);
        $descriptions = preg_replace("/^<ul><li>/", "", $descriptions);
        $descriptions = preg_replace("/<\/li><\/ul>$/", "", $descriptions);
        $elements = explode("</li><li>", $descriptions);

        $new = [];
        foreach ($elements as $element) {
            $description = new ActiviteDescription();
            $description->setActivite($activite);
            $description->setDescription($element);
            $new[] = $description;
        }

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();

            if($data['reponse'] === 'oui') {
                if ($activite->getLibelleField() !== null) {
                    $data = ['libelle' => $activite->getLibelleField()];
                    $this->getActiviteService()->updateLibelle($activite, $data);
                    $activite->setLibelle(null);
                }

                $descriptions = $activite->getDescriptions();
                foreach ($descriptions as $description) $this->getActiviteDescriptionService()->delete($description);
                $activite->clearDescriptions();
                $this->getActiviteService()->update($activite);

                foreach ($new as $item) {
                    $item->setActivite($activite);
                    $this->getActiviteDescriptionService()->create($item);
                }
                $activite->setDescription(null);
                $this->getActiviteService()->update($activite);
            }
            exit();
        }

        return new ViewModel([
            'title' => "Convertion de l'activite [".$activite->getLibelle()."].",
            'activite' => $activite,
            'new' => $new,
            'action' => $this->url()->fromRoute('activite/convert', ['activite' => $activite->getId()], [], true),
        ]);
    }
}