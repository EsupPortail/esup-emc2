<?php

namespace EntretienProfessionnel\Controller;

use EntretienProfessionnel\Entity\Db\CampagneConfigurationPresaisie;
use EntretienProfessionnel\Form\CampagneConfigurationPresaisie\CampagneConfigurationPresaisieFormAwareTrait;
use EntretienProfessionnel\Service\CampagneConfigurationPresaisie\CampagneConfigurationPresaisieServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class CampagneConfigurationPresaisieController extends AbstractActionController
{
    use CampagneConfigurationPresaisieServiceAwareTrait;
    use CampagneConfigurationPresaisieFormAwareTrait;

    public function indexAction(): ViewModel
    {
        $campagneConfigurationPresaisies = $this->getCampagneConfigurationPresaisieService()->getCampagneConfigurationPresaisies(true);

        $vm =  new ViewModel([
            'campagneConfigurationPresaisies' => $campagneConfigurationPresaisies,
        ]);
        $vm->setTemplate('entretien-professionnel/configuration/index-presaisies');
        return $vm;
    }

    public function ajouterAction(): ViewModel
    {
        $presaisie = new CampagneConfigurationPresaisie();
        $form = $this->getCampagneConfigurationPresaisieForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/campagne/configuration-presaisie/ajouter', [], [], true));
        $form->bind($presaisie);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCampagneConfigurationPresaisieService()->create($presaisie);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Ajout d'une recopie",
            'form' => $form,
            'presaisie' => $presaisie,
        ]);
//        $vm->setTemplate('entretien-professionnel/configuration/presaisie-form');
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function modifierAction(): ViewModel
    {
        $presaisie = $this->getCampagneConfigurationPresaisieService()->getRequestedCampagneConfigurationPresaisie($this);
        $form = $this->getCampagneConfigurationPresaisieForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/campagne/configuration-presaisie/modifier', ['campagne-configuration-presaisie' => $presaisie->getId()], [], true));
        $form->bind($presaisie);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCampagneConfigurationPresaisieService()->create($presaisie);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Modifier la recopie",
            'form' => $form,
            'presaisie' => $presaisie,
        ]);
//        $vm->setTemplate('entretien-professionnel/configuration/recopie-form');
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function historiserAction(): Response
    {
        $presaisie = $this->getCampagneConfigurationPresaisieService()->getRequestedCampagneConfigurationPresaisie($this);
        $this->getCampagneConfigurationPresaisieService()->historise($presaisie);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);

        return $this->redirect()->toRoute('entretien-professionnel/campagne/configuration-presaisie', [], [], true);
    }

    public function restaurerAction(): Response
    {
        $presaisie = $this->getCampagneConfigurationPresaisieService()->getRequestedCampagneConfigurationPresaisie($this);
        $this->getCampagneConfigurationPresaisieService()->restore($presaisie);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);

        return $this->redirect()->toRoute('entretien-professionnel/campagne/configuration-presaisie', [], [], true);
    }

    public function supprimerAction() : ViewModel
    {
        $presaisie = $this->getCampagneConfigurationPresaisieService()->getRequestedCampagneConfigurationPresaisie($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getCampagneConfigurationPresaisieService()->delete($presaisie);
            exit();
        }

        $vm = new ViewModel();
        if ($presaisie !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la configuration/pré-saisie ",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('entretien-professionnel/campagne/configuration-presaisie/supprimer', ["campagne-configuration-saisie" => $presaisie->getId()], [], true),
            ]);
        }
        return $vm;
    }
}
