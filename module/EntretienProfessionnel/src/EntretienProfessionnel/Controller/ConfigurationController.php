<?php

namespace EntretienProfessionnel\Controller;

use Application\Entity\Db\ConfigurationEntretienProfessionnel;
use Application\Service\Configuration\ConfigurationServiceAwareTrait;
use EntretienProfessionnel\Form\ConfigurationRecopie\ConfigurationRecopieFormAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class ConfigurationController extends AbstractActionController
{
    use ConfigurationServiceAwareTrait;
    use ConfigurationRecopieFormAwareTrait;

    public function ajouterRecopieAction() : ViewModel
    {
        $recopie = new ConfigurationEntretienProfessionnel();
        $form = $this->getConfigurationRecopieForm();
        $form->setAttribute('action', $this->url()->fromRoute('configuration/ajouter-recopie', [], [], true));
        $form->bind($recopie);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getConfigurationService()->create($recopie);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'une recopie de champ de l'entretien professionnel",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierRecopieAction() : ViewModel
    {
        $id = $this->params()->fromRoute('recopie');
        $recopie = $this->getConfigurationService()->getConfigurationEntretienProfessionnel($id);
        $form = $this->getConfigurationRecopieForm();
        $form->setAttribute('action', $this->url()->fromRoute('configuration/modifier-recopie', ['recopie' => $recopie->getId()], [], true));
        $form->bind($recopie);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getConfigurationService()->update($recopie);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'une recopie de champ de l'entretien professionnel",
            'form' => $form,
        ]);
        return $vm;
    }

    public function supprimerRecopieAction() : Response
    {
        $id = $this->params()->fromRoute('recopie');
        $recopie = $this->getConfigurationService()->getConfigurationEntretienProfessionnel($id);
        $this->getConfigurationService()->delete($recopie);
        return $this->redirect()->toRoute('configuration', [], ['fragment' => 'entretien-pro'],true);
    }
}