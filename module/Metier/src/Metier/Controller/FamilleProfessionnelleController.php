<?php

namespace Metier\Controller;

use Application\Form\ModifierLibelle\ModifierLibelleForm;
use Application\Form\ModifierLibelle\ModifierLibelleFormAwareTrait;
use Metier\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;
use Metier\Entity\Db\FamilleProfessionnelle;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class FamilleProfessionnelleController extends AbstractActionController {
    use FamilleProfessionnelleServiceAwareTrait;
    use ModifierLibelleFormAwareTrait;

    public function indexAction() : ViewModel
    {
        $familles = $this->getFamilleProfessionnelleService()->getFamillesProfessionnelles();

        return new ViewModel([
            'familles' => $familles,
        ]);
    }

    public function afficherAction() : ViewModel
    {
        $famille = $this->getFamilleProfessionnelleService()->getRequestedFamilleProfessionnelle($this);

        $metiers = $famille->getMetiers();

        return new ViewModel([
            'title' => "Affichage de la famille professionnelle",
            'famille' => $famille,
            'metiers' => $metiers,
        ]);
    }

    public function ajouterAction() : ViewModel
    {
        $famille = new FamilleProfessionnelle();

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
        $vm->setTemplate('default/default-form');
        $vm->setVariables([
            'title' => 'Ajouter une nouvelle famille professionnelle',
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction() : ViewModel
    {
        $famille = $this->getFamilleProfessionnelleService()->getRequestedFamilleProfessionnelle($this);

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
        $vm->setTemplate('default/default-form');
        $vm->setVariables([
            'title' => 'Modifier une famille professionnelle',
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction() : Response
    {
        $famille = $this->getFamilleProfessionnelleService()->getRequestedFamilleProfessionnelle($this);

        if ($famille !== null) {
            $this->getFamilleProfessionnelleService()->historise($famille);
        }

        return $this->redirect()->toRoute('famille-professionnelle', [], [], true);
    }

    public function restaurerAction() : Response
    {
        $famille = $this->getFamilleProfessionnelleService()->getRequestedFamilleProfessionnelle($this);

        if ($famille !== null) {
            $this->getFamilleProfessionnelleService()->restore($famille);
        }

        return $this->redirect()->toRoute('famille-professionnelle', [], [], true);
    }

    public function supprimerAction() : ViewModel
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
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la famille professionnelle" . $famille->getLibelle(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('famille-professionnelle/supprimer', ["famille-professionnelle" => $famille->getId()], [], true),
            ]);
        }
        return $vm;
    }

}