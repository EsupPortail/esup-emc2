<?php

namespace Formation\Controller;

use DateTime;
use Formation\Entity\Db\Inscription;
use Formation\Form\SelectionFormateur\SelectionFormateurFormAwareTrait;
use Formation\Form\SelectionGestionnaire\SelectionGestionnaireFormAwareTrait;
use Formation\Form\Session\SessionFormAwareTrait;
use Formation\Provider\Etat\SessionEtats;
use Formation\Provider\Parametre\FormationParametres;
use Formation\Provider\Role\FormationRoles;
use Formation\Service\Formateur\FormateurServiceAwareTrait;
use Formation\Service\Formation\FormationServiceAwareTrait;
use Formation\Service\FormationGroupe\FormationGroupeServiceAwareTrait;
use Formation\Service\Inscription\InscriptionServiceAwareTrait;
use Formation\Service\InscriptionFrais\InscriptionFraisServiceAwareTrait;
use Formation\Service\Notification\NotificationServiceAwareTrait;
use Formation\Service\Presence\PresenceServiceAwareTrait;
use Formation\Service\Session\SessionServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use UnicaenApp\View\Model\CsvModel;
use UnicaenEnquete\Service\Enquete\EnqueteServiceAwareTrait;
use UnicaenEnquete\Service\Resultat\ResultatServiceAwareTrait;
use UnicaenEtat\Service\EtatCategorie\EtatCategorieServiceAwareTrait;
use UnicaenEtat\Service\EtatType\EtatTypeServiceAwareTrait;
use UnicaenMail\Service\Mail\MailServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

/** @method FlashMessenger flashMessenger() */
class SessionController extends AbstractActionController
{
    use EnqueteServiceAwareTrait;
    use EtatCategorieServiceAwareTrait;
    use EtatTypeServiceAwareTrait;
    use FormateurServiceAwareTrait;
    use FormationServiceAwareTrait;
    use FormationGroupeServiceAwareTrait;
    use SessionServiceAwareTrait;
    use InscriptionServiceAwareTrait;
    use InscriptionFraisServiceAwareTrait;
    use MailServiceAwareTrait;
    use NotificationServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use PresenceServiceAwareTrait;
    use ResultatServiceAwareTrait;
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

        $instances = $this->getSessionService()->getSessionsWithParams($params);

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
        $instance = $this->getSessionService()->createSession($formation);

        $user = $this->getUserService()->getConnectedUser();
        $instance->addGestionnaire($user);
        $this->getSessionService()->update($instance);


