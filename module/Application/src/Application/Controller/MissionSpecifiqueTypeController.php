<?php

namespace Application\Controller;

use Application\Entity\Db\MissionSpecifiqueType;
use Application\Form\ModifierLibelle\ModifierLibelleFormAwareTrait;
use Application\Service\MissionSpecifiqueType\MissionSpecifiqueTypeServiceAwareTrait;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class MissionSpecifiqueTypeController extends AbstractActionController {
    use MissionSpecifiqueTypeServiceAwareTrait;
    use ModifierLibelleFormAwareTrait;

    public function indexAction() : ViewModel
    {
        $types = $this->getMissionSpecifiqueTypeService()->getMissionsSpecifiquesTypes();

        return new ViewModel([
            "types" => $types,
        ]);
    }

    public function afficherAction() : ViewModel
    {
        $type = $this->getMissionSpecifiqueTypeService()->getRequestedMissionSpecifiqueType($this);

        return new ViewModel([
            'title' => "Affichage d'un type de mission spécifique",
            'type' => $type,
        ]);
    }

    public function ajouterAction() : ViewModel
    {
        $type = new MissionSpecifiqueType();
        $form = $this->getModifierLibelleForm();
        $form->setAttribute('action', $this->url()->fromRoute('mission-specifique-type/ajouter'));
        $form->bind($type);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMissionSpecifiqueTypeService()->create($type);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'un type de mission spécifique",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction() : ViewModel
    {
        $type = $this->getMissionSpecifiqueTypeService()->getRequestedMissionSpecifiqueType($this);
        $form = $this->getModifierLibelleForm();
        $form->setAttribute('action', $this->url()->fromRoute('mission-specifique-type/modifier', ['type' => $type->getId()], [], true));
        $form->bind($type);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMissionSpecifiqueTypeService()->update($type);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'un type de mission spécifique",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction() : Response
    {
        $type = $this->getMissionSpecifiqueTypeService()->getRequestedMissionSpecifiqueType($this);
        $this->getMissionSpecifiqueTypeService()->historise($type);
        return $this->redirect()->toRoute('mission-specifique-type', [], [], true);
    }

    public function restaurerAction() : Response
    {
        $type = $this->getMissionSpecifiqueTypeService()->getRequestedMissionSpecifiqueType($this);
        $this->getMissionSpecifiqueTypeService()->restore($type);
        return $this->redirect()->toRoute('mission-specifique-type', [], [], true);
    }

    public function detruireAction() : ViewModel
    {
        $type = $this->getMissionSpecifiqueTypeService()->getRequestedMissionSpecifiqueType($this);
        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getMissionSpecifiqueTypeService()->delete($type);
            exit();
        }

        $vm = new ViewModel();
        if ($type !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du type de mission spécifique  " . $type->getLibelle(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('mission-specifique-type/detruire', ["type" => $type->getId()], [], true),
            ]);
        }
        return $vm;
    }
}