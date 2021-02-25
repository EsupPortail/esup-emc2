<?php

namespace UnicaenGlossaire\Controller;

use UnicaenGlossaire\Entity\Db\Definition;
use UnicaenGlossaire\Form\Definition\DefinitionFormAwareTrait;
use UnicaenGlossaire\Service\Definition\DefinitionServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class DefinitionController extends AbstractActionController {
    use DefinitionServiceAwareTrait;
    use DefinitionFormAwareTrait;

    public function indexAction()
    {
        $definitions = $this->getDefinitionService()->getDefinitions();

        return new ViewModel([
            'definitions' => $definitions,
        ]);
    }

    public function afficherAction()
    {
        $definition = $this->getDefinitionService()->getRequestedDefinition($this);

        return new ViewModel([
            'title' => "Affichage de la définition du terme [".$definition->getTerme()."]",
            'definition' => $definition,
        ]);
    }

    public function ajouterAction()
    {
        $definition = new Definition();
        $form = $this->getDefinitionForm();
        $form->setAttribute('action', $this->url()->fromRoute('definition/ajouter', [], [], true));
        $form->bind($definition);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getDefinitionService()->create($definition);
            }
        }

        $vm = new ViewModel([
            'title' => "Ajouter une définition",
            'form' => $form,
        ]);
        $vm->setTemplate('unicaen-glossaire/default/default-form');
        return $vm;
    }

    public function modifierAction()
    {
        $definition = $this->getDefinitionService()->getRequestedDefinition($this);
        $form = $this->getDefinitionForm();
        $form->setAttribute('action', $this->url()->fromRoute('definition/modifier', ['definition' => $definition->getId()], [], true));
        $form->bind($definition);
        $form->setOldTerme($definition->getTerme());

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getDefinitionService()->update($definition);
            }
        }

        $vm = new ViewModel([
            'title' => "Modification de la définition de [".$definition->getTerme()."]",
            'form' => $form,
        ]);
        $vm->setTemplate('unicaen-glossaire/default/default-form');
        return $vm;
    }

    public function historiserAction()
    {
        $definition = $this->getDefinitionService()->getRequestedDefinition($this);
        $retour = $this->params()->fromQuery('retour');
        $this->getDefinitionService()->historise($definition);

        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('definition', [], [], true);
    }

    public function restaurerAction()
    {
        $definition = $this->getDefinitionService()->getRequestedDefinition($this);
        $retour = $this->params()->fromQuery('retour');
        $this->getDefinitionService()->restore($definition);

        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('definition', [], [], true);
    }

    public function supprimerAction()
    {
        $definition = $this->getDefinitionService()->getRequestedDefinition($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getDefinitionService()->delete($definition);
            exit();
        }

        $vm = new ViewModel();
        if ($definition !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la définition " . $definition->getTerme(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('definition/supprimer', ["definition" => $definition->getId()], [], true),
            ]);
        }
        return $vm;
    }
}