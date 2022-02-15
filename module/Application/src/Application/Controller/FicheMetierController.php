<?php

namespace Application\Controller;

use Application\Entity\Db\Activite;
use Application\Entity\Db\ApplicationElement;
use Application\Entity\Db\CompetenceElement;
use Application\Entity\Db\FicheMetier;
use Application\Entity\Db\ParcoursDeFormation;
use Application\Form\Activite\ActiviteForm;
use Application\Form\Activite\ActiviteFormAwareTrait;
use Application\Form\FicheMetier\LibelleForm;
use Application\Form\FicheMetier\LibelleFormAwareTrait;
use Application\Form\SelectionApplication\SelectionApplicationForm;
use Application\Form\SelectionApplication\SelectionApplicationFormAwareTrait;
use Application\Form\SelectionCompetence\SelectionCompetenceFormAwareTrait;
use Application\Service\Activite\ActiviteServiceAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\Configuration\ConfigurationServiceAwareTrait;
use Application\Service\FicheMetier\FicheMetierServiceAwareTrait;
use Application\Service\HasApplicationCollection\HasApplicationCollectionServiceAwareTrait;
use Application\Service\HasCompetenceCollection\HasCompetenceCollectionServiceAwareTrait;
use Application\Service\ParcoursDeFormation\ParcoursDeFormationServiceAwareTrait;
use Application\Service\RendererAwareTrait;
use Formation\Form\SelectionFormation\SelectionFormationFormAwareTrait;
use Metier\Service\Domaine\DomaineServiceAwareTrait;
use Metier\Service\Metier\MetierServiceAwareTrait;
use Mpdf\MpdfException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenEtat\Form\SelectionEtat\SelectionEtatFormAwareTrait;
use UnicaenEtat\Service\Etat\EtatServiceAwareTrait;
use UnicaenEtat\Service\EtatType\EtatTypeServiceAwareTrait;
use UnicaenPdf\Exporter\PdfExporter;
use UnicaenRenderer\Service\Rendu\RenduServiceAwareTrait;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Zend\View\Model\ViewModel;

/** @method FlashMessenger flashMessenger() */

class FicheMetierController extends AbstractActionController
{
    use RendererAwareTrait;

    /** Traits associé aux services */
    use ActiviteServiceAwareTrait;
    use AgentServiceAwareTrait;
    use RenduServiceAwareTrait;
    use DomaineServiceAwareTrait;
    use FicheMetierServiceAwareTrait;
    use HasApplicationCollectionServiceAwareTrait;
    use HasCompetenceCollectionServiceAwareTrait;
    use MetierServiceAwareTrait;
    use ParcoursDeFormationServiceAwareTrait;
    use EtatServiceAwareTrait;

    /** Traits associé aux formulaires */
    use ActiviteFormAwareTrait;
    use LibelleFormAwareTrait;
    use SelectionApplicationFormAwareTrait;
    use SelectionCompetenceFormAwareTrait;
    use SelectionFormationFormAwareTrait;
    use SelectionEtatFormAwareTrait;
    use EtatTypeServiceAwareTrait;

    use ConfigurationServiceAwareTrait;

    /** CRUD **********************************************************************************************************/

    public function indexAction() : ViewModel
    {
        $fromQueries  = $this->params()->fromQuery();
        $etatId       = $fromQueries['etat'];
        $domaineId    = $fromQueries['domaine'];
        $expertise    = $fromQueries['expertise'];
        $params = ['etat' => $etatId, 'domaine' => $domaineId, 'expertise' => $expertise];

        $type = $this->getEtatTypeService()->getEtatTypeByCode(FicheMetier::TYPE_FICHEMETIER);
        $etats = $this->getEtatService()->getEtatsByType($type);
        $domaines = $this->getDomaineService()->getDomaines();

        $fichesMetiers = $this->getFicheMetierService()->getFichesMetiersWithFiltre($params);

        return new ViewModel([
            'params' => $params,
            'domaines' => $domaines,
            'etats' => $etats,
            'fiches' => $fichesMetiers,
        ]);
    }

