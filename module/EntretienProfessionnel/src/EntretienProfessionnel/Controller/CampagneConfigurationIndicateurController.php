<?php

namespace EntretienProfessionnel\Controller;

use EntretienProfessionnel\Entity\Db\CampagneConfigurationIndicateur;
use EntretienProfessionnel\Form\CampagneConfigurationIndicateur\CampagneConfigurationIndicateurFormAwareTrait;
use EntretienProfessionnel\Service\CampagneConfigurationIndicateur\CampagneConfigurationIndicateurServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class CampagneConfigurationIndicateurController extends AbstractActionController
{
    use CampagneConfigurationIndicateurServiceAwareTrait;
    use CampagneConfigurationIndicateurFormAwareTrait;

    public function indexAction() : ViewModel
    {
        $campagneConfigurationIndicateurs = $this->getCampagneConfigurationIndicateurService()->getCampagneConfigurationIndicateurs(true);

        $vm =  new ViewModel([
            'campagneConfigurationIndicateurs' => $campagneConfigurationIndicateurs,
        ]);
        $vm->setTemplate('entretien-professionnel/configuration/index-indicateurs');
        return $vm;
    }

    public function ajouterAction() : ViewModel
    {
        $configuration = new CampagneConfigurationIndicateur();
        $form = $this->getCampagneConfigurationIndicateurForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/campagne/configuration-indicateur/ajouter', [], [], true));
        $form->bind($configuration);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCampagneConfigurationIndicateurService()->create($configuration);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Ajout d'un indicateur",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function modifierAction() : ViewModel
    {
        $configuration = $this->getCampagneConfigurationIndicateurService()->getRequestedCampagneConfigurationIndicateur($this);
        $form = $this->getCampagneConfigurationIndicateurForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/campagne/configuration-indicateur/modifier', ['campagne-configuration-indicateur' => $configuration->getId()], [], true));
        $form->bind($configuration);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCampagneConfigurationIndicateurService()->update($configuration);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Modification de l'indicateur [".$configuration->getCode()."]",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;

    }

    public function historiserAction() : Response
    {
        $configuration = $this->getCampagneConfigurationIndicateurService()->getRequestedCampagneConfigurationIndicateur($this);
        $this->getCampagneConfigurationIndicateurService()->historise($configuration);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);

        return $this->redirect()->toRoute('entretien-professionnel/campagne/configuration', [], ['fragment' => 'indicateurs'], true);
    }

    public function restaurerAction() : Response
    {
        $configuration = $this->getCampagneConfigurationIndicateurService()->getRequestedCampagneConfigurationIndicateur($this);
        $this->getCampagneConfigurationIndicateurService()->restore($configuration);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);

        return $this->redirect()->toRoute('entretien-professionnel/campagne/configuration', [], ['fragment' => 'indicateurs'], true);
    }

    public function supprimerAction() : ViewModel
    {
        $configuration = $this->getCampagneConfigurationIndicateurService()->getRequestedCampagneConfigurationIndicateur($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getCampagneConfigurationIndicateurService()->delete($configuration);
            exit();
        }

        $vm = new ViewModel();
        if ($configuration !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la configuration/indicateur " . $configuration->getCode(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('entretien-professionnel/campagne/configuration', ["campagne-configuration-indicateur" => $configuration->getId()], [], true),
            ]);
        }
        return $vm;
    }
}
