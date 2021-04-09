<?php

namespace EntretienProfessionnel\Controller;

use EntretienProfessionnel\Entity\Db\Campagne;
use EntretienProfessionnel\Form\Campagne\CampagneFormAwareTrait;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use Exception;
use Mailing\Service\Mailing\MailingServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenUtilisateur\Entity\DateTimeAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CampagneController extends AbstractActionController {
    use DateTimeAwareTrait;

    use CampagneServiceAwareTrait;
    use MailingServiceAwareTrait;
    use ParametreServiceAwareTrait;

    use CampagneFormAwareTrait;

    public function ajouterAction()
    {
        $campagne = new Campagne();
        $campagne->setAnnee($this->getAnneeScolaire());

        $form = $this->getCampagneForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/campagne/ajouter', [], [], true));
        $form->bind($campagne);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCampagneService()->create($campagne);
                try {
                    $mail_DAC = $this->parametreService->getParametreByCode('ENTRETIEN_PROFESSIONNEL','MAIL_LISTE_DAC')->getValeur();
                    $mail1 = $this->getMailingService()->sendMailType("CAMPAGNE_OUVERTURE_DAC", ['campagne' => $campagne, 'mailing' => $mail_DAC]);
                    $mail_BIATS = $this->parametreService->getParametreByCode('ENTRETIEN_PROFESSIONNEL','MAIL_LISTE_BIATS')->getValeur();
                    $mail2 = $this->getMailingService()->sendMailType("CAMPAGNE_OUVERTURE_BIATSS", ['campagne' => $campagne, 'mailing' => $mail_BIATS]);
                    $this->getMailingService()->addAttachement($mail1, Campagne::class, $campagne->getId());
                    $this->getMailingService()->addAttachement($mail2, Campagne::class, $campagne->getId());
                } catch(Exception $e) {
                    $vm = new ViewModel([
                        'title' => "Un problème est survenu lors de la création de la campagne d'entretien professionnel",
                        'text' => "Création effectué mais une exception a été levée lors de l'envoi des mails <br/><br/> <strong>" . $e->getMessage() ."</strong>",
                    ]);
                    $vm->setTemplate('entretien-professionnel/default/probleme');
                    return $vm;

                }
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'une campagne d'entretien professionnel",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction()
    {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);

        $form = $this->getCampagneForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/campagne/modifier', ['campagne' => $campagne->getId()], [], true));
        $form->bind($campagne);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCampagneService()->update($campagne);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'une campagne d'entretien professionnel",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction()
    {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);
        $this->getCampagneService()->historise($campagne);
        return $this->redirect()->toRoute('entretien-professionnel', [], ['fragment' => 'campagne'], true);
    }

    public function restaurerAction()
    {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);
        $this->getCampagneService()->restore($campagne);
        return $this->redirect()->toRoute('entretien-professionnel', [], ['fragment' => 'campagne'], true);
    }

    public function detruireAction()
    {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getCampagneService()->delete($campagne);
            exit();
        }

        $vm = new ViewModel();
        if ($campagne !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la campagne " . $campagne->getAnnee(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('entretien-professionnel/campagne/detruire', ["campagne" => $campagne->getId()], [], true),
            ]);
        }
        return $vm;
    }
}