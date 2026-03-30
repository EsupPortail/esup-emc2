<?php

namespace EntretienProfessionnel\Controller;

use EntretienProfessionnel\Entity\Db\CampagneConfigurationRecopie;
use EntretienProfessionnel\Form\CampagneConfigurationRecopie\CampagneConfigurationRecopieFormAwareTrait;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use EntretienProfessionnel\Service\CampagneConfigurationRecopie\CampagneConfigurationRecopieServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\View\Model\ViewModel;

/** @method FlashMessenger flashMessenger() */
class CampagneConfigurationRecopieController extends AbstractActionController
{
    use CampagneServiceAwareTrait;
    use CampagneConfigurationRecopieServiceAwareTrait;
    use CampagneConfigurationRecopieFormAwareTrait;

    public function indexAction(): ViewModel
    {
        $campagneConfigurationRecopies = $this->getCampagneConfigurationRecopieService()->getCampagneConfigurationRecopies(true);
        $verificationTypes = $this->getCampagneConfigurationRecopieService()->verifierTypes();
        $vm =  new ViewModel([
            'campagneConfigurationRecopies' => $campagneConfigurationRecopies,
            'verificationTypes' => $verificationTypes,
        ]);
        $vm->setTemplate('entretien-professionnel/configuration/index-recopies');
        return $vm;
    }

    public function ajouterAction(): ViewModel
    {
        $recopie = new CampagneConfigurationRecopie();
        $form = $this->getCampagneConfigurationRecopieForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/campagne/configuration-recopie/ajouter', [], [], true));
        $form->bind($recopie);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCampagneConfigurationRecopieService()->create($recopie);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Ajout d'une recopie",
            'form' => $form,
            'recopie' => $recopie,
        ]);
        $vm->setTemplate('entretien-professionnel/configuration/recopie-form');
        return $vm;
    }

    public function modifierAction(): ViewModel
    {
        $recopie = $this->getCampagneConfigurationRecopieService()->getRequestedCampagneConfigurationRecopie($this);
        $form = $this->getCampagneConfigurationRecopieForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/campagne/configuration-recopie/modifier', ['campagne-configuration-recopie' => $recopie->getId()], [], true));
        $form->bind($recopie);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCampagneConfigurationRecopieService()->create($recopie);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Modifier la recopie",
            'form' => $form,
            'recopie' => $recopie,
        ]);
        $vm->setTemplate('entretien-professionnel/configuration/recopie-form');
        return $vm;
    }

    public function historiserAction(): Response
    {
        $recopie = $this->getCampagneConfigurationRecopieService()->getRequestedCampagneConfigurationRecopie($this);
        $this->getCampagneConfigurationRecopieService()->historise($recopie);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);

        return $this->redirect()->toRoute('entretien-professionnel/campagne/configuration-recopie', [], [], true);
    }

    public function restaurerAction(): Response
    {
        $recopie = $this->getCampagneConfigurationRecopieService()->getRequestedCampagneConfigurationRecopie($this);
        $this->getCampagneConfigurationRecopieService()->restore($recopie);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);

        return $this->redirect()->toRoute('entretien-professionnel/campagne/configuration-recopie', [], [], true);
    }

    public function supprimerAction() : ViewModel
    {
        $recopie = $this->getCampagneConfigurationRecopieService()->getRequestedCampagneConfigurationRecopie($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getCampagneConfigurationRecopieService()->delete($recopie);
            exit();
        }

        $vm = new ViewModel();
        if ($recopie !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la configuration/recopie ",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('entretien-professionnel/campagne/configuration-recopie/supprimer', ["campagne-configuration-recopie" => $recopie->getId()], [], true),
            ]);
        }
        return $vm;
    }

    public function verifierAction() : ViewModel
    {
        $request = $this->getRequest();

        if ($request->isPost()) {
            $success = null; $error = null; $warning = null;

            $data = $request->getPost();
            $campagneId = $data['campagne'];
            $campagne = $this->getCampagneService()->getCampagne($campagneId);

            if ($campagne !== null) {
                $error = $this->getCampagneConfigurationRecopieService()->verifierFormulaire($campagne);

                $log1 = $this->getCampagneConfigurationRecopieService()->verifierTypes();
                $log2 = $this->getCampagneConfigurationRecopieService()->verifierExistences($campagne);
                $warning = $log1 . (($log1)?"<br>":"") . $log2;

                if ($error === null AND $warning === null) {
                    $success = "Les copies sont bien paramétrées pour la campagne ".$campagne->getAnnee();
                }
            }

            $vm = new ViewModel([
                'title' => "Vérification des recopies",
                'reponse' => "Vérification effectuée",
                'success' => $success,
                'error' => $error,
                'warning' => $warning,
            ]);
            $vm->setTemplate('default/reponse');
            return $vm;
        }

        $campagnes = $this->getCampagneService()->getCampagnes();
        $vm = new ViewModel([
            'title' => "Vérification des recopies",
            'campagnes' => $campagnes,
            'url' => $this->url()->fromRoute('entretien-professionnel/campagne/configuration-recopie/verifier', [], [], true),
        ]);
        $vm->setTemplate('entretien-professionnel/configuration/selectionner-campagne');
        return $vm;

    }

}
