<?php

namespace FichePoste\Controller;

use Application\Entity\Db\AgentSuperieur;
use DateTime;
use FichePoste\Provider\Etat\FichePosteEtats;
use FichePoste\Provider\Validation\FichePosteValidations;
use FichePoste\Service\FichePoste\FichePosteServiceAwareTrait;
use FichePoste\Service\Notification\NotificationServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use UnicaenEtat\Service\EtatInstance\EtatInstanceServiceAwareTrait;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceServiceAwareTrait;

class ValidationController extends AbstractActionController
{
    use EtatInstanceServiceAwareTrait;
    use FichePosteServiceAwareTrait;
    use NotificationServiceAwareTrait;
    use ValidationInstanceServiceAwareTrait;

    public function validerResponsableAction(): ViewModel
    {

        $fiche = $this->getFichePosteService()->getRequestedFichePoste($this);
        $agent = $fiche->getAgent();

        $titre = "Validation de la fiche de poste #" . $fiche->getId() . " associée à l'agent·e ".$agent->getDenomination(true);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();

            $etat = $fiche->getEtatActif(); $mail = null;

            if ($data["reponse"] === "oui") {
                $validation = $this->getValidationInstanceService()->setValidationActive($fiche, FichePosteValidations::VALIDATION_RESPONSABLE);
                $etat = $this->getEtatInstanceService()->setEtatActif($fiche, FichePosteEtats::ETAT_CODE_SIGNEE_RESPONSABLE);
                $mail = $this->getNotificationService()->triggerValidationResponsableFichePoste($fiche, $validation);
            }
            if ($data["reponse"] === "non") {
                $validation = $this->getValidationInstanceService()->setValidationActive($fiche, FichePosteValidations::VALIDATION_RESPONSABLE, true);
                $etat = $this->getEtatInstanceService()->setEtatActif($fiche, FichePosteEtats::ETAT_CODE_KO);
                $mail = $this->getNotificationService()->triggerRefusFichePoste($fiche, $validation);
            }

            $this->getFichePosteService()->update($fiche);
            $vm = new ViewModel([
                'title' => $titre,
                'reponse' => "La fiche de poste est maintenant à l'état " . $etat->getType()->getCode(),
                'error' => ($mail === null) ? "Échec de l'envoi de la notification" : null,
            ]);
            $vm->setTemplate('default/reponse');
        }

