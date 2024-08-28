<?php

namespace Formation\Controller;

use Application\Entity\Db\Interfaces\HasSourceInterface;
use Application\Service\Agent\AgentServiceAwareTrait;
use DateTime;
use Fichier\Controller\FichierController;
use Fichier\Entity\Db\Fichier;
use Fichier\Form\Upload\UploadFormAwareTrait;
use Fichier\Service\Fichier\FichierServiceAwareTrait;
use Fichier\Service\Nature\NatureServiceAwareTrait;
use Formation\Entity\Db\Formation;
use Formation\Entity\Db\Inscription;
use Formation\Entity\Db\InscriptionFrais;
use Formation\Entity\Db\Seance;
use Formation\Form\Inscription\InscriptionFormAwareTrait;
use Formation\Form\InscriptionFrais\InscriptionFraisFormAwareTrait;
use Formation\Form\Justification\JustificationFormAwareTrait;
use Formation\Provider\Etat\InscriptionEtats;
use Formation\Provider\FichierNature\FichierNature;
use Formation\Provider\Parametre\FormationParametres;
use Formation\Provider\Source\Sources;
use Formation\Service\Inscription\InscriptionServiceAwareTrait;
use Formation\Service\InscriptionFrais\InscriptionFraisServiceAwareTrait;
use Formation\Service\Notification\NotificationServiceAwareTrait;
use Formation\Service\Session\SessionServiceAwareTrait;
use Laminas\Form\Element\Select;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\View\Model\ViewModel;
use UnicaenEnquete\Service\Enquete\EnqueteServiceAwareTrait;
use UnicaenEnquete\Service\Instance\InstanceServiceAwareTrait;
use UnicaenEtat\Service\EtatInstance\EtatInstanceServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

/** @method FlashMessenger flashMessenger() */
class InscriptionController extends AbstractActionController
{
    use AgentServiceAwareTrait;
    use InscriptionServiceAwareTrait;
    use EnqueteServiceAwareTrait;
    use EtatInstanceServiceAwareTrait;
    use FichierServiceAwareTrait;
    use InstanceServiceAwareTrait;
    use NatureServiceAwareTrait;
    use NotificationServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use SessionServiceAwareTrait;
    use UserServiceAwareTrait;

    use InscriptionFormAwareTrait;
    use InscriptionFraisServiceAwareTrait;
    use InscriptionFraisFormAwareTrait;
    use JustificationFormAwareTrait;
    use UploadFormAwareTrait;


    /** CRUD ******************************************************************************************************** */

    public function indexAction(): ViewModel
    {
        $inscriptions = $this->getInscriptionService()->getInscriptions('histoCreation', 'DESC', true);

        return new ViewModel([
            'inscriptions' => $inscriptions,
        ]);
    }

