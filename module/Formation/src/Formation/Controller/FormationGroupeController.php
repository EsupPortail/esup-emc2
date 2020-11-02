<?php

namespace Formation\Controller;

use Formation\Entity\Db\FormationGroupe;
use Formation\Form\FormationGroupe\FormationGroupeFormAwareTrait;
use Formation\Service\FormationGroupe\FormationGroupeServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FormationGroupeController extends AbstractActionController
{
    use FormationGroupeServiceAwareTrait;
    use FormationGroupeFormAwareTrait;

    public function afficherGroupeAction()
    {
        $groupe = $this->getFormationGroupeService()->getRequestedFormationGroupe($this);

        return new ViewModel([
            'title' => 'Affichage du groupe',
            'groupe' => $groupe,
        ]);
    }

    public function ajouterGroupeAction()
    {
        $groupe = new FormationGroupe();
        $form = $this->getFormationGroupeForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation-groupe/ajouter', [], [], true));
        $form->bind($groupe);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormationGroupeService()->create($groupe);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajouter un groupe de formation',
            'form' => $form,
        ]);
        return $vm;
    }

    public function editerGroupeAction()
    {
        $groupe = $this->getFormationGroupeService()->getRequestedFormationGroupe($this);
        $form = $this->getFormationGroupeForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation-groupe/editer', ['formation-groupe' => $groupe->getId()], [], true));
        $form->bind($groupe);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormationGroupeService()->update($groupe);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Modifier un groupe de formation',
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserGroupeAction()
    {
        $groupe = $this->getFormationGroupeService()->getRequestedFormationGroupe($this);
        $this->getFormationGroupeService()->historise($groupe);
        return $this->redirect()->toRoute('formation', [], ['fragment' => 'groupe'], true);
    }

    public function restaurerGroupeAction()
    {
        $groupe = $this->getFormationGroupeService()->getRequestedFormationGroupe($this);
        $this->getFormationGroupeService()->restore($groupe);
        return $this->redirect()->toRoute('formation', [], ['fragment' => 'groupe'], true);
    }

    public function detruireGroupeAction()
    {
        $groupe = $this->getFormationGroupeService()->getRequestedFormationGroupe($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getFormationGroupeService()->delete($groupe);
            exit();
        }

        $vm = new ViewModel();
        if ($groupe !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du groupe de formation [" . $groupe->getLibelle() . "]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('formation-groupe/detruire', ["formation-groupe" => $groupe->getId()], [], true),
            ]);
        }
        return $vm;
    }

    public function updateOrdreGroupeAction()
    {
        $ordre = explode("_", $this->params()->fromRoute('ordre'));
        $sort = [];
        $position = 1;
        foreach ($ordre as $item) {
            $sort[$item] = $position;
            $position++;
        }

        $groupes = $this->getFormationGroupeService()->getFormationsGroupes();
        foreach ($groupes as $groupe) {
            if (!isset($sort[$groupe->getId()]) and $groupe->getOrdre() !== null) {
                $groupe->setOrdre(null);
                $this->getFormationGroupeService()->update($groupe);
            }
            if ($groupe->getOrdre() != $sort[$groupe->getId()]) {
                $groupe->setOrdre($sort[$groupe->getId()]);
                $this->getFormationGroupeService()->update($groupe);
            }
        }

        return new ViewModel();
    }
}