<?php

namespace EntretienProfessionnel\Controller;

use Application\Entity\Db\ConfigurationEntretienProfessionnel;
use Application\Service\Configuration\ConfigurationServiceAwareTrait;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use EntretienProfessionnel\Form\ConfigurationRecopie\ConfigurationRecopieFormAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use RuntimeException;
use UnicaenAutoform\Service\Formulaire\FormulaireServiceAwareTrait;

class ConfigurationController extends AbstractActionController
{
    use ConfigurationServiceAwareTrait;
    use ConfigurationRecopieFormAwareTrait;
    use FormulaireServiceAwareTrait;

    public function ajouterRecopieAction() : ViewModel
    {
        $code = $this->params()->fromRoute('formulaire');
        $formulaire = $this->getFormulaireService()->getFormulaireByCode($code);
        if ($formulaire === null) {
            throw new RuntimeException("Aucun formulaire de code [".$code."]");
        }

        $recopie = new ConfigurationEntretienProfessionnel();

        $form = $this->getConfigurationRecopieForm();
        $form->setFormulaire($formulaire);
        $form->init();
        $form->setAttribute('action', $this->url()->fromRoute('configuration/ajouter-recopie', [], [], true));
        $form->bind($recopie);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getConfigurationService()->create($recopie);
                $recopie->setValeur($code . "|". $recopie->getValeur());
                $this->getConfigurationService()->update($recopie);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'une recopie de champ de l'entretien professionnel",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierRecopieAction() : ViewModel
    {
        $code = $this->params()->fromRoute('formulaire');
        $formulaire = $this->getFormulaireService()->getFormulaireByCode($code);
        if ($formulaire === null) {
            throw new RuntimeException("Aucun formulaire de code [".$code."]");
        }

        $id = $this->params()->fromRoute('recopie');
        $recopie = $this->getConfigurationService()->getConfigurationEntretienProfessionnel($id);
        $form = $this->getConfigurationRecopieForm();
        $form->setFormulaire($formulaire);
        $form->init();

        $form->setAttribute('action', $this->url()->fromRoute('configuration/modifier-recopie', ['recopie' => $recopie->getId()], [], true));
        $form->bind($recopie);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getConfigurationService()->update($recopie);
                $recopie->setValeur($code . "|". $recopie->getValeur());
                $this->getConfigurationService()->update($recopie);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/default-form');
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