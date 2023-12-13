<?php

namespace Formation\Controller;

use Formation\Entity\Db\Formation;
use Formation\Entity\Db\FormationInstanceInscrit;
use Formation\Entity\Db\Inscription;
use Formation\Form\Inscription\InscriptionFormAwareTrait;
use Formation\Provider\Etat\InscriptionEtats;
use Formation\Provider\Source\Sources;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Formation\Service\Inscription\InscriptionServiceAwareTrait;
use Formation\Service\Notification\NotificationServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\View\Model\ViewModel;
use UnicaenEtat\Service\EtatInstance\EtatInstanceServiceAwareTrait;

/** @method FlashMessenger flashMessenger() */
class InscriptionController extends AbstractActionController
{

    use InscriptionServiceAwareTrait;
    use InscriptionFormAwareTrait;
    use EtatInstanceServiceAwareTrait;
    use FormationInstanceServiceAwareTrait;
    use NotificationServiceAwareTrait;

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
        $session = $this->getFormationInstanceService()->getRequestedFormationInstance($this, 'session');

        $inscription = new Inscription();
        if ($session) $inscription->setSession($session);
        $inscription->setSource(Sources::EMC2);

        $form = $this->getInscriptionForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/inscription/ajouter', ['session' => ($session)?$session->getId():null], [], true));
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
            'title' => "Modificaiton de l'inscription",
            'form' => $form,
        ]);
        $vm->setTemplate('formation/inscription/modifier');
        return $vm;
    }

    public function afficherAction(): ViewModel
    {
        $inscription = $this->getInscriptionService()->getRequestedInscription($this);

        return new ViewModel([
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
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $inscrit->getSession()->getId()], ['fragment' => 'inscriptions'], true);
    }

    public function restaurerAction(): Response
    {
        $inscrit = $this->getInscriptionService()->getRequestedInscription($this);
        $this->getInscriptionService()->restore($inscrit);
        $this->flashMessenger()->addSuccessMessage("L'inscription de <strong>" . $inscrit->getStagiaireDenomination() . "</strong> vient d'être restaurée.");

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $inscrit->getSession()->getId()], [], true);

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

    public function validerResponsableAction() : ViewModel
    {
        $inscription = $this->getInscriptionService()->getRequestedInscription($this);
        $instance = $inscription->getSession();

        $form = $this->getInscriptionForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/inscription/valider-responsable', ['inscription' => $inscription->getId()], [], true));
        $form->bind($inscription);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $justification = (isset($data['HasDescription']['description']) && trim($data['HasDescription']['description']) !== '')?trim($data['HasDescription']['description']):null;
                if ($justification === null) {
                    $this->flashMessenger()->addErrorMessage("<span class='text-danger'><strong> Échec de la validation </strong></span> <br/> Veuillez justifier votre validation !");
                } else {
                    $inscription->setJustificationResponsable($justification);
                    $this->getEtatInstanceService()->setEtatActif($inscription,InscriptionEtats::ETAT_VALIDER_RESPONSABLE);
                    $this->getInscriptionService()->update($inscription);
                    $this->flashMessenger()->addSuccessMessage("Validation effectuée.");
                    $this->getNotificationService()->triggerResponsableValidation($inscription);
                }
            }
        }

        $vm =  new ViewModel([
            'title' => "Validation de l'inscription de ". $inscription->getStagiaireDenomination() ." à la formation ".$instance->getInstanceLibelle(). " du ".$instance->getPeriode(),
            'inscription' => $inscription,
            'form' => $form,
        ]);
        $vm->setTemplate('formation/formation-instance-inscrit/inscription');
        return $vm;
    }

    public function refuserResponsableAction() : ViewModel
    {
        $inscription = $this->getInscriptionService()->getRequestedInscription($this);
        $instance = $inscription->getSession();

        $form = $this->getInscriptionForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/inscription/refuser-responsable', ['inscription' => $inscription->getId()], [], true));
        $form->bind($inscription);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $justification = (isset($data['HasDescription']['description']) && trim($data['HasDescription']['description']) !== '')?trim($data['HasDescription']['description']):null;
                if ($justification === null) {
                    $this->flashMessenger()->addErrorMessage("<span class='text-danger'><strong> Échec du refus  </strong></span> <br/> Veuillez justifier votre refus !");
                } else {
                    $inscription->setJustificationRefus($justification);
                    $this->getEtatInstanceService()->setEtatActif($inscription,InscriptionEtats::ETAT_VALIDER_RESPONSABLE);
                    $this->getInscriptionService()->historise($inscription);
                    $this->flashMessenger()->addSuccessMessage("Refus effectué.");
                    $this->getNotificationService()->triggerResponsableRefus($inscription);
                    //todo trigger reclassement
                }
            }
        }

        $vm =  new ViewModel([
            'title' => "refus de l'inscription de ". $inscription->getStagiaireDenomination() ." à la formation ".$instance->getInstanceLibelle(). " du ".$instance->getPeriode(),
            'inscription' => $inscription,
            'form' => $form,
        ]);
        $vm->setTemplate('formation/formation-instance-inscrit/inscription');
        return $vm;
    }

    public function validerDrhAction() : ViewModel
    {
        $inscription = $this->getInscriptionService()->getRequestedInscription($this);
        $session = $inscription->getSession();

        $form = $this->getInscriptionForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/inscription/valider-drh', ['inscription' => $inscription->getId()], [], true));
        $form->bind($inscription);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getEtatInstanceService()->setEtatActif($inscription,InscriptionEtats::ETAT_VALIDER_DRH);
                $this->getInscriptionService()->update($inscription);
                $this->getFormationInstanceService()->classerInscription($inscription);
                $this->flashMessenger()->addSuccessMessage("Validation effectuée.");
                $this->getNotificationService()->triggerDrhValidation($inscription);
                if ($inscription->getListe() === FormationInstanceInscrit::PRINCIPALE
                    AND $inscription->getSession()->getFormation()->getRattachement() === Formation::RATTACHEMENT_PREVENTION) {
                    $this->getNotificationService()->triggerPrevention($inscription);
                }
            }
        }

        $vm =  new ViewModel([
            'title' => "Validation de l'inscription de ". $inscription->getStagiaireDenomination() ." à la formation ".$session->getInstanceLibelle(). " du ".$session->getPeriode(),
            'inscription' => $inscription,
            'form' => $form,
        ]);
        $vm->setTemplate('formation/formation-instance-inscrit/inscription');
        return $vm;
    }

    public function refuserDrhAction() : ViewModel
    {
        $inscription = $this->getInscriptionService()->getRequestedInscription($this);
        $instance = $inscription->getSession();

        $form = $this->getInscriptionForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/inscription/refuser-drh', ['inscription' => $inscription->getId()], [], true));
        $form->bind($inscription);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $justification = (isset($data['HasDescription']['description']) && trim($data['HasDescription']['description']) !== '')?trim($data['HasDescription']['description']):null;
                if ($justification === null) {
                    $this->flashMessenger()->addErrorMessage("<strong> Échec du refus </strong> <br/> Veuillez justifier votre refus !");
                } else {
                    $inscription->setJustificationRefus($justification);
                    $this->getEtatInstanceService()->setEtatActif($inscription,InscriptionEtats::ETAT_REFUSER);
                    $this->getInscriptionService()->historise($inscription);
                    $this->flashMessenger()->addSuccessMessage("Refus effectué.");
                    $this->getNotificationService()->triggerDrhRefus($inscription);
                }
            }
        }

        $vm =  new ViewModel([
            'title' => "refus de l'inscription de ". $inscription->getStagiaireDenomination() ." à la formation ".$instance->getInstanceLibelle(). " du ".$instance->getPeriode(),
            'inscription' => $inscription,
            'form' => $form,
        ]);
        $vm->setTemplate('formation/formation-instance-inscrit/inscription');
        return $vm;
    }

    /** Gestion des listes ********************************************************************************************/

    public function envoyerListePrincipaleAction() : Response
    {
        $inscription = $this->getInscriptionService()->getRequestedInscription($this);

        $inscription->setListe(FormationInstanceInscrit::PRINCIPALE);
        $this->getInscriptionService()->update($inscription);

        $this->getNotificationService()->triggerListePrincipale($inscription);

        $this->flashMessenger()->addSuccessMessage("<strong>" . $inscription->getStagiaireDenomination() . "</strong> vient d'être ajouté&middot;e en liste principale.");

        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $inscription->getSession()->getId()], [], true);
    }

    public function envoyerListeComplementaireAction() : Response
    {
        $inscription = $this->getInscriptionService()->getRequestedInscription($this);

        $inscription->setListe(FormationInstanceInscrit::COMPLEMENTAIRE);
        $this->getInscriptionService()->update($inscription);

        $this->getNotificationService()->triggerListeComplementaire($inscription);


        $this->flashMessenger()->addSuccessMessage("<strong>" .  $inscription->getStagiaireDenomination() . "</strong> vient d'être ajouté&middot;e en liste complémentaire.");

        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $inscription->getSession()->getId()], [], true);
    }

    public function retirerListeAction() : Response
    {
        $inscription = $this->getInscriptionService()->getRequestedInscription($this);

        $inscription->setListe(null);
        $this->getInscriptionService()->update($inscription);

        $this->getNotificationService()->triggerRetraitListe($inscription);


        $this->flashMessenger()->addSuccessMessage("<strong>" .  $inscription->getStagiaireDenomination() . "</strong> vient d'être retiré·e des listes.");

        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $inscription->getSession()->getId()], [], true);
    }

    public function classerAction() : Response
    {
        $inscription = $this->getInscriptionService()->getRequestedInscription($this);

        $this->getFormationInstanceService()->classerInscription($inscription);

        switch ($inscription->getListe()) {
            case FormationInstanceInscrit::PRINCIPALE :
                $this->flashMessenger()->addSuccessMessage("Classement de l'inscription en liste principale.");
                break;
            case FormationInstanceInscrit::COMPLEMENTAIRE :
                $this->flashMessenger()->addWarningMessage("Liste principale complète. <br/> Classement de l'inscription en liste complémentaire.");
                break;
            default :
                $this->flashMessenger()->addErrorMessage("Plus de place en liste principale et complémentaire. <br/> Échec du classement de l'inscription.");
                break;
        }

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);

        $session = $inscription->getSession();
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $session->getId()], ['fragment' => 'inscriptions'], true);
    }

}