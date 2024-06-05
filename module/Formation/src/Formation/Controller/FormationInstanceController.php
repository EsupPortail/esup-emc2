<?php

namespace Formation\Controller;

use DateTime;
use Formation\Entity\Db\Inscription;
use Formation\Form\SelectionFormateur\SelectionFormateurFormAwareTrait;
use Formation\Form\SelectionGestionnaire\SelectionGestionnaireFormAwareTrait;
use Formation\Form\Session\SessionFormAwareTrait;
use Formation\Provider\Etat\SessionEtats;
use Formation\Provider\Role\FormationRoles;
use Formation\Service\Formateur\FormateurServiceAwareTrait;
use Formation\Service\Formation\FormationServiceAwareTrait;
use Formation\Service\FormationGroupe\FormationGroupeServiceAwareTrait;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Formation\Service\InscriptionFrais\InscriptionFraisServiceAwareTrait;
use Formation\Service\Notification\NotificationServiceAwareTrait;
use Formation\Service\Presence\PresenceServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use UnicaenApp\View\Model\CsvModel;
use UnicaenEtat\Service\EtatCategorie\EtatCategorieServiceAwareTrait;
use UnicaenEtat\Service\EtatType\EtatTypeServiceAwareTrait;
use UnicaenMail\Service\Mail\MailServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

/** @method FlashMessenger flashMessenger() */
class FormationInstanceController extends AbstractActionController
{
    use EtatCategorieServiceAwareTrait;
    use EtatTypeServiceAwareTrait;
    use FormateurServiceAwareTrait;
    use FormationServiceAwareTrait;
    use FormationGroupeServiceAwareTrait;
    use FormationInstanceServiceAwareTrait;
    use InscriptionFraisServiceAwareTrait;
    use MailServiceAwareTrait;
    use NotificationServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use PresenceServiceAwareTrait;
    use UserServiceAwareTrait;

    use SelectionFormateurFormAwareTrait;
    use SelectionGestionnaireFormAwareTrait;
    use SessionFormAwareTrait;

    public function indexAction(): ViewModel
    {
        $liste = $this->getRequest()->getUri()->getQuery();
        $params = [];
        if ($liste AND $liste !== '') {
            $liste = explode('&', $liste);
            foreach ($liste as $item) {
                [$key, $value] = explode('=', $item);
                $params[$key][] = $value;
            }
        } else {
                $params['etats'] = SessionEtats::ETATS_OUVERTS;
        }

        $instances = $this->getFormationInstanceService()->getSessionsWithParams($params);

        return new ViewModel([
            'instances' => $instances,

            'params' => $params,
            'etats' => $this->getEtatTypeService()->getEtatsTypesByCategorieCode(SessionEtats::TYPE),
            'gestionnaires' => $this->getUserService()->getUtilisateursByRoleIdAsOptions(FormationRoles::GESTIONNAIRE_FORMATION),
            'themes' => $this->getFormationGroupeService()->getFormationsGroupesAsOption(),
        ]);
    }

    /** NB : par defaut les instances de formation sont toutes en autoinscription **************************************/

