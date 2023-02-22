<?php

namespace Application\Controller;

use Application\Entity\Db\Activite;
use Application\Entity\Db\FicheMetier;
use Application\Entity\Db\ParcoursDeFormation;
use Application\Form\Activite\ActiviteForm;
use Application\Form\Activite\ActiviteFormAwareTrait;
use Application\Form\FicheMetierImportation\FicheMetierImportationFormAwareTrait;
use Application\Form\Raison\RaisonFormAwareTrait;
use Application\Form\SelectionFicheMetier\SelectionFicheMetierFormAwareTrait;
use Application\Provider\Etat\FicheMetierEtats;
use Application\Provider\Template\PdfTemplate;
use Application\Service\Activite\ActiviteServiceAwareTrait;
use Application\Service\ActiviteDescription\ActiviteDescriptionServiceAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\Configuration\ConfigurationServiceAwareTrait;
use Application\Service\FicheMetier\FicheMetierServiceAwareTrait;
use Application\Service\ParcoursDeFormation\ParcoursDeFormationServiceAwareTrait;
use Doctrine\ORM\ORMException;
use Element\Entity\Db\Application;
use Element\Entity\Db\ApplicationElement;
use Element\Entity\Db\Competence;
use Element\Entity\Db\CompetenceElement;
use Element\Form\SelectionApplication\SelectionApplicationFormAwareTrait;
use Element\Form\SelectionCompetence\SelectionCompetenceFormAwareTrait;
use Element\Service\HasApplicationCollection\HasApplicationCollectionServiceAwareTrait;
use Element\Service\HasCompetenceCollection\HasCompetenceCollectionServiceAwareTrait;
use Formation\Form\SelectionFormation\SelectionFormationFormAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\View\Model\ViewModel;
use Metier\Form\SelectionnerMetier\SelectionnerMetierFormAwareTrait;
use Metier\Service\Domaine\DomaineServiceAwareTrait;
use Metier\Service\Metier\MetierServiceAwareTrait;
use Mpdf\MpdfException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenEtat\Form\SelectionEtat\SelectionEtatFormAwareTrait;
use UnicaenEtat\Service\Etat\EtatServiceAwareTrait;
use UnicaenEtat\Service\EtatType\EtatTypeServiceAwareTrait;
use UnicaenPdf\Exporter\PdfExporter;
use UnicaenRenderer\Service\Rendu\RenduServiceAwareTrait;

/** @method FlashMessenger flashMessenger() */

class FicheMetierController extends AbstractActionController
{
    /** Traits associés aux services */
    use ActiviteServiceAwareTrait;
    use ActiviteDescriptionServiceAwareTrait;
    use AgentServiceAwareTrait;
    use ConfigurationServiceAwareTrait;
    use DomaineServiceAwareTrait;
    use EtatServiceAwareTrait;
    use EtatTypeServiceAwareTrait;
    use FicheMetierServiceAwareTrait;
    use HasApplicationCollectionServiceAwareTrait;
    use HasCompetenceCollectionServiceAwareTrait;
    use MetierServiceAwareTrait;
    use ParcoursDeFormationServiceAwareTrait;
    use RenduServiceAwareTrait;

    /** Traits associés aux formulaires */
    use ActiviteFormAwareTrait;
    use FicheMetierImportationFormAwareTrait;
    use RaisonFormAwareTrait;
    use SelectionApplicationFormAwareTrait;
    use SelectionCompetenceFormAwareTrait;
    use SelectionEtatFormAwareTrait;
    use SelectionFicheMetierFormAwareTrait;
    use SelectionFormationFormAwareTrait;
    use SelectionnerMetierFormAwareTrait;


    const REFERENS_SEP = "|";

    /** CRUD **********************************************************************************************************/

    public function indexAction() : ViewModel
    {
        $fromQueries  = $this->params()->fromQuery();
        $etatId       = $fromQueries['etat'] ?? null;
        $domaineId    = $fromQueries['domaine'] ?? null;
        $expertise    = $fromQueries['expertise'] ?? null;
        $params = ['etat' => $etatId, 'domaine' => $domaineId, 'expertise' => $expertise];

        $type = $this->getEtatTypeService()->getEtatTypeByCode(FicheMetierEtats::TYPE);
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
        $fiche->setEtat($this->getEtatService()->getEtatByCode(FicheMetierEtats::ETAT_REDACTION));

        $form = $this->getSelectionnerMetierForm();
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
            'title' => "Ajout d'une fiche metier",
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
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(PdfTemplate::FICHE_METIER, $vars);

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

    public function editerRaisonAction() : ViewModel
    {
        $fiche = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');

        $form = $this->getRaisonForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier-type/editer-raison', ['fiche-metier' => $fiche->getId()], [], true));
        $form->bind($fiche);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFicheMetierService()->update($fiche);
                $this->flashMessenger()->addSuccessMessage("Mise à jour de la \"raison d'être du métier\" effectuée.");
                exit();
            }
        }

