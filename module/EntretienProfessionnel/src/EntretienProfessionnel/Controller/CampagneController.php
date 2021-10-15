<?php

namespace EntretienProfessionnel\Controller;

use EntretienProfessionnel\Entity\Db\Campagne;
use EntretienProfessionnel\Form\Campagne\CampagneFormAwareTrait;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use Exception;
use UnicaenMail\Service\Mail\MailServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenRenderer\Service\Rendu\RenduServiceAwareTrait;
use UnicaenUtilisateur\Entity\DateTimeAwareTrait;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CampagneController extends AbstractActionController {
    use DateTimeAwareTrait;

    use CampagneServiceAwareTrait;
    use RenduServiceAwareTrait;
    use MailServiceAwareTrait;
    use ParametreServiceAwareTrait;

    use CampagneFormAwareTrait;

    public function ajouterAction() : ViewModel
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

                    $vars = ['campagne' => $campagne];

                    $mail_DAC = $this->parametreService->getParametreByCode('GLOBAL','MAIL_LISTE_DAC')->getValeur();
                    $rendu = $this->getRenduService()->genereateRenduByTemplateCode('CAMPAGNE_OUVERTURE_DAC', $vars);
                    $mailDac = $this->getMailService()->sendMail($mail_DAC, $rendu->getSujet(), $rendu->getCorps());
                    $mailDac->setMotsClefs([$campagne->generateTag(), $rendu->getTemplate()->generateTag()]);
                    $this->getMailService()->update($mailDac);

                    $mail_BIATS = $this->parametreService->getParametreByCode('GLOBAL','MAIL_LISTE_BIATS')->getValeur();
                    $rendu = $this->getRenduService()->genereateRenduByTemplateCode('CAMPAGNE_OUVERTURE_BIATSS', $vars);
                    $mailBiats = $this->getMailService()->sendMail($mail_BIATS, $rendu->getSujet(), $rendu->getCorps());
                    $mailBiats->setMotsClefs([$campagne->generateTag(), $rendu->getTemplate()->generateTag()]);
                    $this->getMailService()->update($mailBiats);

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

    public function modifierAction() : ViewModel
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

    public function historiserAction() : Response
    {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);
        $this->getCampagneService()->historise($campagne);
        return $this->redirect()->toRoute('entretien-professionnel', [], ['fragment' => 'campagne'], true);
    }

    public function restaurerAction() : Response
    {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);
        $this->getCampagneService()->restore($campagne);
        return $this->redirect()->toRoute('entretien-professionnel', [], ['fragment' => 'campagne'], true);
    }

    public function detruireAction() : ViewModel
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