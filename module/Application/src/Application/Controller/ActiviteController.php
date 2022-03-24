<?php

namespace Application\Controller;

use Application\Entity\Db\Activite;
use Application\Entity\Db\ActiviteDescription;
use Application\Entity\Db\FicheMetierActivite;
use Application\Form\Activite\ActiviteForm;
use Application\Form\Activite\ActiviteFormAwareTrait;
use Application\Form\HasDescription\HasDescriptionFormAwareTrait;
use Application\Form\ModifierLibelle\ModifierLibelleFormAwareTrait;
use Element\Form\SelectionApplication\SelectionApplicationFormAwareTrait;
use Element\Form\SelectionCompetence\SelectionCompetenceFormAwareTrait;
use Element\Service\HasApplicationCollection\HasApplicationCollectionServiceAwareTrait;
use Element\Service\HasCompetenceCollection\HasCompetenceCollectionServiceAwareTrait;
use Carriere\Entity\Db\NiveauEnveloppe;
use Carriere\Form\NiveauEnveloppe\NiveauEnveloppeFormAwareTrait;
use Carriere\Service\NiveauEnveloppe\NiveauEnveloppeServiceAwareTrait;
use Formation\Form\SelectionFormation\SelectionFormationFormAwareTrait;
use Application\Service\Activite\ActiviteServiceAwareTrait;
use Application\Service\ActiviteDescription\ActiviteDescriptionServiceAwareTrait;
use Formation\Service\HasFormationCollection\HasFormationCollectionServiceAwareTrait;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class ActiviteController  extends AbstractActionController {
    /** Traits associé aux services */
    use ActiviteServiceAwareTrait;
    use ActiviteDescriptionServiceAwareTrait;
    use HasApplicationCollectionServiceAwareTrait;
    use HasCompetenceCollectionServiceAwareTrait;
    use HasFormationCollectionServiceAwareTrait;
    use NiveauEnveloppeServiceAwareTrait;
    /** Traits associé aux formulaires */
    use ActiviteFormAwareTrait;
    use HasDescriptionFormAwareTrait;
    use ModifierLibelleFormAwareTrait;
    use NiveauEnveloppeFormAwareTrait;
    use SelectionApplicationFormAwareTrait;
    use SelectionCompetenceFormAwareTrait;
    use SelectionFormationFormAwareTrait;

    /** ACTION SIMPLE *************************************************************************************************/

    public function indexAction() : ViewModel
    {
        /** @var Activite[] $activites */
        $activites = $this->getActiviteService()->getActivites();

        return new ViewModel([
            'activites' => $activites,
        ]);
    }

    public function afficherAction() : ViewModel
    {
        $activite = $this->getActiviteService()->getRequestedActivite($this);

        return new ViewModel([
            'title' => 'Visualisation d\'une activité',
            'mode' => 'afficher',
            'activite' => $activite,
        ]);
    }

    public function creerAction() : ViewModel
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

    public function modifierAction() : ViewModel
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

    public function historiserAction() : Response
    {
        $activite = $this->getActiviteService()->getRequestedActivite($this);
        $this->getActiviteService()->historise($activite);
        return $this->redirect()->toRoute('activite');
    }

    public function restaurerAction() : Response
    {
        $activite = $this->getActiviteService()->getRequestedActivite($this);
        $this->getActiviteService()->restore($activite);
        return $this->redirect()->toRoute('activite');
    }

    public function detruireAction() : ViewModel
    {
        $activite = $this->getActiviteService()->getRequestedActivite($this);

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

    /** GESTION DES CONSTITUANTS **************************************************************************************/

    public function modifierLibelleAction() : ViewModel
    {
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

    public function ajouterDescriptionAction() : ViewModel
    {
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

    public function ajouterDescriptionsAction() : ViewModel
    {
        $activite = $this->getActiviteService()->getRequestedActivite($this);
        $form = $this->getHasDescriptionForm();
        $form->setAttribute('action', $this->url()->fromRoute('activite/ajouter-descriptions', ['activite' => $activite->getId()], [], true));

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $descriptions = explode("<li>", $data['HasDescription']['description']);
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

    public function modifierDescriptionAction() : ViewModel
    {
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

    public function supprimerDescriptionAction() : Response
    {
        $description = $this->getActiviteDescriptionService()->getRequestedActiviteDescription($this);
        $activite = $description->getActivite();

        $this->getActiviteDescriptionService()->historise($description);

        return $this->redirect()->toRoute('activite/modifier', ['activite' => $activite->getId()], [], true);
    }

    public function modifierApplicationAction() : ViewModel
    {
        $activite = $this->getActiviteService()->getRequestedActivite($this);

        $form = $this->getSelectionApplicationForm();
        $form->setAttribute('action', $this->url()->fromRoute('activite/modifier-application', ['activite' => $activite->getId()], [], true));
        $form->bind($activite);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $this->getHasApplicationCollectionService()->updateApplications($activite, $data);
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Sélection des applications associées à l'activité",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierCompetenceAction() : ViewModel
    {
        $activite = $this->getActiviteService()->getRequestedActivite($this);

        $form = $this->getSelectionCompetenceForm();
        $form->setAttribute('action', $this->url()->fromRoute('activite/modifier-competence', ['activite' => $activite->getId()], [], true));
        $form->bind($activite);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $this->getHasCompetenceCollectionService()->updateCompetences($activite, $data);
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Sélection des compétences associées à l'activité",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierFormationAction() : ViewModel
    {
        $activite = $this->getActiviteService()->getRequestedActivite($this);

        $form = $this->getSelectionFormationForm();
        $form->setAttribute('action', $this->url()->fromRoute('activite/modifier-formation', ['activite' => $activite->getId()], [], true));
        $form->bind($activite);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $this->getHasFormationCollectionService()->updateFormations($activite, $data);
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

    public function updateOrdreDescriptionAction() : ViewModel
    {
        $activite = $this->getActiviteService()->getRequestedActivite($this);
        $ordre = explode("_",$this->params()->fromRoute('ordre'));
        $sort = [];
        $position = 1;
        foreach ($ordre as $item) {
            $sort[$item] = $position;
            $position++;
        }

        $descriptions = $activite->getDescriptions();
        foreach ($descriptions as $description) {
            if (! isset($sort[$description->getId()]) AND $description->getOrdre() !== null) {
                $description->setOrdre(null);
                $this->getActiviteDescriptionService()->update($description);
            }
            if ($description->getOrdre() != $sort[$description->getId()]) {
                $description->setOrdre($sort[$description->getId()]);
                $this->getActiviteDescriptionService()->update($description);
            }
        }

        return new ViewModel();
    }

    /** ENVELOPPE DE NIVEAUX ******************************************************************************************/

    public function ajouterNiveauxAction() : ViewModel
    {
        $activite = $this->getActiviteService()->getRequestedActivite($this);

        $niveauEnveloppe = new NiveauEnveloppe();
        $form = $this->getNiveauEnveloppeForm();
        $form->setAttribute('action', $this->url()->fromRoute('activite/ajouter-niveaux', ['activite' => $activite->getId()], [], true));
        $form->bind($niveauEnveloppe);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getNiveauEnveloppeService()->create($niveauEnveloppe);
                $activite->setNiveaux($niveauEnveloppe);
                $this->getActiviteService()->update($activite);
            }
        }

        $vm = new ViewModel([
            'title' => "Ajouter une enveloppe de niveau",
            'form' => $form,
        ]);
        $vm->setTemplate('application/default/default-form');
        return $vm;
    }

    public function modifierNiveauxAction() : ViewModel
    {
        $activite = $this->getActiviteService()->getRequestedActivite($this);
        $niveauEnveloppe = $activite->getNiveaux();

        $form = $this->getNiveauEnveloppeForm();
        $form->setAttribute('action', $this->url()->fromRoute('activite/modifier-niveaux', ['activite' => $activite->getId()], [], true));
        $form->bind($niveauEnveloppe);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getNiveauEnveloppeService()->update($niveauEnveloppe);
            }
        }

        $vm = new ViewModel([
            'title' => "Modifier l'enveloppe de niveau",
            'form' => $form,
        ]);
        $vm->setTemplate('application/default/default-form');
        return $vm;
    }

    public function retirerNiveauxAction() : Response
    {
        $activite = $this->getActiviteService()->getRequestedActivite($this);
        $this->getNiveauEnveloppeService()->delete($activite->getNiveaux());

        return $this->redirect()->toRoute('activite/afficher', ['activite' => $activite->getId()], [], true);

    }

    public function initialiserNiveauxAction() : Response
    {
        $activites = $this->getActiviteService()->getActivites();
        foreach ($activites as $activite) {
            if ($activite->getNiveaux() === null) {
                $inferieure = null;
                $superieure = null;
                /** @var FicheMetierActivite $ficheMetier */
                foreach ($activite->getFichesMetiers() as $ficheMetier) {
                    $niveaux = $ficheMetier->getFiche()->getMetier()->getNiveaux();
                    if ($niveaux) {
                        if ($inferieure === null OR $niveaux->getBorneInferieure() < $inferieure) $inferieure = $niveaux->getBorneInferieure();
                        if ($superieure === null OR $niveaux->getBorneSuperieure() > $superieure) $superieure = $niveaux->getBorneSuperieure();
                    }
                }
                if ($inferieure !== null AND $superieure !== null) {
                    $niveaux = new NiveauEnveloppe();
                    $niveaux->setBorneInferieure($inferieure);
                    $niveaux->setBorneSuperieure($superieure);
                    $niveaux->setDescription("Recupérer de l'ancien système de niveau");
                    $this->getNiveauEnveloppeService()->create($niveaux);
                    $activite->setNiveaux($niveaux);
                    $this->getActiviteService()->update($activite);
                }
            }
        }

        return $this->redirect()->toRoute('activite');
    }

    /** ACTIONS DE RECHERCHE ******************************************************************************************/

    public function rechercherActiviteAction() : JsonModel
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $activites = $this->getActiviteService()->findActiviteByExtendedTerm($term);
            $result = $this->getActiviteService()->formatActiviteJSON($activites);
            return new JsonModel($result);
        }
        exit;
    }
}