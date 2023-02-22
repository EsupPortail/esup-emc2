<?php

namespace FicheMetier\Controller;

use Application\Service\FicheMetier\FicheMetierServiceAwareTrait;
use FicheMetier\Form\Raison\RaisonFormAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Metier\Form\SelectionnerMetier\SelectionnerMetierFormAwareTrait;
use Metier\Service\Metier\MetierServiceAwareTrait;

class FicheMetierController extends AbstractActionController {
    use FicheMetierServiceAwareTrait;
    use MetierServiceAwareTrait;

    use RaisonFormAwareTrait;
    use SelectionnerMetierFormAwareTrait;

    /** CRUD **********************************************************************************************************/

    public function historiserAction() : Response
    {
        $fiche = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $this->getFicheMetierService()->historise($fiche);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        //todo pointer vers la nouvelle route
        return $this->redirect()->toRoute('fiche-metier-type', [], [], true);
    }

    public function restaurerAction() : Response
    {
        $fiche = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $this->getFicheMetierService()->restore($fiche);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        //todo pointer vers la nouvelle route
        return $this->redirect()->toRoute('fiche-metier-type', [], [], true);
    }

    /** COMPOSITION FICHE *********************************************************************************************/

    public function modifierMetierAction() : ViewModel
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');

        $form = $this->getSelectionnerMetierForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier/modifier-metier', ['fiche-metier' => $fichemetier->getId()], [], true));
        $form->bind($fichemetier);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFicheMetierService()->update($fichemetier);
                $this->flashMessenger()->addSuccessMessage("Mise à jour du métier associé.");
                exit();
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/default-form');
        $vm->setVariables([
            'title' => 'Modifier le métier associé à la fiche métier',
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierRaisonAction() : ViewModel
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');

        $form = $this->getRaisonForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier/modifier-raison', ['fiche-metier' => $fichemetier->getId()], [], true));
        $form->bind($fichemetier);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFicheMetierService()->update($fichemetier);
                $this->flashMessenger()->addSuccessMessage("Mise à jour de la \"raison d'être du métier\" effectuée.");
                exit();
            }
        }

        $vm =  new ViewModel([
            'title' => "Modification de la raison d'être du métier",
            'form' => $form,
            'info' => "Laisser vide si aucune raison n'est nécessaire",
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }
}