        $texte = "En validant la fiche de poste les actions suivantes se produiront : <ol>";
        $texte .= "<li> La fiche de poste quittera le mode édition et ne pourra plus être modifiée. </li>";
        $texte .= "<li> L'agent·e ".$agent->getDenomination(true)." sera notifié·e et pourra à son tour valider la fiche de poste</li>";
        $texte .= "</ol>";
        $vm = new ViewModel([
            'title' => $titre,
            'text' => $texte,
            'action' => $this->url()->fromRoute('fiche-poste/validation/valider-responsable', ["fiche-poste" => $fiche->getId()], [], true),
        ]);
        $vm->setTemplate('default/confirmation');
        return $vm;
    }

    public function revoquerResponsableAction(): ViewModel
    {
        $fiche = $this->getFichePosteService()->getRequestedFichePoste($this);
        $agent = $fiche->getAgent();

        $validation = $fiche->getValidationActiveByTypeCode(FichePosteValidations::VALIDATION_RESPONSABLE);
        if ($validation !== null) $this->getValidationInstanceService()->historise($validation);

        $etat = $this->getEtatInstanceService()->setEtatActif($fiche, FichePosteEtats::ETAT_CODE_REDACTION);

        $vm = new ViewModel([
            'title' => "Révocation de la validation responsable",
            'reponse' => "La fiche de poste #".$fiche->getId()." de l'agent·e ".$agent->getDenomination(true)." peut être de nouveau modifier."
        ]);
        $vm->setTemplate('default/reponse');
        return $vm;
    }

    public function validerAgentAction(): ViewModel
    {

        $fiche = $this->getFichePosteService()->getRequestedFichePoste($this);
        $agent = $fiche->getAgent();

        $titre = "Validation de la fiche de poste #" . $fiche->getId() . " associée à l'agent·e ".$agent->getDenomination(true);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();

            $etat = $fiche->getEtatActif(); $mail = null;

            if ($data["reponse"] === "oui") {
                $validation = $this->getValidationInstanceService()->setValidationActive($fiche, FichePosteValidations::VALIDATION_AGENT);
                $etat = $this->getEtatInstanceService()->setEtatActif($fiche, FichePosteEtats::ETAT_CODE_SIGNEE_AGENT);
                $mail = $this->getNotificationService()->triggerValidationAgentFichePoste($fiche, $validation);
            }
            if ($data["reponse"] === "non") {
                $validation = $this->getValidationInstanceService()->setValidationActive($fiche, FichePosteValidations::VALIDATION_AGENT, true);
                $etat = $this->getEtatInstanceService()->setEtatActif($fiche, FichePosteEtats::ETAT_CODE_KO);
                $mail = $this->getNotificationService()->triggerRefusFichePoste($fiche, $validation);
            }

            $this->getFichePosteService()->update($fiche);
            $vm = new ViewModel([
                'title' => $titre,
                'reponse' => "La fiche de poste est maintenant à l'état " . $etat->getType()->getCode(),
                'error' => ($mail === null) ? "Échec de l'envoi de la notification" : null,
            ]);
            $vm->setTemplate('default/reponse');
        }

        $texte = "En validant la fiche de poste les actions suivantes se produiront : <ol>";
        $texte .= "<li> La Direction des Ressources Humaines sera notifié·e et pourra à son tour valider la fiche de poste</li>";
        $texte .= "</ol>";
        $vm = new ViewModel([
            'title' => $titre,
            'text' => $texte,
            'action' => $this->url()->fromRoute('fiche-poste/validation/valider-agent', ["fiche-poste" => $fiche->getId()], [], true),
        ]);
        $vm->setTemplate('default/confirmation');
        return $vm;
    }

    public function revoquerAgentAction(): ViewModel
    {
        $fiche = $this->getFichePosteService()->getRequestedFichePoste($this);
        $agent = $fiche->getAgent();

        $validation = $fiche->getValidationActiveByTypeCode(FichePosteValidations::VALIDATION_AGENT);
        if ($validation !== null) $this->getValidationInstanceService()->historise($validation);

        $etat = $this->getEtatInstanceService()->setEtatActif($fiche, FichePosteEtats::ETAT_CODE_SIGNEE_RESPONSABLE);

        $vm = new ViewModel([
            'title' => "Révocation de la validation agent",
            'reponse' => "La fiche de poste #".$fiche->getId()." de l'agent·e ".$agent->getDenomination(true)." est maintenant en attente de la validation de l'agent·e."
        ]);
        $vm->setTemplate('default/reponse');
        return $vm;
    }

    public function validerDrhAction(): ViewModel
    {

        $fiche = $this->getFichePosteService()->getRequestedFichePoste($this);
        $agent = $fiche->getAgent();

        $titre = "Validation de la fiche de poste #" . $fiche->getId() . " associée à l'agent·e ".$agent->getDenomination(true);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();

            $etat = $fiche->getEtatActif(); $mail = null;

            if ($data["reponse"] === "oui") {
                $validation = $this->getValidationInstanceService()->setValidationActive($fiche, FichePosteValidations::VALIDATION_DRH);

                $oldFiche = $this->getFichePosteService()->getFichePosteByAgent($agent);
                if ($oldFiche !== null) {
                    $oldFiche->setFinValidite(new DateTime());
                    $this->getFichePosteService()->update($oldFiche);
                }

                $etat = $this->getEtatInstanceService()->setEtatActif($fiche, FichePosteEtats::ETAT_CODE_OK);
                $mail = $this->getNotificationService()->triggerValidationDrhFichePoste($fiche, $validation);

                $newFiche = $this->getFichePosteService()->clonerFichePoste($fiche, true);
                $this->getEtatInstanceService()->setEtatActif($newFiche, FichePosteEtats::ETAT_CODE_REDACTION);
            }
            if ($data["reponse"] === "non") {
                $validation = $this->getValidationInstanceService()->setValidationActive($fiche, FichePosteValidations::VALIDATION_DRH, true);
                $etat = $this->getEtatInstanceService()->setEtatActif($fiche, FichePosteEtats::ETAT_CODE_KO);
                $mail = $this->getNotificationService()->triggerRefusFichePoste($fiche, $validation);
            }

            $this->getFichePosteService()->update($fiche);
            $vm = new ViewModel([
                'title' => $titre,
                'reponse' => "La fiche de poste est maintenant à l'état " . $etat->getType()->getCode(),
                'error' => ($mail === null) ? "Échec de l'envoi de la notification" : null,
            ]);
            $vm->setTemplate('default/reponse');
        }

        $texte = "En validant la fiche de poste les actions suivantes se produiront : <ol>";
        $texte .= "<li> La fiche passera comme la nouvelle fiche active </li>";
        $texte .= "<li> La précédente fiche active recevra une date de fin de validité  </li>";
        $texte .= "<li> Une duplicata de cette fiche sera créer et mis à l'état 'En rédaction' </li>";
        $texte .= "</ol>";
        $vm = new ViewModel([
            'title' => $titre,
            'text' => $texte,
            'action' => $this->url()->fromRoute('fiche-poste/validation/valider-drh', ["fiche-poste" => $fiche->getId()], [], true),
        ]);
        $vm->setTemplate('default/confirmation');
        return $vm;
    }

    //todo il faut le lien vers la précendente sinon on ne pourra la reactiver
    //todo il faut le lien vers la nouvelle car sinon on ne pourra pas retirer le duplicata
    public function revoquerDrhAction(): ViewModel
    {
        $fiche = $this->getFichePosteService()->getRequestedFichePoste($this);
        $agent = $fiche->getAgent();

        $validation = $fiche->getValidationActiveByTypeCode(FichePosteValidations::VALIDATION_AGENT);
        if ($validation !== null) $this->getValidationInstanceService()->historise($validation);

        $etat = $this->getEtatInstanceService()->setEtatActif($fiche, FichePosteEtats::ETAT_CODE_SIGNEE_RESPONSABLE);

        $vm = new ViewModel([
            'title' => "Révocation de la validation agent",
            'reponse' => "La fiche de poste #".$fiche->getId()." de l'agent·e ".$agent->getDenomination(true)." est maintenant en attente de la validation de l'agent·e."
        ]);
        $vm->setTemplate('default/reponse');
        return $vm;
    }


}