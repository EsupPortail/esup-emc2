<?php

namespace Application\Controller;

use Application\Entity\Db\FamilleProfessionnelle;
use Application\Form\ModifierLibelle\ModifierLibelleForm;
use Application\Form\ModifierLibelle\ModifierLibelleFormAwareTrait;
use Application\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FamilleProfessionnelleController extends AbstractActionController {
    use FamilleProfessionnelleServiceAwareTrait;
    use ModifierLibelleFormAwareTrait;


    public function ajouterAction()
    {
        $famille = new FamilleProfessionnelle();

        /** @var ModifierLibelleForm $form */
        $form = $this->getModifierLibelleForm();
        $form->setAttribute('action', $this->url()->fromRoute('famille-professionnelle/ajouter', [], [], true));
        $form->bind($famille);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFamilleProfessionnelleService()->create($famille);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajouter une nouvelle famille de métiers',
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction()
    {
        $famille = $this->getFamilleProfessionnelleService()->getRequestedFamilleProfessionnelle($this);

        /** @var ModifierLibelleForm $form */
        $form = $this->getModifierLibelleForm();
        $form->setAttribute('action', $this->url()->fromRoute('famille-professionnelle/modifier', ['famille-professionnelle' => $famille->getId()], [], true));
        $form->bind($famille);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFamilleProfessionnelleService()->update($famille);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Modifier une famille de métiers',
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction()
    {
        $famille = $this->getFamilleProfessionnelleService()->getRequestedFamilleProfessionnelle($this);

        if ($famille !== null) {
            $this->getFamilleProfessionnelleService()->historise($famille);
        }

        return $this->redirect()->toRoute('metier', [], ['fragment'=>'famille'], true);
    }

    public function restaurerAction()
    {
        $famille = $this->getFamilleProfessionnelleService()->getRequestedFamilleProfessionnelle($this);

        if ($famille !== null) {
            $this->getFamilleProfessionnelleService()->restore($famille);
        }

        return $this->redirect()->toRoute('metier', [], ['fragment'=>'famille'], true);
    }

    public function effacerAction()
    {
        $famille = $this->getFamilleProfessionnelleService()->getRequestedFamilleProfessionnelle($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getFamilleProfessionnelleService()->delete($famille);
            //return $this->redirect()->toRoute('home');
            exit();
        }

        $vm = new ViewModel();
        if ($famille !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la famille professionnelle" . $famille->getLibelle(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('famille-professionnelle/effacer', ["famille-professionnelle" => $famille->getId()], [], true),
            ]);
        }
        return $vm;
    }

}