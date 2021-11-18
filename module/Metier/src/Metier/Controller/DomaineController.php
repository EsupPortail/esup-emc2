<?php

namespace Metier\Controller;

use Metier\Entity\Db\Domaine;
use Metier\Form\Domaine\DomaineFormAwareTrait;
use Metier\Service\Domaine\DomaineServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class DomaineController extends AbstractActionController {
    use DomaineServiceAwareTrait;
    use DomaineFormAwareTrait;

    public function indexAction() : ViewModel
    {
        $domaines = $this->getDomaineService()->getDomaines();

        return new ViewModel([
            'domaines' => $domaines,
        ]);
    }

    public function ajouterAction()
    {
        /** @var Domaine $domaine */
        $domaine = new Domaine();

        $form = $this->getDomaineForm();
        $form->setAttribute('action', $this->url()->fromRoute('domaine/ajouter', [], [], true));
        $form->bind($domaine);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getDomaineService()->create($domaine);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajouter un domaine',
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction()
    {
        $domaine = $this->getDomaineService()->getRequestedDomaine($this);

        $form = $this->getDomaineForm();
        $form->setAttribute('action', $this->url()->fromRoute('domaine/modifier', ['domaine' => $domaine->getId()], [], true));
        $form->bind($domaine);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getDomaineService()->update($domaine);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Modifier un domaine',
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction()
    {
        $domaine = $this->getDomaineService()->getRequestedDomaine($this);
        if ($domaine !== null) {
            $this->getDomaineService()->historise($domaine);
        }
        return $this->redirect()->toRoute('metier', [], ['fragment'=>'domaine'], true);
    }

    public function restaurerAction()
    {
        $domaine = $this->getDomaineService()->getRequestedDomaine($this);
        if ($domaine !== null) {
            $this->getDomaineService()->restore($domaine);
        }
        return $this->redirect()->toRoute('metier', [], ['fragment'=>'domaine'], true);
    }

    public function effacerAction()
    {
        $domaine = $this->getDomaineService()->getRequestedDomaine($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getDomaineService()->delete($domaine);
            exit();
        }

        $vm = new ViewModel();
        if ($domaine !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du domaine " . $domaine->getLibelle(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('domaine/effacer', ["domaine" => $domaine->getId()], [], true),
            ]);
        }
        return $vm;
    }
}