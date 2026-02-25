<?php

namespace EmploiRepere\Controller;

use EmploiRepere\Entity\Db\EmploiRepereCodeFonctionFicheMetier;
use EmploiRepere\Form\EmploiRepereCodeFonctionFicheMetier\EmploiRepereCodeFonctionFicheMetierFormAwareTrait;
use EmploiRepere\Service\EmploiRepere\EmploiRepereServiceAwareTrait;
use EmploiRepere\Service\EmploiRepereCodeFonctionFicheMetier\EmploiRepereCodeFonctionFicheMetierServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class EmploiRepereCodeFonctionFicheMetierController extends AbstractActionController
{
    use EmploiRepereServiceAwareTrait;
    use EmploiRepereCodeFonctionFicheMetierServiceAwareTrait;
    use EmploiRepereCodeFonctionFicheMetierFormAwareTrait;

    public function ajouterAction(): ViewModel
    {
        $emploiRepere = $this->getEmploiRepereService()->getRequestedEmploiRepere($this);

        $ercffm = new EmploiRepereCodeFonctionFicheMetier();
        $ercffm->setEmploiRepere($emploiRepere);
        $form = $this->getEmploiRepereCodeFonctionFicheMetierForm();
        $form->setAttribute('action', $this->url()->fromRoute('emploi-repere/emploi-repere-code-fonction-fiche-metier/ajouter', ['emploi-repere' => $emploiRepere->getId()], [], true));
        $form->bind($ercffm);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getEmploiRepereCodeFonctionFicheMetierService()->create($ercffm);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Ajout d'un couple code fonction / fiche métier",
            'form' => $form,
        ]);
        $vm->setTemplate('emploi-repere/formulaire-codefonction-fichemetier');
        return $vm;
    }

    public function modifierAction(): ViewModel
    {
        $ercffm = $this->getEmploiRepereCodeFonctionFicheMetierService()->getRequestedEmploiRepereCodeFonctionFicheMetier($this);

        $form = $this->getEmploiRepereCodeFonctionFicheMetierForm();
        $form->setAttribute('action', $this->url()->fromRoute('emploi-repere/emploi-repere-code-fonction-fiche-metier/modifier', ['id' => $ercffm->getId()], [], true));
        $form->bind($ercffm);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getEmploiRepereCodeFonctionFicheMetierService()->update($ercffm);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Modification d'un couple code fonction / fiche métier",
            'form' => $form,
        ]);
        $vm->setTemplate('emploi-repere/formulaire-codefonction-fichemetier');
        return $vm;
    }

    public function retirerAction(): Response
    {
        $ercffm = $this->getEmploiRepereCodeFonctionFicheMetierService()->getRequestedEmploiRepereCodeFonctionFicheMetier($this);
        $this->getEmploiRepereCodeFonctionFicheMetierService()->delete($ercffm);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('emploi-repere', [], [], true);
    }
}