    public function afficherAction() : ViewModel
    {
        $fiche = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'id', true);
        $missions = $this->getActiviteService()->getActivitesByFicheMetierType($fiche);
        $parcours = ($fiche->getMetier()->getCategorie())?$this->getParcoursDeFormationService()->generateParcoursArrayFromFicheMetier($fiche):null;
        $applications = $this->getFicheMetierService()->getApplicationsDictionnaires($fiche, true);
        $competences = $this->getFicheMetierService()->getCompetencesDictionnaires($fiche, true);

        return new ViewModel([
            'fiche' => $fiche,
            'competences' => $competences,
            'applications' => $applications,
            'parcours' => $parcours,
            'missions' => $missions,
        ]);
    }

    public function ajouterAction() : ViewModel
    {
        /** @var FicheMetier $fiche */
        $fiche = new FicheMetier();
        $fiche->setEtat($this->getEtatService()->getEtatByCode(FicheMetier::ETAT_REDACTION));

        /** @var LibelleForm $form */
        $form = $this->getLibelleForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier-type/ajouter', [], [], true));
        $form->bind($fiche);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFicheMetierService()->create($fiche);
                $this->getConfigurationService()->addDefaultToFicheMetier($fiche);
                $this->getFicheMetierService()->update($fiche);

                $libelle = $this->getMetierService()->computeEcritureInclusive($fiche->getMetier()->getLibelleFeminin(), $fiche->getMetier()->getLibelleMasculin());
                $this->flashMessenger()->addSuccessMessage(
                    "Une nouvelle fiche métier vient d'être ajouter pour le métier <strong>".$libelle."</strong>.<br/> ".
                    "Vous pouvez modifier celle-ci en utilisant le lien suivant : <a href='".$this->url()->fromRoute('fiche-metier-type/editer', ['id' => $fiche->getId()], [], true)."'>Modification de la fiche métier #". $fiche->getId()."</a>");
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajout d\'une fiche metier',
            'form' => $form,
        ]);
        return $vm;
    }

    public function dupliquerAction() : Response
    {
        $fiche = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');

        if ($fiche !== null) {
            $duplicata = $this->getFicheMetierService()->dupliquerFicheMetier($fiche);
            return $this->redirect()->toRoute('fiche-metier-type/editer', ['id' => $duplicata->getId()], [], true);
        }
        exit();
    }

    public function editerAction() : ViewModel
    {
        $fiche = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'id', false);
        $missions = $this->getActiviteService()->getActivitesByFicheMetierType($fiche);
        $parcours = ($fiche->getMetier()->getCategorie())?$this->getParcoursDeFormationService()->generateParcoursArrayFromFicheMetier($fiche):null;
        $applications = $this->getFicheMetierService()->getApplicationsDictionnaires($fiche, true);
        $competences = $this->getFicheMetierService()->getCompetencesDictionnaires($fiche, true);

        return new ViewModel([
            'fiche' => $fiche,
            'competences' => $competences,
            'applications' => $applications,
            'parcours' => $parcours,
            'missions' => $missions,
        ]);
    }

    public function exporterAction() : string
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'id', true);

        $vars = [
            'fichemetier' => $fichemetier,
            'metier' => $fichemetier->getMetier(),
            'parcours' => ($fichemetier->getMetier()->getCategorie())?$this->getParcoursDeFormationService()->getParcoursDeFormationByTypeAndReference(ParcoursDeFormation::TYPE_CATEGORIE, $fichemetier->getMetier()->getCategorie()->getId()):null,
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode('FICHE_METIER', $vars);

        try {
            $exporter = new PdfExporter();
            $exporter->getMpdf()->SetTitle($rendu->getSujet());
            $exporter->setHeaderScript('');
            $exporter->setFooterScript('');
            $exporter->addBodyHtml($rendu->getCorps());
            return $exporter->export($rendu->getSujet());
        } catch (MpdfException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'export en PDF",0,$e);
        }
    }

    public function historiserAction() : Response
    {
        $fiche = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'id', true);
        $this->getFicheMetierService()->historise($fiche);
        return $this->redirect()->toRoute('fiche-metier-type', [], [], true);
    }

    public function restaurerAction() : Response
    {
        $fiche = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'id', true);
        $this->getFicheMetierService()->restore($fiche);
        return $this->redirect()->toRoute('fiche-metier-type', [], [], true);
    }

    public function detruireAction() : ViewModel
    {
        $fiche = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") {
                $this->getFicheMetierService()->delete($fiche);
            }
            exit();
        }

        $vm = new ViewModel();
        if ($fiche !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la fiche de poste  de " . (($fiche and $fiche->getMetier()) ? $fiche->getMetier()->getLibelle() : "[Aucun métier]"),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('fiche-metier-type/detruire', ["fiche-metier" => $fiche->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** Action lier à l'édition d'une fiche métier ********************************************************************/

    public function editerLibelleAction() : ViewModel
    {
        $fiche = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'id', true);

        /** @var LibelleForm $form */
        $form = $this->getLibelleForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier-type/editer-libelle', ['id' => $fiche->getId()], [], true));
        $form->bind($fiche);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $this->getFicheMetierService()->updateFromForm($request, $form, $this->getFicheMetierService());
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Modifier le libellé',
            'form' => $form,
        ]);
        return $vm;
    }

    /** ACTIVITE LIEE *************************************************************************************************/

    public function ajouterNouvelleActiviteAction() : ViewModel
    {
        $fiche = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'id', true);

        $activite = new Activite();
        /** @var ActiviteForm $form */
        $form = $this->getActiviteForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier-type/ajouter-nouvelle-activite', ['id' => $fiche->getId()], [], true));
        $form->bind($activite);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getActiviteService()->create($activite);
                $this->getActiviteService()->updateLibelle($activite, $data);
                $this->getActiviteService()->createFicheMetierTypeActivite($fiche, $activite);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajouter une nouvelle activité',
            'form' => $form,
        ]);
        return $vm;

    }

    public function ajouterActiviteExistanteAction() : ViewModel
    {
        $fiche = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'id', true);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $activite = $this->getActiviteService()->getActivite($data['activite']["id"]);
            $this->getActiviteService()->createFicheMetierTypeActivite($fiche, $activite);
        }

        return new ViewModel([
            'title' => 'Ajouter une activité existante',
            'url' => $this->url()->fromRoute('fiche-metier-type/ajouter-activite-existante', ['id' => $fiche->getId()], [], true),
        ]);
    }

    public function retirerActiviteAction() : Response
    {
        $coupleId = $this->params()->fromRoute('id');
        $couple = $this->getActiviteService()->getFicheMetierTypeActivite($coupleId);

        $this->getActiviteService()->removeFicheMetierTypeActivite($couple);

        return $this->redirect()->toRoute('fiche-metier-type/editer', ['id' => $couple->getFiche()->getId()], [], true);
    }

    public function deplacerActiviteAction() : Response
    {
        $direction = $this->params()->fromRoute('direction');
        $coupleId = $this->params()->fromRoute('id');
        $couple = $this->getActiviteService()->getFicheMetierTypeActivite($coupleId);

        if ($direction === 'up') $this->getActiviteService()->moveUp($couple);
        if ($direction === 'down') $this->getActiviteService()->moveDown($couple);

        $this->getActiviteService()->updateFicheMetierTypeActivite($couple);

        return $this->redirect()->toRoute('fiche-metier-type/editer', ['id' => $couple->getFiche()->getId()], [], true);
    }

    public function modifierApplicationAction()
    {
        $fiche = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'id');

        /** @var SelectionApplicationForm $form */
        $form = $this->getSelectionApplicationForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier-type/modifier-application', ['id' => $fiche->getId()], [], true));
        $form->bind($fiche);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $this->getHasApplicationCollectionService()->updateApplications($fiche, $data);
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Modification des applications',
            'form' => $form,
        ]);
        return $vm;
    }

    public function changerExpertiseAction() : Response
    {
        $fiche = $this->getFicheMetierService()->getRequestedFicheMetier($this);
        if ($fiche->hasExpertise()) {
            $fiche->setExpertise(false);
        } else {
            $fiche->setExpertise(true);
        }
        $this->getFicheMetierService()->update($fiche);

        return $this->redirect()->toRoute('fiche-metier-type/editer', ['id' => $fiche->getId()], [], true);
    }

    /** GESTION DES ETATS DES FICHES METIERS **************************************************************************/

    public function changerEtatAction() : ViewModel
    {
        $fiche = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');

        $form = $this->getSelectionEtatForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier-type/changer-etat', ['fiche-metier' => $fiche->getId()], [], true));
        $form->bind($fiche);
        $form->reinit(FicheMetier::TYPE_FICHEMETIER);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFicheMetierService()->update($fiche);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Changer l'état de la fiche métier",
            'form' => $form,
        ]);
        return $vm;
    }

    /** Graphique *****************************************************************************************************/

    public function graphiqueCompetencesAction() : ViewModel
    {
        $ficheMetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $agent = $this->getAgentService()->getRequestedAgent($this);

        $dictionnaire = $this->getFicheMetierService()->getCompetencesDictionnaires($ficheMetier, true);
        $dictionnaire = array_filter($dictionnaire, function($item) { return ($item['entite'])->isClef();});
        $labels = []; $values = [];

        $valuesFiche = [];
        foreach ($dictionnaire as $entry) {
            /** @var CompetenceElement $element */
            $element = $entry['entite'];
            $labels[] = substr($element->getLibelle(),0,200);
            $valuesFiche[] = ($element->getNiveauMaitrise())?$element->getNiveauMaitrise()->getNiveau():"'-'";
        }
        $values[] = [
            'title' => "pré-requis",
            'values' => $valuesFiche,
            'color' => "255,0,0",
        ];

        if ($agent !== null) {
            $valuesAgent = [];
            /** @var CompetenceElement[] $competences */
            $competences = $agent->getCompetenceListe();
            foreach ($dictionnaire as $entry) {
                /** @var CompetenceElement $element */
                $element = $entry['entite'];
                $id = $element->getCompetence()->getId();
                $niveau = (isset($competences[$id]) AND $competences[$id]->getNiveauMaitrise())?$competences[$id]->getNiveauMaitrise()->getNiveau():"'-'";
                $valuesAgent[] = $niveau;
            }
            $values[] = [
                'title' => "Acquis",
                'values' => $valuesAgent,
                'color' => "0,255,0",
            ];
        }

        $libelle = $ficheMetier->getMetier()->getLibelle();
        $vm =  new ViewModel([
            'title' => "Diagramme des compétences pour la fiche métier <strong>".$libelle."</strong>",
            'agent' => $agent,
            'label' => $labels,
            'values' => $values,
        ]);
        $vm->setTemplate('application/fiche-metier/graphique-radar');
        return $vm;
    }

    public function graphiqueApplicationsAction() : ViewModel
    {
        $ficheMetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $agent = $this->getAgentService()->getRequestedAgent($this);

        $dictionnaire = $this->getFicheMetierService()->getApplicationsDictionnaires($ficheMetier, true);
        $dictionnaire = array_filter($dictionnaire, function($item) { return ($item['entite'])->isClef();});
        $labels = []; $values = [];

        $valuesFiche = [];
        foreach ($dictionnaire as $entry) {
            /** @var ApplicationElement $element */
            $element = $entry['entite'];
            $labels[] = substr($element->getLibelle(),0,200);
            $valuesFiche[] = ($element->getNiveauMaitrise())?$element->getNiveauMaitrise()->getNiveau():"'-'";
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
                $niveau = (isset($applications[$id]) AND $applications[$id]->getNiveauMaitrise())?$applications[$id]->getNiveauMaitrise()->getNiveau():"'-'";
                $valuesAgent[] = $niveau;
            }
            $values[] = [
                'title' => "Acquis",
                'values' => $valuesAgent,
                'color' => "0,255,0",
            ];
        }

        $libelle = $ficheMetier->getMetier()->getLibelle();
        $vm =  new ViewModel([
            'title' => "Diagramme des applications pour la fiche métier <strong>".$libelle."</strong>",
            'agent' => $agent,
            'label' => $labels,
            'values' => $values,
        ]);
        $vm->setTemplate('application/fiche-metier/graphique-radar');
        return $vm;
    }

}