    public function ajouterAction(): Response
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);
        $instance = $this->getFormationInstanceService()->createNouvelleInstance($formation);

        $user = $this->getUserService()->getConnectedUser();
        $instance->addGestionnaire($user);
        $this->getFormationInstanceService()->update($instance);


        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $instance->getId()], [], true);
    }

    public function ajouterAvecFormulaireAction(): ViewModel
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $formation_ = $data['formation']['id'];
            $formation = $this->getFormationService()->getFormation($formation_);

            if ($formation) {
                $instance = $this->getFormationInstanceService()->createNouvelleInstance($formation);
                $this->flashMessenger()->addSuccessMessage("La session #" . $instance->getId() . " vient d'être créée.");
            } else {
                $this->flashMessenger()->addErrorMessage("La formation sélectionnée est incorrecte.");
            }
        }

        return new ViewModel([
            'title' => "Ouverture d'une nouvelle session de formation",
        ]);

    }

    public function afficherAction(): ViewModel
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $mails = $this->getMailService()->getMailsByMotClef($instance->generateTag());

        $presences = $this->getPresenceService()->getPresenceByInstance($instance);
        $presencesManquantes = $this->getPresenceService()->getPresencesManquantes($instance);
        $fraisManquants = $this->getInscriptionFraisService()->getFraisManquants($instance);

        $dictionnaire = [];
        foreach ($presences as $presence) {
            $dictionnaire[$presence->getJournee()->getId()][$presence->getInscription()->getId()] = $presence;
        }

        return new ViewModel([
            'instance' => $instance,
            'mode' => "affichage",
            'presences' => $dictionnaire,
            'fraisManquants' => $fraisManquants,
            'presencesManquantes' => $presencesManquantes,
            'mails' => $mails,
        ]);
    }

    public function modifierAction(): ViewModel
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);

        return new ViewModel([
            'instance' => $instance,
            'mode' => "modification",
        ]);
    }

    public function historiserAction(): ViewModel
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getFormationInstanceService()->historise($instance);
            exit();
        }

        $vm = new ViewModel();
        if ($instance !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Historisation d'une instance de formation",
                'text' => "Est-vous sûr·e de vouloir historiser la session".$instance->getInstanceLibelle()." du ".$instance->getPeriode()." ?",
                'action' => $this->url()->fromRoute('formation-instance/historiser', ["formation-instance" => $instance->getId()], [], true),
            ]);
        }
        return $vm;
    }

    public function restaurerAction(): Response
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $this->getFormationInstanceService()->restore($instance);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('formation-instance', [], [], true);
    }

    public function supprimerAction(): ViewModel
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getFormationInstanceService()->delete($instance);
            exit();
        }

        $vm = new ViewModel();
        if ($instance !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression d'une instance de formation",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('formation-instance/supprimer', ["formation-instance" => $instance->getId()], [], true),
            ]);
        }
        return $vm;
    }

    public function modifierInformationsAction(): ViewModel
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);

        $form = $this->getSessionForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation-instance/modifier-informations', ['formation-instance' => $instance->getId()], [], true));
        $form->bind($instance);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormationInstanceService()->update($instance);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification des informations de la session",
            'form' => $form,
        ]);
        return $vm;
    }

    /** ASSOCIATION DES FORMATEURS ET GESTIONNAIRES *******************************************************************/

    //ajout
    public function ajouterFormateurAction(): ViewModel
    {
        $session = $this->getFormationInstanceService()->getRequestedFormationInstance($this, 'session');
        $form = $this->getSelectionFormateurForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/session/ajouter-formateur', ['session' => $session->getId()], [], true));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data['formateur']['id'] !== '') {
                $formateurId = $data['formateur']['id'];
                $formateur = $this->getFormateurService()->getFormateur($formateurId);

                if ($formateur) {
                    $session->addFormateur($formateur);
                    $this->getFormationInstanceService()->update($session);
                    exit();
                }

            }
        }

        $vm = new ViewModel([
            'title' => "Sélection un·formateur·trice ou un organisme",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    //retrait
    public function retirerFormateurAction(): Response
    {
        $session = $this->getFormationInstanceService()->getRequestedFormationInstance($this, 'session');
        $formateur = $this->getFormateurService()->getRequestedFormateur($this);

        $this->getFormationInstanceService()->retirerFormateur($session, $formateur);
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $session->getId()], ['fragment' => "information"], true);
    }

    public function selectionnerGestionnairesAction(): ViewModel
    {
        $session = $this->getFormationInstanceService()->getRequestedFormationInstance($this, 'session');

        $form = $this->getSelectionGestionnaireForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation-instance/selectionner-gestionnaires', ['session' => $session->getId()], [], true));
        $form->bind($session);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if (!isset($data['gestionnaires'])) $data['gestionnaires'] = [];
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormationInstanceService()->update($session);
            }
        }

        $vm = new ViewModel([
            'title' => "Sélectionner les gestionnaires pour cette session",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;

    }

    /** WORKFLOW  *****************************************************************************************************/
    public function ouvrirInscriptionAction(): Response
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $this->getFormationInstanceService()->ouvrirInscription($instance);

        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $instance->getId()], [], true);
    }

    public function fermerInscriptionAction(): Response
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $this->getFormationInstanceService()->fermerInscription($instance);


        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $instance->getId()], [], true);
    }

    public function envoyerConvocationAction(): Response
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $this->getFormationInstanceService()->envoyerConvocation($instance);
        $this->getFormationInstanceService()->envoyerEmargement($instance);
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $instance->getId()], [], true);
    }

    public function demanderRetourAction(): Response
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $this->getFormationInstanceService()->demanderRetour($instance);
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $instance->getId()], [], true);
    }

    public function cloturerAction(): Response
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $this->getFormationInstanceService()->cloturer($instance);
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $instance->getId()], [], true);
    }

    public function annulerAction(): Response
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $this->getFormationInstanceService()->annuler($instance);
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $instance->getId()], [], true);
    }

    public function reouvrirAction(): Response
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $this->getFormationInstanceService()->reouvrir($instance);
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $instance->getId()], [], true);
    }

    public function changerEtatAction(): ViewModel
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $etat = $this->getEtatTypeService()->getEtatType($data['etat']);

            switch ($etat->getCode()) {
                case SessionEtats::ETAT_CREATION_EN_COURS :
                    $this->getFormationInstanceService()->recreation($instance);
                    exit();
                case SessionEtats::ETAT_INSCRIPTION_OUVERTE :
                    $this->getFormationInstanceService()->ouvrirInscription($instance);
                    exit();
                case SessionEtats::ETAT_INSCRIPTION_FERMEE :
                    $this->getFormationInstanceService()->fermerInscription($instance);
                    exit();
                case SessionEtats::ETAT_FORMATION_CONVOCATION :
                    $this->getFormationInstanceService()->envoyerConvocation($instance);
                    $this->getFormationInstanceService()->envoyerEmargement($instance);
                    exit();
                case SessionEtats::ETAT_ATTENTE_RETOURS :
                    $this->getFormationInstanceService()->demanderRetour($instance);
                    exit();
                case SessionEtats::ETAT_CLOTURE_INSTANCE :
                    $this->getFormationInstanceService()->cloturer($instance);
                    exit();
                case SessionEtats::ETAT_SESSION_ANNULEE :
                    $this->getFormationInstanceService()->annuler($instance);
                    exit();
                default :
            }

            exit();
        }

        return new ViewModel([
            'title' => "Changer l'état de la session de formation",
            'etats' => $this->getEtatTypeService()->getEtatsTypesByCategorieCode(SessionEtats::TYPE),
            'instance' => $instance,
        ]);
    }

    public function rechercherAction(): JsonModel
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $sessions = $this->getFormationInstanceService()->getSessionByTerm($term);
            $result = $this->getFormationInstanceService()->formatSessionJSON($sessions);
            return new JsonModel($result);
        }
        exit;
    }

    public function exporterInscriptionAction(): CsvModel
    {
        $session = $this->getFormationInstanceService()->getRequestedFormationInstance($this, 'session');
        $inscriptions = $session->getInscriptions();

        $inscriptions = array_filter($inscriptions, function (Inscription $inscription) { return $inscription->estNonHistorise();});
        $inscriptions = array_filter($inscriptions, function (Inscription $inscription) { return in_array($inscription->getListe(), [Inscription::PRINCIPALE, Inscription::COMPLEMENTAIRE]) ;});

        $headers = [ 'Liste', 'Prenom', 'Nom', 'Adresse électronique', 'Date de naissance', 'Grade', 'Affectation'];

        $records = [];
        /** @var Inscription $inscription */
        foreach ($inscriptions as $inscription) {
            $item = [];
            $item[] = $inscription->getListe();
            if ($inscription->isInterne()) {
                $individu = $inscription->getIndividu();
                $item[] = $individu->getPrenom();
                $item[] = $individu->getNomUsuel()??$individu->getNomFamille();
                $item[] = $individu->getEmail();
                $item[] = $individu->getDateNaissance();
                $gradeTexte = [];
                foreach($individu->getGradesActifs($session->getDebut(true)) as $grade) {
                    $gradeTexte[] = $grade->getGrade()->getLibelleLong();
                }
                $item[] = implode(", ", $gradeTexte);
                $affectationTexte = [];
                foreach($individu->getAffectationsActifs($session->getDebut(true)) as $affectation) {
                    if ($affectation->getStructure()->getNiv2() !== null) {
                        $structure = $affectation->getStructure()->getNiv2()->getLibelleLong() ." > ". $affectation->getStructure()->getLibelleLong();
                    } else {
                        $structure = $affectation->getStructure()->getLibelleLong();
                    }
                    $affectationTexte[] = $structure;
                }
                $item[] = implode(", ", $affectationTexte);
            }
            if ($inscription->isExterne()) {
                $item[] = $inscription->getStagiaire()->getPrenom();
                $item[] = $inscription->getStagiaire()->getNom();
                $item[] = $inscription->getStagiaire()->getEmail();
                $item[] = $inscription->getStagiaire()->getDateNaissance();
                $item[] = "";
                $item[] = $inscription->getStagiaire()->getStructure();
            }

            $records[] = $item;
        }

        $date = (new DateTime())->format('Ymd-His');
        $filename="export_inscriptions_sessions_".$date.".csv";
        $CSV = new CsvModel();
        $CSV->setDelimiter(';');
        $CSV->setEnclosure('"');
        $CSV->setHeader($headers);
        $CSV->setData($records);
        $CSV->setFilename($filename);

        return $CSV;
    }

}