    public function ajouterAction(): ViewModel
    {
        $session = $this->getSessionService()->getRequestedSession($this);

        $inscription = new Inscription();
        if ($session) $inscription->setSession($session);
        $inscription->setSource(Sources::EMC2);

        $form = $this->getInscriptionForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/inscription/ajouter', ['session' => ($session) ? $session->getId() : null], [], true));
        $form->bind($inscription);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                if ($inscription->getIndividu() !== null) {
                    $inscription->setIdSource($inscription->getSession()->getId() . '_' . $inscription->getIndividu()->getId());
                    $this->getInscriptionService()->create($inscription);
                    $this->getEtatInstanceService()->setEtatActif($inscription, InscriptionEtats::ETAT_VALIDER_DRH);
                    $this->getInscriptionService()->update($inscription);

                    $this->getSessionService()->classerInscription($inscription);
                }
            }
            exit();
        }

        $vm = new ViewModel([
            'title' => "Ajout d'une inscription",
            'form' => $form,
        ]);
        $vm->setTemplate('formation/inscription/modifier');
        return $vm;
    }

    public function modifierAction(): ViewModel
    {
        $inscription = $this->getInscriptionService()->getRequestedInscription($this);

        $form = $this->getInscriptionForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/inscription/modifier', ['inscription' => $inscription->getId()], [], true));
        $form->bind($inscription);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                if ($inscription->getIndividu() !== null) {
                    $inscription->setIdSource($inscription->getSession()->getId() . '_' . $inscription->getIndividu()->getId());
                    $this->getInscriptionService()->update($inscription);
                }
            }
            exit();
        }

        $vm = new ViewModel([
            'title' => "Modification de l'inscription",
            'form' => $form,
        ]);
        $vm->setTemplate('formation/inscription/modifier');
        return $vm;
    }

    public function afficherAction(): ViewModel
    {
        $inscription = $this->getInscriptionService()->getRequestedInscription($this);

        return new ViewModel([
            'title' => "Visualisation de l'inscription",
            'inscription' => $inscription,
        ]);
    }

    public function historiserAction(): Response
    {
        $inscrit = $this->getInscriptionService()->getRequestedInscription($this);
        $this->getInscriptionService()->historise($inscrit);
        $this->flashMessenger()->addSuccessMessage("L'inscription de <strong>" . $inscrit->getStagiaireDenomination() . "</strong> vient d'être retirée des listes.");

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('formation/session/afficher', ['session' => $inscrit->getSession()->getId()], ['fragment' => 'inscriptions'], true);
    }

    public function restaurerAction(): Response
    {
        $inscrit = $this->getInscriptionService()->getRequestedInscription($this);
        $this->getInscriptionService()->restore($inscrit);
        $this->flashMessenger()->addSuccessMessage("L'inscription de <strong>" . $inscrit->getStagiaireDenomination() . "</strong> vient d'être restaurée.");

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('formation/session/afficher', ['session' => $inscrit->getSession()->getId()], [], true);

    }

    public function supprimerAction(): ViewModel
    {
        $inscrit = $this->getInscriptionService()->getRequestedInscription($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getInscriptionService()->delete($inscrit);
            exit();
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/confirmation');
        if ($inscrit !== null) {
            $vm->setVariables([
                'title' => "Suppression de l'inscription de [" . $inscrit->getStagiaireDenomination() . "]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('formation/inscription/supprimer', ["inscription" => $inscrit->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** VALIDATION DES INSCRIPTIONS ********************************************************************************* */

    public function validerResponsableAction(): ViewModel
    {
        $inscription = $this->getInscriptionService()->getRequestedInscription($this);
        $instance = $inscription->getSession();

        $form = $this->getJustificationForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/inscription/valider-responsable', ['inscription' => $inscription->getId()], [], true));
        $form->bind($inscription);
        $form->get('etape')->setValue('RESPONSABLE');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $justification = (isset($data['justification']) && trim($data['justification']) !== '') ? trim($data['justification']) : null;
                if ($justification === null) {
                    $this->flashMessenger()->addErrorMessage("<span class='text-danger'><strong> Échec de la validation </strong></span> <br/> Veuillez justifier votre validation !");
                } else {
                    $inscription->setJustificationResponsable($justification);
                    $this->getEtatInstanceService()->setEtatActif($inscription, InscriptionEtats::ETAT_VALIDER_RESPONSABLE);
                    $this->getInscriptionService()->update($inscription);
                    $this->flashMessenger()->addSuccessMessage("Validation effectuée.");
                    $this->getNotificationService()->triggerResponsableValidation($inscription);
                }
            }
        }

        $vm = new ViewModel([
            'title' => "Validation de l'inscription de " . $inscription->getStagiaireDenomination() . " à la formation " . $instance->getInstanceLibelle() . " du " . $instance->getPeriode(),
            'inscription' => $inscription,
            'form' => $form,
        ]);
        $vm->setTemplate('formation/formation-instance-inscrit/inscription');
        return $vm;
    }

    public function refuserResponsableAction(): ViewModel
    {
        $inscription = $this->getInscriptionService()->getRequestedInscription($this);
        $instance = $inscription->getSession();

        $form = $this->getJustificationForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/inscription/refuser-responsable', ['inscription' => $inscription->getId()], [], true));
        $form->bind($inscription);
        $form->get('etape')->setValue('REFUS');
        $form->get('missions')->setValue($inscription->getMissions());
        $form->get('rqth')->setValue($inscription->isRqth()?"1":"0");

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getEtatInstanceService()->setEtatActif($inscription, InscriptionEtats::ETAT_VALIDER_RESPONSABLE);
                $this->getInscriptionService()->historise($inscription);
                $this->flashMessenger()->addSuccessMessage("Refus effectué.");
                $this->getNotificationService()->triggerResponsableRefus($inscription);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "refus de l'inscription de " . $inscription->getStagiaireDenomination() . " à la formation " . $instance->getInstanceLibelle() . " du " . $instance->getPeriode(),
            'inscription' => $inscription,
            'form' => $form,
        ]);
        $vm->setTemplate('formation/formation-instance-inscrit/inscription');
        return $vm;
    }

    public function validerDrhAction(): ViewModel
    {
        $inscription = $this->getInscriptionService()->getRequestedInscription($this);
        $session = $inscription->getSession();

        $form = $this->getJustificationForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/inscription/valider-drh', ['inscription' => $inscription->getId()], [], true));
        $form->bind($inscription);
        $form->get('etape')->setValue('DRH');
        $form->get('justification')->setValue('Validation du bureau de gestion des formations');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getEtatInstanceService()->setEtatActif($inscription, InscriptionEtats::ETAT_VALIDER_DRH);
                $this->getInscriptionService()->update($inscription);
                $this->getSessionService()->classerInscription($inscription);
                $this->flashMessenger()->addSuccessMessage("Validation effectuée.");
                $this->getNotificationService()->triggerDrhValidation($inscription);
                if ($inscription->getListe() === Inscription::PRINCIPALE
                    and $inscription->getSession()->getFormation()->getRattachement() === Formation::RATTACHEMENT_PREVENTION) {
                    $this->getNotificationService()->triggerPrevention($inscription);
                }
            }
        }

        $vm = new ViewModel([
            'title' => "Validation de l'inscription de " . $inscription->getStagiaireDenomination() . " à la formation " . $session->getInstanceLibelle() . " du " . $session->getPeriode(),
            'inscription' => $inscription,
            'form' => $form,
        ]);
        $vm->setTemplate('formation/formation-instance-inscrit/inscription');
        return $vm;
    }

    public function refuserDrhAction(): ViewModel
    {
        $inscription = $this->getInscriptionService()->getRequestedInscription($this);
        $instance = $inscription->getSession();

        $form = $this->getJustificationForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/inscription/refuser-drh', ['inscription' => $inscription->getId()], [], true));
        $form->bind($inscription);
        $form->get('etape')->setValue('REFUS');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $justification = (isset($data['justification']) && trim($data['justification']) !== '') ? trim($data['justification']) : null;
                if ($justification === null) {
                    $this->flashMessenger()->addErrorMessage("<strong> Échec du refus </strong> <br/> Veuillez justifier votre refus !");
                } else {
                    $inscription->setJustificationRefus($justification);
                    $this->getEtatInstanceService()->setEtatActif($inscription, InscriptionEtats::ETAT_REFUSER);
                    $this->getInscriptionService()->historise($inscription);
                    $this->flashMessenger()->addSuccessMessage("Refus effectué.");
                    $this->getNotificationService()->triggerDrhRefus($inscription);
                }
            }
        }

        $vm = new ViewModel([
            'title' => "refus de l'inscription de " . $inscription->getStagiaireDenomination() . " à la formation " . $instance->getInstanceLibelle() . " du " . $instance->getPeriode(),
            'inscription' => $inscription,
            'form' => $form,
        ]);
        $vm->setTemplate('formation/formation-instance-inscrit/inscription');
        return $vm;
    }

    /** Gestion des listes ********************************************************************************************/

    public function envoyerListePrincipaleAction(): Response
    {
        $inscription = $this->getInscriptionService()->getRequestedInscription($this);

        $inscription->setListe(Inscription::PRINCIPALE);
        $this->getInscriptionService()->update($inscription);

        $this->getNotificationService()->triggerListePrincipale($inscription);

        $this->flashMessenger()->addSuccessMessage("<strong>" . $inscription->getStagiaireDenomination() . "</strong> vient d'être ajouté&middot;e en liste principale.");

        return $this->redirect()->toRoute('formation/session/afficher', ['session' => $inscription->getSession()->getId()], [], true);
    }

    public function envoyerListeComplementaireAction(): Response
    {
        $inscription = $this->getInscriptionService()->getRequestedInscription($this);

        $inscription->setListe(Inscription::COMPLEMENTAIRE);
        $this->getInscriptionService()->update($inscription);

        $this->getNotificationService()->triggerListeComplementaire($inscription);


        $this->flashMessenger()->addSuccessMessage("<strong>" . $inscription->getStagiaireDenomination() . "</strong> vient d'être ajouté&middot;e en liste complémentaire.");

        return $this->redirect()->toRoute('formation/session/afficher', ['session' => $inscription->getSession()->getId()], [], true);
    }

    public function retirerListeAction(): Response
    {
        $inscription = $this->getInscriptionService()->getRequestedInscription($this);

        $inscription->setListe(null);
        $this->getInscriptionService()->update($inscription);

        $this->getNotificationService()->triggerRetraitListe($inscription);


        $this->flashMessenger()->addSuccessMessage("<strong>" . $inscription->getStagiaireDenomination() . "</strong> vient d'être retiré·e des listes.");

        return $this->redirect()->toRoute('formation/session/afficher', ['session' => $inscription->getSession()->getId()], [], true);
    }

    public function classerAction(): Response
    {
        $inscription = $this->getInscriptionService()->getRequestedInscription($this);

        $this->getSessionService()->classerInscription($inscription);

        switch ($inscription->getListe()) {
            case Inscription::PRINCIPALE :
                $this->flashMessenger()->addSuccessMessage("Classement de l'inscription en liste principale.");
                break;
            case Inscription::COMPLEMENTAIRE :
                $this->flashMessenger()->addWarningMessage("Liste principale complète. <br/> Classement de l'inscription en liste complémentaire.");
                break;
            default :
                $this->flashMessenger()->addErrorMessage("Plus de place en liste principale et complémentaire. <br/> Échec du classement de l'inscription.");
                break;
        }

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);

        $session = $inscription->getSession();
        return $this->redirect()->toRoute('formation/session/afficher', ['session' => $session->getId()], ['fragment' => 'inscriptions'], true);
    }

    /** FRAIS  ********************************************************************************************************/

    public function renseignerFraisAction(): ViewModel
    {
        $inscription = $this->getInscriptionService()->getRequestedInscription($this);
        $frais = $inscription->getFrais();
        if ($frais === null) {
            $frais = new InscriptionFrais();
            $frais->setInscrit($inscription);
            $this->getInscriptionFraisService()->create($frais);
        }


        $form = $this->getInscriptionFraisForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/inscription/renseigner-frais', ['inscription' => $inscription->getId()], [], true));
        $form->bind($frais);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getInscriptionFraisService()->update($frais);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/default-form');
        $vm->setVariables([
            'title' => "Saisie des frais de " . $inscription->getStagiaireDenomination(),
            'form' => $form,
        ]);
        return $vm;
    }

    /** FICHIERS ASSOCIES *********************************************************************************************/

    public function televerserAttestationAction(): ViewModel
    {
        $inscription = $this->getInscriptionService()->getRequestedInscription($this);

        $fichier = new Fichier();
        $nature = $this->getNatureService()->getNatureByCode(FichierNature::INSCRIPTION_ATTESTATION);
        $fichier->setNature($nature);
        $form = $this->getUploadForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/inscription/televerser-attestation', ['inscription' => $inscription->getId()], [], true));
        /** @var Select $select */
        $select = $form->get('nature');
        $select->setValueOptions([$nature->getId() => $nature->getLibelle()]);
        $form->bind($fichier);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $files = $request->getFiles();
            $file = $files['fichier'];

            if ($file['name'] != '') {
                $nature = $this->getNatureService()->getNature($data['nature']);
                $fichier = $this->getFichierService()->createFichierFromUpload($file, $nature);
                $inscription->addFichier($fichier);
                $this->getInscriptionService()->update($inscription);
            }
            exit();
        }

        $vm = new ViewModel();
        $vm->setTemplate('formation/default/default-form');
        $vm->setVariables([
            'title' => 'Téléverserment d\'un fichier',
            'form' => $form,
        ]);
        return $vm;
    }

    public function telechargerAttestationAction(): ViewModel|Response
    {
        $inscription = $this->getInscriptionService()->getRequestedInscription($this);
        $fichier = $this->getFichierService()->getRequestedFichier($this, 'attestation');

        if ($inscription->hasFichier($fichier)) {
            /** @see FichierController::downloadAction() */
            return $this->redirect()->toRoute('download-fichier', ['fichier' => $fichier->getId()]);
        }

        $vm =  new ViewModel([
            'title' => "Impossible de télécharger l'attestation",
            'reponse' => "L'attestation est incohérente avec l'inscription",
        ]);
        $vm->setTemplate('default/reponse');
        return $vm;
    }

    public function supprimerAttestationAction(): ViewModel|Response
    {
        $inscription = $this->getInscriptionService()->getRequestedInscription($this);
        $fichier = $this->getFichierService()->getRequestedFichier($this, 'attestation');

        if ($inscription->hasFichier($fichier)) {
            $this->getFichierService()->delete($fichier);

            $retour = $this->params()->fromQuery('retour');
            if ($retour) return $this->redirect()->toUrl($retour);
            return $this->redirect()->toRoute('formation/inscription');
        }

        $vm =  new ViewModel([
            'title' => "Impossible de supprimer l'attestation",
            'reponse' => "L'attestation est incohérente avec l'inscription",
        ]);
        $vm->setTemplate('default/reponse');
        return $vm;
    }

    /** Gestion d'une inscription *************************************************************************************/

    public function inscriptionAction(): ViewModel
    {
        $session = $this->getSessionService()->getRequestedSession($this);
        $agent = $this->getAgentService()->getRequestedAgent($this);

        $inscription = new Inscription();
        $inscription->setSession($session);
        $inscription->setAgent($agent);

        $form = $this->getJustificationForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/inscription/creer-inscription', ['session' => $session->getId(), 'agent' => $agent->getId()], [], true));
        $form->bind($inscription);
        $form->get('etape')->setValue('AGENT');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $plafond = $this->getParametreService()->getValeurForParametre(FormationParametres::TYPE, FormationParametres::INSCRIPTION_PLAFOND_ANNUEL);
                $volumeAnnuel = $this->getInscriptionService()->getVolumeAnnuelByAgent($agent, $session->getDebut(true)->format("Y")) + $session->getDuree(true);

                $justification = (isset($data['justification']) && trim($data['justification']) !== '') ? trim($data['justification']) : null;
                if ($justification === null) {
                    $this->flashMessenger()->addErrorMessage("<span class='text-danger'><strong> Échec de l'inscription  </strong></span> <br/> Veuillez motiver votre demande d'inscription!");
                } else {

                    //check already inscrit
                    $probleme = [];
                    foreach ($session->getSeances() as $seance) {
                        $blocage = $this->getInscriptionService()->checkDisponibiliteAgent($agent, $seance);
                        if (!empty($blocage)) $probleme[$seance->getId()] = $blocage;
                    }
                    //check volume
                    if (!empty($probleme)) {
                        $seances = [];
                        foreach ($session->getSeances() as $seance) $seances[$seance->getId()] = $seance;
                        $message = "Vous êtes déjà inscrit·e à des formations pour certaine·s séance·s de cette formation : ";
                        /** @var Seance $item */
                        $message .= "<ul>";
                        foreach ($probleme as $seanceCibleId => $seanceItem) {
                            foreach ($seanceItem as $item) {
                                $message .= "<li>";
                                $message .= "vous n'êtes pas disponible pour la séance du " . $seances[$seanceCibleId]->getDateDebut()->format("d/m/Y") . " de " . $seances[$seanceCibleId]->getDateDebut()->format("H:i") . " au " . $seances[$seanceCibleId]->getDateFin()->format("H:i") . " ";
                                $message .= " car vous êtes inscrit·e à la session " . $item->getInstance()->getInstanceLibelle() . " #" . $item->getInstance()->getId();
                                $message .= " (séance du " . $item->getDateDebut()->format("d/m/Y") . " de " . $item->getDateDebut()->format("H:i") . " au " . $item->getDateFin()->format("H:i") . ")";
                                $message .= "</li>";
                            }
                        }
                        $message .= "</ul>";
//                        $this->flashMessenger()->addErrorMessage("<span class=''>".$message."</span>");
                        $vm = new ViewModel([
                            'title' => "Inscription à la session " . $session->getInstanceLibelle() . " du " . $session->getPeriode(),
                            'ok' => false,
                            'message' => $message,
                        ]);
                        $vm->setTemplate('formation/inscription/resultat');
                        return $vm;
                    } elseif ($plafond !== null AND $plafond !== 0 AND ($volumeAnnuel) >= $plafond) {
                        $message = "Cette inscription vous ferait dépasser le plafond horaire annuel de formation (plafond de ".$plafond." heure·s pour un volume suivi de" . $volumeAnnuel . " heure·s.)";
                        $vm = new ViewModel([
                            'title' => "Inscription à la session " . $session->getInstanceLibelle() . " du " . $session->getPeriode(),
                            'ok' => false,
                            'message' => $message,
                        ]);
                        $vm->setTemplate('formation/inscription/resultat');
                        return $vm;
                    } else {
                        $inscription->setJustificationAgent($justification);
                        $inscription->setSource(HasSourceInterface::SOURCE_EMC2);
                        $this->getInscriptionService()->create($inscription);
                        $this->getEtatInstanceService()->setEtatActif($inscription, InscriptionEtats::ETAT_DEMANDE);
                        $this->getInscriptionService()->update($inscription);
                        $this->flashMessenger()->addSuccessMessage("Demande d'inscription faite.");
                        $this->getNotificationService()->triggerInscriptionAgent($agent, $session);
                    }
                }
            }
        }

        return new ViewModel([
            'title' => "Inscription à la formation " . $session->getInstanceLibelle() . " du " . $session->getPeriode(),
            'inscription' => $inscription,
            'form' => $form,
        ]);
    }

    public function desinscriptionAction(): ViewModel
    {
        $inscription = $this->getInscriptionService()->getRequestedInscription($this);
        $instance = $inscription->getSession();
        $agent = $inscription->getAgent();


        $form = $this->getJustificationForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/inscription/annuler-inscription', ['inscrit' => $inscription->getId(), 'agent' => $agent->getId()], [], true));
        $form->bind($inscription);
        $form->get('etape')->setValue('REFUS');

        $break = false;
        if ($agent->getUtilisateur() !== $this->getUserService()->getConnectedUser()) {
            $this->flashMessenger()->addErrorMessage("L'utilisateur connecté ne correspond à l'agent en train de se déinscrire !");
            $break = true;
        }

        if (!$break) {
            $request = $this->getRequest();
            if ($request->isPost()) {
                $data = $request->getPost();
                $form->setData($data);
                if ($form->isValid()) {
                    $this->getEtatInstanceService()->setEtatActif($inscription, InscriptionEtats::ETAT_REFUSER);
                    $this->getInscriptionService()->historise($inscription);
                    $this->flashMessenger()->addSuccessMessage("Désinscription faite.");
                }
            }
        }

        $vm = new ViewModel([
            'title' => "Désinscription à la formation " . $instance->getInstanceLibelle() . " du " . $instance->getPeriode(),
            'inscription' => $inscription,
            'form' => $form,
        ]);
        $vm->setTemplate('formation/formation-instance-inscrit/inscription');
        return $vm;
    }

    public function envoyerConvocationAction(): ViewModel
    {
        $inscription = $this->getInscriptionService()->getRequestedInscription($this);
        $session = $inscription->getSession();

        $this->getSessionService()->envoyerConvocation($session, $inscription);

        $vm = new ViewModel([
            'title' => "Envoi de la convocation",
            'reponse' => "La convocation pour la session ".$session->getFormation()->getLibelle(). " du ".$session->getPeriode()." vient d'être envoyée à ".$inscription->getStagiaireDenomination().".",
        ]);
        $vm->setTemplate('default/reponse');
        return $vm;
    }

    public function envoyerAttestationAction(): ViewModel
    {
        $inscription = $this->getInscriptionService()->getRequestedInscription($this);
        $session = $inscription->getSession();

        $this->getSessionService()->envoyerAttestation($session, $inscription);

        $vm = new ViewModel([
            'title' => "Envoi de l'attestation",
            'reponse' => "L'attestion pour la session ".$session->getFormation()->getLibelle(). " du ".$session->getPeriode()." vient d'être envoyée à ".$inscription->getStagiaireDenomination().".",
        ]);
        $vm->setTemplate('default/reponse');
        return $vm;
    }

    public function envoyerAbsenceAction(): ViewModel
    {
        $inscription = $this->getInscriptionService()->getRequestedInscription($this);
        $session = $inscription->getSession();

        $this->getSessionService()->envoyerAbsence($session, $inscription);

        $vm = new ViewModel([
            'title' => "Envoi du constat d'absence",
            'reponse' => "Le constat d'absence pour la session ".$session->getFormation()->getLibelle(). " du ".$session->getPeriode()." vient d'être envoyée à ".$inscription->getStagiaireDenomination().".",
        ]);
        $vm->setTemplate('default/reponse');
        return $vm;
    }

    /** GESTION DE L'ENQUETE ******************************************************************************************/

    public function repondreEnqueteAction(): ViewModel
    {
        $inscription = $this->getInscriptionService()->getRequestedInscription($this);
        if ($inscription->getEnquete() === null) {
            $enquete = $this->getEnqueteService()->getEnqueteByCode($this->getParametreService()->getValeurForParametre(FormationParametres::TYPE, FormationParametres::CODE_ENQUETE));
            $instance = $this->getInstanceService()->createInstance($enquete);
            $inscription->setEnquete($instance);
            $this->getInscriptionService()->update($inscription);
        } else {
            $instance = $inscription->getEnquete();
        }

        $retour = $this->url()->fromRoute('formations', ['agent' => $inscription->getAgent()->getId()], [], true);
        return new ViewModel([
            'inscription' => $inscription,
            'instance' => $instance,
            'retour' => $retour,
            'connectedRole' => $this->getUserService()->getConnectedRole(),
            'connectedUser' => $this->getUserService()->getConnectedUser(),
        ]);

    }

    public function validerEnqueteAction(): ViewModel
    {
        $inscription = $this->getInscriptionService()->getRequestedInscription($this);
        $enquete = $inscription->getEnquete();
        if ($enquete !== null) {
            $enquete->setValidation(new DateTime());
            $this->getInstanceService()->update($enquete);
        }

        $vm = new ViewModel([
            'title' => "Validation de l'enquête de retour de l'atelier",
            'reponse' => "<span class='icon icon-checked'></span> Vous venez de valider l'enquête de retour d'atelier. Vous pouvez maintenant télécharger votre attestation.",
        ]);
        $vm->setTemplate('default/reponse');
        return $vm;
    }


}