<?php

namespace Element\Controller;

use Application\Form\ApplicationGroupe\ApplicationGroupeFormAwareTrait;
use Application\Service\Application\ApplicationGroupeServiceAwareTrait;
use Element\Entity\Db\ApplicationGroupe;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ApplicationGroupeController extends AbstractActionController {
    use ApplicationGroupeServiceAwareTrait;
    use ApplicationGroupeFormAwareTrait;

    public function ajouterAction()
    {
        $groupe = new ApplicationGroupe();

        $form = $this->getApplicationGroupeForm();
        $form->setAttribute('action', $this->url()->fromRoute('application/groupe/ajouter',[],[], true));
        $form->bind($groupe);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getApplicationGroupeService()->create($groupe);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'un groupe d'application",
            'form' => $form,
        ]);
        return $vm;
    }

    public function afficherAction() : ViewModel
    {
        $groupe = $this->getApplicationGroupeService()->getRequestedApplicationGroupe($this);

        return new ViewModel([
            'title' => "Affichage du groupe d'application",
            'groupe' => $groupe,
        ]);
    }

    public function modifierAction()
    {
        $groupe = $this->getApplicationGroupeService()->getRequestedApplicationGroupe($this);

        $form = $this->getApplicationGroupeForm();
        $form->setAttribute('action', $this->url()->fromRoute('application/groupe/editer',[],[], true));
        $form->bind($groupe);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getApplicationGroupeService()->update($groupe);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'un groupe d'application",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction()
    {
        $groupe = $this->getApplicationGroupeService()->getRequestedApplicationGroupe($this);
        $this->getApplicationGroupeService()->historise($groupe);
        return $this->redirect()->toRoute('application', [], ['fragment' => 'groupe'], true);
    }

    public function restaurerAction()
    {
        $groupe = $this->getApplicationGroupeService()->getRequestedApplicationGroupe($this);
        $this->getApplicationGroupeService()->restore($groupe);
        return $this->redirect()->toRoute('application', [], ['fragment' => 'groupe'], true);
    }

    public function detruireAction()
    {
        $groupe = $this->getApplicationGroupeService()->getRequestedApplicationGroupe($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getApplicationGroupeService()->delete($groupe);
            exit();
        }

        $vm = new ViewModel();
        if ($groupe !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du groupe d'application [" . $groupe->getLibelle(). "]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('application/groupe/detruire', ["application-groupe" => $groupe->getId()], [], true),
            ]);
        }
        return $vm;
    }

}