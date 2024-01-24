<?php

namespace FicheMetier\Controller;

use FicheMetier\Entity\Db\ThematiqueElement;
use FicheMetier\Form\ThematiqueElement\ThematiqueElementFormAwareTrait;
use FicheMetier\Service\FicheMetier\FicheMetierServiceAwareTrait;
use FicheMetier\Service\ThematiqueElement\ThematiqueElementServiceAwareTrait;
use FicheMetier\Service\ThematiqueType\ThematiqueTypeServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class ThematiqueElementController extends AbstractActionController
{
    use FicheMetierServiceAwareTrait;
    use ThematiqueTypeServiceAwareTrait;
    use ThematiqueElementServiceAwareTrait;
    use ThematiqueElementFormAwareTrait;

    public function indexAction(): ViewModel
    {
        return new ViewModel([]);
    }

    public function modifierAction(): ViewModel
    {
        $ficheMetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $thematiqueType = $this->getThematiqueTypeService()->getRequestedThematiqueType($this);

        $element = $this->getThematiqueElementService()->getThematiqueElementByFicheMetierAndThematiqueType($ficheMetier, $thematiqueType);
        if ($element === null) $element = new ThematiqueElement();

        $form = $this->getThematiqueElementForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier/thematique-element/modifier', ['fiche-metier' => $ficheMetier->getId(), 'thematique-type' => $thematiqueType->getId()], [], true));
        $form->bind($element);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getThematiqueElementService()->createOrUpdate($ficheMetier, $thematiqueType, $element->getNiveauMaitrise(), $element->getComplement());
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Modifier la thématique [".$thematiqueType->getLibelle()."]",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function historiserAction(): Response
    {
        $thematiqueElement = $this->getThematiqueElementService()->getRequestedThematiqueElement($this);
        $this->getThematiqueElementService()->historise($thematiqueElement);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('home');
    }

    public function restaurerAction(): Response
    {
        $thematiqueElement = $this->getThematiqueElementService()->getRequestedThematiqueElement($this);
        $this->getThematiqueElementService()->historise($thematiqueElement);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('home');
    }

    public function supprimerAction(): ViewModel
    {
        $thematiqueElement = $this->getThematiqueElementService()->getRequestedThematiqueElement($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") {
                $this->getThematiqueElementService()->delete($thematiqueElement);
            }
            exit();
        }

        $vm = new ViewModel();
        if ($thematiqueElement !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de l'élément associé à  [".$thematiqueElement->getType()->getLibelle()."]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('fiche-metier/thematique-element/supprimer', ["thematique-element" => $thematiqueElement->getId()], [], true),
            ]);
        }
        return $vm;
    }
}