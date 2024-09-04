<?php

namespace Formation\Controller;

use DateInterval;
use DateTime;
use Exception;
use Formation\Entity\Db\Seance;
use Formation\Entity\Db\Session;
use Formation\Event\InscriptionCloture\InscriptionClotureEventAwareTrait;
use Formation\Form\Seance\SeanceFormAwareTrait;
use Formation\Provider\Event\EvenementProvider;
use Formation\Provider\Parametre\FormationParametres;
use Formation\Service\Seance\SeanceServiceAwareTrait;
use Formation\Service\Session\SessionServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use RuntimeException;
use UnicaenEvenement\Entity\Db\Evenement;
use UnicaenEvenement\Service\Evenement\EvenementServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;

//TODO refactorer la reprogrammation si cela fonctionne comme cela !!!

class SeanceController extends AbstractActionController
{
    use ParametreServiceAwareTrait;
    use SeanceServiceAwareTrait;
    use SessionServiceAwareTrait;

    use EvenementServiceAwareTrait;
    use InscriptionClotureEventAwareTrait;

    use SeanceFormAwareTrait;

    public function ajouterJourneeAction(): ViewModel
    {
        $session = $this->getSessionService()->getRequestedSession($this);

        $journee = new Seance();
        $journee->setInstance($session);

        $form = $this->getSeanceForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/session/ajouter-journee', ['session' => $session->getId()], [], true));
        $form->bind($journee);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $oldDebut = $session->getDebut(true);
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                /** si le debut a changer il faut reprogrammer l'evenement de cloture automatique **/
                $this->getSeanceService()->create($journee);
                $newDebut = $session->getDebut(true);
                if ($oldDebut === null OR $newDebut < $oldDebut) {
                   $this->ajouterRappelSessionImminenteEtudiant($session);
                }
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('formation/seance/modifier');
        $vm->setVariables([
            'title' => "Ajout d'une journée de formation",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierJourneeAction(): ViewModel
    {
        $journee = $this->getSeanceService()->getRequestedSeance($this);

        $form = $this->getSeanceForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/session/modifier-journee', ['journee' => $journee->getId()], [], true));
        $form->bind($journee);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                /** si le debut a changer il faut reprogrammer l'evenement de cloture automatique **/
                $session = $journee->getInstance();
                $oldDebut = $session->getDebut(true);
                $this->getSeanceService()->update($journee);
                $newDebut = $session->getDebut(true);
                if ($oldDebut === null OR $newDebut < $oldDebut) {
                    $this->ajouterRappelSessionImminenteEtudiant($session);
                }
                $this->getSeanceService()->update($journee);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('formation/seance/modifier');
        $vm->setVariables([
            'title' => "Modification d'une journée de formation",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserJourneeAction(): Response
    {
        $journee = $this->getSeanceService()->getRequestedSeance($this);
        $this->getSeanceService()->historise($journee);

        /** si le debut a changer il faut reprogrammer l'evenement de cloture automatique **/
        $session = $journee->getInstance();
        $oldDebut = $session->getDebut(true);
        $this->getSeanceService()->historise($journee);
        $newDebut = $session->getDebut(true);
        if ($oldDebut === null OR $newDebut < $oldDebut) {
            $this->ajouterRappelSessionImminenteEtudiant($session);
        }
        $this->getSeanceService()->update($journee);
        return $this->redirect()->toRoute('formation/session/afficher', ['session' => $journee->getInstance()->getId()], [], true);
    }

    public function restaurerJourneeAction(): Response
    {
        $journee = $this->getSeanceService()->getRequestedSeance($this);
        $this->getSeanceService()->restore($journee);

        /** si le debut a changer il faut reprogrammer l'evenement de cloture automatique **/
        $session = $journee->getInstance();
        $this->getSeanceService()->restore($journee);
        if ($session->getDebut() === null OR $journee->getDateDebut()->format('d/m/Y H:i') === $session->getDebut()) {
            $this->ajouterRappelSessionImminenteEtudiant($session);
        }
        $this->getSeanceService()->update($journee);

        return $this->redirect()->toRoute('formation/session/afficher', ['session' => $journee->getInstance()->getId()], [], true);
    }

    public function supprimerJourneeAction()
    {
        $journee = $this->getSeanceService()->getRequestedSeance($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") {

                /** si le debut a changer il faut reprogrammer l'evenement de cloture automatique **/
                $session = $journee->getInstance();
                $oldDebut = $session->getDebut(true);
                $this->getSeanceService()->delete($journee);
                $newDebut = $session->getDebut(true);
                if ($oldDebut === null OR $newDebut < $oldDebut) {
                    $this->ajouterRappelSessionImminenteEtudiant($session);
                }
                $this->getSeanceService()->update($journee);
            }
            exit();
        }

        $vm = new ViewModel();
        if ($journee !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la journée de formation du [" . $journee->getDateDebut()->format('d/m/Y') . "]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('formation/session/supprimer-journee', ["journee" => $journee->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** Gestion des evenements ****************************************************************************************/
    public function ajouterRappelSessionImminenteEtudiant(Session $session): void
    {
        $evenements = $session->getEvenements()->toArray();
        $evenements = array_filter($evenements, function (Evenement $e) { return $e->getType()->getCode() === EvenementProvider::INSCRIPTION_CLOTURE;});
        foreach ($evenements as $evenement) {
            $session->removeEvenement($evenement);
            $this->getSessionService()->update($session);
            $this->getEvenementService()->delete($evenement);
        }

        //todo calcul valeur par defaut
        $dateTraitement = $session->getDateClotureInscription();
        if ($dateTraitement === null) {
            $dateDebut = $session->getDebut(true);
            try {
                $interval = new DateInterval('P' . $this->getParametreService()->getValeurForParametre(FormationParametres::TYPE, FormationParametres::AUTO_CLOTURE) . 'D');
            } catch (Exception $e) {
                throw new RuntimeException("Un problème est survenu lors du calcul de l'interval", 0 ,$e);
            }
            $dateTraitement = $dateDebut->sub($interval);
        }
        if (!$dateTraitement instanceof DateTime) {
            throw new RuntimeException("La date de traitement de l'evenement [".EvenementProvider::INSCRIPTION_CLOTURE."] n'a pas pu être déterminé.");
        }
        $event = $this->getInscriptionClotureEvent()->creer($session, $dateTraitement);
        $session->addEvenement($event);
        $this->getSessionService()->update($session);
    }
}