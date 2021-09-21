<?php

namespace UnicaenDocument\Controller;

use Application\Service\Agent\AgentServiceAwareTrait;
use UnicaenDocument\Entity\Db\Macro;
use UnicaenDocument\Form\Macro\MacroFormAwareTrait;
use UnicaenDocument\Service\Macro\MacroServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class MacroController extends AbstractActionController {
    use MacroServiceAwareTrait;
    use MacroFormAwareTrait;

    public function indexAction() {
        $macrosAll = $this->getMacroService()->getMacros();
        $variable = $this->params()->fromQuery('variable');

        $macros = [];
        $variables = [];
        foreach ($macrosAll as $macro) {
            $variables[$macro->getVariable()] = $macro->getVariable();
            if ($variable === null OR $variable ==='' OR $macro->getVariable() === $variable) {
                $macros[] = $macro;
            }
        }

        return new ViewModel([
            'macros' => $macros,
            'variables' => $variables,
            'variable' => $variable,
        ]);
    }

    public function ajouterAction() {
        $macro = new Macro();
        $form = $this->getMacroForm();
        $form->setAttribute('action', $this->url()->fromRoute('contenu/macro/ajouter', [], [], true));
        $form->bind($macro);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMacroService()->create($macro);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('unicaen-document/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'une nouvelle macro",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction() {
        $macro = $this->getMacroService()->getRequestedMacro($this);
        $form = $this->getMacroForm();
        $form->setAttribute('action', $this->url()->fromRoute('contenu/macro/modifier', ['macro' => $macro->getId()], [], true));
        $form->bind($macro);
        $form->setOldCode($macro->getCode());

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMacroService()->update($macro);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('unicaen-document/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'une macro",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction()
    {
        $macro = $this->getMacroService()->getRequestedMacro($this);
        $this->getMacroService()->historise($macro);
        return $this->redirect()->toRoute('contenu/macro', [], [], true);
    }

    public function restaurerAction()
    {
        $macro = $this->getMacroService()->getRequestedMacro($this);
        $this->getMacroService()->restore($macro);
        return $this->redirect()->toRoute('contenu/macro', [], [], true);
    }

    public function supprimerAction()
    {
        $macro = $this->getMacroService()->getRequestedMacro($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getMacroService()->delete($macro);
            exit();
        }

        $vm = new ViewModel();
        if ($macro !== null) {
            $vm->setTemplate('unicaen-document/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la macro " . $macro->getCode(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('contenu/macro/supprimer', ["macro" => $macro->getId()], [], true),
            ]);
        }
        return $vm;
    }

    public function genererJsonAction()
    {
        $json = $this->getMacroService()->generateJSON();

        return new ViewModel([
            'title' => 'JSON pour tinyMCE',
            'json' => $json,
        ]);
    }

}