        $vm =  new ViewModel([
            'title' => "Modification de la raison d'être du métier",
            'form' => $form,
            'info' => "Laisser vide si aucun raison n'est nécessaire",
        ]);
        $vm->setTemplate('application/default/default-form');
        return $vm;
    }

    public function editerLibelleAction() : ViewModel
    {
        $fiche = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'id', true);

        $form = $this->getSelectionnerMetierForm();
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
                $this->getActiviteService()->createFicheMetierActivite($fiche, $activite);
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
            $this->getActiviteService()->createFicheMetierActivite($fiche, $activite);
        }

        return new ViewModel([
            'title' => 'Ajouter une activité existante',
            'url' => $this->url()->fromRoute('fiche-metier-type/ajouter-activite-existante', ['id' => $fiche->getId()], [], true),
        ]);
    }

    public function retirerActiviteAction() : Response
    {
        $coupleId = $this->params()->fromRoute('id');
        $couple = $this->getActiviteService()->getFicheMetierActivite($coupleId);

        $this->getActiviteService()->removeFicheMetierActivite($couple);

        return $this->redirect()->toRoute('fiche-metier-type/editer', ['id' => $couple->getFiche()->getId()], [], true);
    }

    public function deplacerActiviteAction() : Response
    {
        $direction = $this->params()->fromRoute('direction');
        $coupleId = $this->params()->fromRoute('id');
        $couple = $this->getActiviteService()->getFicheMetierActivite($coupleId);

        if ($direction === 'up') $this->getActiviteService()->moveUp($couple);
        if ($direction === 'down') $this->getActiviteService()->moveDown($couple);

        $this->getActiviteService()->updateFicheMetierActivite($couple);

        return $this->redirect()->toRoute('fiche-metier-type/editer', ['id' => $couple->getFiche()->getId()], [], true);
    }

    /** ACTIONS LIEES AUX ELEMENTS ************************************************************************************/

    public function afficherApplicationsAction() : ViewModel
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $applications = $this->getFicheMetierService()->getApplicationsDictionnaires($fichemetier, true);

        return new ViewModel([
            'fichemetier' => $fichemetier,
            'applications' => $applications,
        ]);
    }

    public function clonerApplicationsAction() : ViewModel
    {
        $ficheMetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');

        $form = $this->getSelectionFicheMetierForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier-type/cloner-applications', ['fiche-metier' => $ficheMetier->getId()], [], true));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $ficheClone = $this->getFicheMetierService()->getFicheMetier($data['fiche-metier']);

            if ($ficheClone !== null) {
                try {
                    /** @var CompetenceElement[] $oldCollection */
                    $oldCollection = $ficheMetier->getApplicationCollection();
                    foreach ($oldCollection as $element) $element->historiser();

                    $newCollection = $ficheClone->getApplicationListe();
                    foreach ($newCollection as $element) {
                        $newElement = new ApplicationElement();
                        $newElement->setApplication($element->getApplication());
                        $newElement->setCommentaire("Clonée depuis la fiche #".$ficheClone->getId());
                        $newElement->setNiveauMaitrise($element->getNiveauMaitrise());
                        $this->getFicheMetierService()->getEntityManager()->persist($newElement);
                        $ficheMetier->addApplicationElement($newElement);
                  }
                  $this->getFicheMetierService()->getEntityManager()->flush();
                } catch (ORMException $e) {
                    throw new RuntimeException("Un problème est survenu en base de donnée", 0, $e);
                }
            }

        }

        $vm = new ViewModel();
        $vm->setVariables([
            'title' => "Cloner les applications d'une autre fiche métier",
            'form' => $form,
        ]);
        return $vm;
    }

    public function afficherCompetencesAction() : ViewModel
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $competences = $this->getFicheMetierService()->getCompetencesDictionnaires($fichemetier, true);

        return new ViewModel([
            'fichemetier' => $fichemetier,
            'competences' => $competences,
        ]);
    }

    public function clonerCompetencesAction() : ViewModel
    {
        $ficheMetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');

        $form = $this->getSelectionFicheMetierForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier-type/cloner-competences', ['fiche-metier' => $ficheMetier->getId()], [], true));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $ficheClone = $this->getFicheMetierService()->getFicheMetier($data['fiche-metier']);

            if ($ficheClone !== null) {
                try {
                    /** @var CompetenceElement[] $oldCollection */
                    $oldCollection = $ficheMetier->getCompetenceCollection();
                    foreach ($oldCollection as $element) $element->historiser();

                    $newCollection = $ficheClone->getCompetenceListe();
                    foreach ($newCollection as $element) {
                        $newElement = new CompetenceElement();
                        $newElement->setCompetence($element->getCompetence());
                        $newElement->setCommentaire("Clonée depuis la fiche #" . $ficheClone->getId());
                        $newElement->setNiveauMaitrise($element->getNiveauMaitrise());
                        $this->getFicheMetierService()->getEntityManager()->persist($newElement);
                        $ficheMetier->addCompetenceElement($newElement);
                    }
                    $this->getFicheMetierService()->getEntityManager()->flush();
                } catch (ORMException $e) {
                    throw new RuntimeException("Un problème est survenu en base de donnée", 0, $e);
                }
            }

        }

        $vm = new ViewModel();
        $vm->setVariables([
            'title' => "Cloner les compétences d'une autre fiche métier",
            'form' => $form,
        ]);
        return $vm;
    }

    /** Expertise  ****************************************************************************************************/

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
        $form->reinit(FicheMetierEtats::TYPE);

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

    /** IMPORTATION ***************************************************************************************************/

    private function genererInfosFromCSV(string $fichier_path) : array
    {
        $csvInfos = $this->getFicheMetierService()->readFromCSV($fichier_path);

        $ajouts = $this->getConfigurationService()->getConfigurationsFicheMetier();
        foreach ($ajouts as $ajout) {
            if ($ajout->getEntityType() === Application::class) {
                $application = $ajout->getEntity();
                $csvInfos['applications'][$application->getId()] = $application;
            }
            if ($ajout->getEntityType() === Competence::class) {
                $competence = $ajout->getEntity();
                $csvInfos['competencesListe'][$competence->getId()] = $competence;
                $csvInfos['competences'][$competence->getType()->getLibelle()][$competence->getId()] = $competence;
            }
        }

        // tri
        foreach (['Connaissances', 'Opérationnelles', 'Comportementales'] as $type) {
            usort($csvInfos['competences'][$type], function (Competence $a, Competence $b) {return $a->getLibelle() > $b->getLibelle();});
        }
        usort($csvInfos['applications'], function (Application $a, Application $b) {return $a->getLibelle() > $b->getLibelle();});

        return $csvInfos;
    }

    public function importerDepuisCsvAction()
    {
        $form = $this->getFicheMetierImportationForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier-type/importer-depuis-csv', ['mode' => 'preview', 'path' => null], [], true));


        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $file = $request->getFiles();

            $fichier_path = $file['fichier']['tmp_name'];
            $mode = $data['mode'];

            $csvInfos = $this->genererInfosFromCSV($fichier_path);

            if ($mode !== null) {
                if ($mode === 'import') {
                    if ($csvInfos['metier'] !== null and empty($csvInfos['competences']['Manquantes'])) {
                        $fiche = $this->getFicheMetierService()->importFromCsvArray($csvInfos);

                        /** @see \Application\Controller\FicheMetierController::afficherAction() */
                        return $this->redirect()->toRoute('fiche-metier-type/afficher', ['id' => $fiche->getId()], [], true);
                    }
                }
                return new ViewModel([
                    'fichier_path' => $fichier_path,
                    'form' => $form,
                    'mode' => $mode,
                    'code' => $csvInfos['code'],
                    'metier' => $csvInfos['metier'],
                    'mission' => $csvInfos['mission'],
                    'activites' => $csvInfos['activites'],
                    'applications' => $csvInfos['applications'],
                    'competences' => $csvInfos['competences'],
                ]);
            }
        }

        $vm = new ViewModel([
            'title' => "Importation d'une fiche métier",
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