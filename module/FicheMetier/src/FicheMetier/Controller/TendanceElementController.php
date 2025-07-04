<?php

namespace FicheMetier\Controller;

use FicheMetier\Entity\Db\TendanceElement;
use FicheMetier\Form\TendanceElement\TendanceElementFormAwareTrait;
use FicheMetier\Service\FicheMetier\FicheMetierServiceAwareTrait;
use FicheMetier\Service\TendanceElement\TendanceElementServiceAwareTrait;
use FicheMetier\Service\TendanceType\TendanceTypeServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class TendanceElementController extends AbstractActionController
{
    use FicheMetierServiceAwareTrait;
    use TendanceTypeServiceAwareTrait;
    use TendanceElementServiceAwareTrait;
    use TendanceElementFormAwareTrait;

    public function indexAction(): ViewModel
    {
        return new ViewModel([]);
    }

    public function modifierAction(): ViewModel
    {
        $ficheMetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $tendanceType = $this->getTendanceTypeService()->getRequestedTendanceType($this);

        $element = $this->getTendanceElementService()->getTendanceElementByFicheMetierAndTendanceType($ficheMetier, $tendanceType);
        if ($element === null) $element = new TendanceElement();

        $form = $this->getTendanceElementForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier/tendance-element/modifier', ['fiche-metier' => $ficheMetier->getId(), 'tendance-type' => $tendanceType->getId()], [], true));
        $form->bind($element);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getTendanceElementService()->createOrUpdate($ficheMetier, $tendanceType, $element->getTexte());
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Modifier la tendance [" . $tendanceType->getLibelle() . "]",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function historiserAction(): Response
    {
        $tendanceElement = $this->getTendanceElementService()->getRequestedTendanceElement($this);
        $this->getTendanceElementService()->historise($tendanceElement);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('home');
    }

    public function restaurerAction(): Response
    {
        $tendanceElement = $this->getTendanceElementService()->getRequestedTendanceElement($this);
        $this->getTendanceElementService()->historise($tendanceElement);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('home');
    }

    public function supprimerAction(): ViewModel
    {
        $tendanceElement = $this->getTendanceElementService()->getRequestedTendanceElement($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") {
                $this->getTendanceElementService()->delete($tendanceElement);
            }
            exit();
        }

        $vm = new ViewModel();
        if ($tendanceElement !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de l'élément associé à  [" . $tendanceElement->getType()->getLibelle() . "]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('fiche-metier/tendance-element/supprimer', ["tendance-element" => $tendanceElement->getId()], [], true),
            ]);
        }
        return $vm;
    }
}