        return $this->redirect()->toRoute('formation/session/afficher', ['session' => $instance->getId()], [], true);
    }

    public function ajouterAvecFormulaireAction(): ViewModel
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $formation_ = $data['formation']['id'];
            $formation = $this->getFormationService()->getFormation($formation_);

            if ($formation) {
                $instance = $this->getSessionService()->createSession($formation);
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
        $session = $this->getSessionService()->getRequestedSession($this);
        $mails = $this->getMailService()->getMailsByMotClef($session->generateTag());

        $presences = $this->getPresenceService()->getPresenceBySession($session);
        $presencesManquantes = $this->getPresenceService()->getPresencesManquantes($session);
        $fraisManquants = $this->getInscriptionFraisService()->getFraisManquants($session);

        $dictionnaire = [];
        foreach ($presences as $presence) {
            $dictionnaire[$presence->getJournee()->getId()][$presence->getInscription()->getId()] = $presence;
        }

        return new ViewModel([
            'session' => $session,
            'mode' => "affichage",
            'presences' => $dictionnaire,
            'fraisManquants' => $fraisManquants,
            'presencesManquantes' => $presencesManquantes,
            'mails' => $mails,
        ]);
    }

    public function historiserAction(): ViewModel
    {
        $session = $this->getSessionService()->getRequestedSession($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getSessionService()->historise($session);
            exit();
        }

        $vm = new ViewModel();
        if ($session !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Historisation d'une instance de formation",
                'text' => "Est-vous sûr·e de vouloir historiser la session".$session->getInstanceLibelle()." du ".$session->getPeriode()." ?",
                'action' => $this->url()->fromRoute('formation/session/historiser', ['session' => $session->getId()], [], true),
            ]);
        }
        return $vm;
    }

    public function restaurerAction(): Response
    {
        $session = $this->getSessionService()->getRequestedSession($this);
        $this->getSessionService()->restore($session);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('formation/session', [], [], true);
    }

    public function supprimerAction(): ViewModel
    {
        $session = $this->getSessionService()->getRequestedSession($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getSessionService()->delete($session);
            exit();
        }

        $vm = new ViewModel();
        if ($session !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression d'une instance de formation",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('formation/session/supprimer', ["session" => $session->getId()], [], true),
            ]);
        }
        return $vm;
    }

    public function modifierInformationsAction(): ViewModel
    {
        $session = $this->getSessionService()->getRequestedSession($this);

        $form = $this->getSessionForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/session/modifier-informations', ['session' => $session->getId()], [], true));
        $form->bind($session);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getSessionService()->update($session);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/default-form');
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
        $session = $this->getSessionService()->getRequestedSession($this);
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
                    $this->getSessionService()->update($session);
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
        $session = $this->getSessionService()->getRequestedSession($this);
        $formateur = $this->getFormateurService()->getRequestedFormateur($this);

        $this->getSessionService()->retirerFormateur($session, $formateur);
        return $this->redirect()->toRoute('formation/session/afficher', ['session' => $session->getId()], ['fragment' => "information"], true);
    }

    public function selectionnerGestionnairesAction(): ViewModel
    {
        $session = $this->getSessionService()->getRequestedSession($this);

        $form = $this->getSelectionGestionnaireForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/session/selectionner-gestionnaires', ['session' => $session->getId()], [], true));
        $form->bind($session);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if (!isset($data['gestionnaires'])) $data['gestionnaires'] = [];
            $form->setData($data);
            if ($form->isValid()) {
                $this->getSessionService()->update($session);
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
        $session = $this->getSessionService()->getRequestedSession($this);
        $this->getSessionService()->ouvrirInscription($session);

        return $this->redirect()->toRoute('formation/session/afficher', ['session' => $session->getId()], [], true);
    }

    public function fermerInscriptionAction(): Response
    {
        $instance = $this->getSessionService()->getRequestedSession($this);
        $this->getSessionService()->fermerInscription($instance);


        return $this->redirect()->toRoute('formation/session/afficher', ['session' => $instance->getId()], [], true);
    }

    public function envoyerConvocationAction(): Response
    {
        $session = $this->getSessionService()->getRequestedSession($this);
        $this->getSessionService()->envoyerConvocation($session);
        $this->getSessionService()->envoyerEmargement($session);
        return $this->redirect()->toRoute('formation/session/afficher', ['session' => $session->getId()], [], true);
    }

    public function demanderRetourAction(): Response
    {
        $session = $this->getSessionService()->getRequestedSession($this);
        $this->getSessionService()->demanderRetour($session);
        return $this->redirect()->toRoute('formation/session/afficher', ['session' => $session->getId()], [], true);
    }

    public function cloturerAction(): Response
    {
        $session = $this->getSessionService()->getRequestedSession($this);
        $this->getSessionService()->cloturer($session);
        return $this->redirect()->toRoute('formation/session/afficher', ['session' => $session->getId()], [], true);
    }

    public function annulerAction(): Response
    {
        $session = $this->getSessionService()->getRequestedSession($this);
        $this->getSessionService()->annuler($session);
        return $this->redirect()->toRoute('formation/session/afficher', ['session' => $session->getId()], [], true);
    }

    public function reouvrirAction(): Response
    {
        $session = $this->getSessionService()->getRequestedSession($this);
        $this->getSessionService()->reouvrir($session);
        return $this->redirect()->toRoute('formation/session/afficher', ['session' => $session->getId()], [], true);
    }

    public function changerEtatAction(): ViewModel
    {
        $session = $this->getSessionService()->getRequestedSession($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $etat = $this->getEtatTypeService()->getEtatType($data['etat']);

            switch ($etat->getCode()) {
                case SessionEtats::ETAT_CREATION_EN_COURS :
                    $this->getSessionService()->recreation($session);
                    exit();
                case SessionEtats::ETAT_INSCRIPTION_OUVERTE :
                    $this->getSessionService()->ouvrirInscription($session);
                    exit();
                case SessionEtats::ETAT_INSCRIPTION_FERMEE :
                    $this->getSessionService()->fermerInscription($session);
                    exit();
                case SessionEtats::ETAT_FORMATION_CONVOCATION :
                    $this->getSessionService()->envoyerConvocation($session);
                    $this->getSessionService()->envoyerEmargement($session);
                    exit();
                case SessionEtats::ETAT_ATTENTE_RETOURS :
                    $this->getSessionService()->demanderRetour($session);
                    exit();
                case SessionEtats::ETAT_CLOTURE_INSTANCE :
                    $this->getSessionService()->cloturer($session);
                    exit();
                case SessionEtats::ETAT_SESSION_ANNULEE :
                    $this->getSessionService()->annuler($session);
                    exit();
                default :
            }

            exit();
        }

        return new ViewModel([
            'title' => "Changer l'état de la session de formation",
            'etats' => $this->getEtatTypeService()->getEtatsTypesByCategorieCode(SessionEtats::TYPE),
            'session' => $session,
        ]);
    }

    public function resultatEnqueteAction(): ViewModel
    {
        $session = $this->getSessionService()->getRequestedSession($this);

        $code_enquete = $this->getParametreService()->getValeurForParametre(FormationParametres::TYPE, FormationParametres::CODE_ENQUETE);
        $enquete = $this->getEnqueteService()->getEnqueteByCode($code_enquete);

        $inscriptions = $this->getInscriptionService()->getInscriptionsBySession($session);
        [$counts, $results] = $this->getResultatService()->generateResultatArray($enquete, $inscriptions);

        $vm = new ViewModel([
            'enquete' => $enquete,
            'results' => $results,
            'counts' => $counts,
            'elements' => $inscriptions,

            'retourLibelle' => "Accéder à la session de formation",
            'retourIcone' => "icon icon-retour",
            /** @see SessionController::afficherAction() */
            'retourUrl' => $this->url()->fromRoute('formation/session/afficher', ['session' => $session->getId()] ,[], true),
        ]);
        $vm->setTemplate('unicaen-enquete/resultat/resultats');
        return $vm;
    }

    public function rechercherAction(): JsonModel
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $sessions = $this->getSessionService()->getSessionsByTerm($term);
            $result = $this->getSessionService()->formatSessionJSON($sessions);
            return new JsonModel($result);
        }
        exit;
    }

    public function exporterInscriptionAction(): CsvModel
    {
        $session = $this->getSessionService()->getRequestedSession($this);
        $inscriptions = $session->getInscriptions();

        $inscriptions = array_filter($inscriptions, function (Inscription $inscription) { return $inscription->estNonHistorise();});
        $inscriptions = array_filter($inscriptions, function (Inscription $inscription) { return in_array($inscription->getListe(), [Inscription::PRINCIPALE, Inscription::COMPLEMENTAIRE]) ;});

        $headers = [ 'Liste', 'Prenom', 'Nom usuel', 'Nom patronymique', 'Adresse électronique', 'Date de naissance', 'Grade', 'Affectation'];

        $records = [];
        /** @var Inscription $inscription */
        foreach ($inscriptions as $inscription) {
            $item = [];
            $item[] = $inscription->getListe();
            if ($inscription->isInterne()) {
                $individu = $inscription->getIndividu();
                $item[] = $individu->getPrenom();
                $item[] = $individu->getNomUsuel();
                $item[] = $individu->getNomFamille();
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
                $item[] = $inscription->getStagiaire()->getNomFamille();
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