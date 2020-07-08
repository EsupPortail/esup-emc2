<?php

namespace Application\Controller;

use Application\Entity\Db\ParcoursDeFormation;
use Application\Form\ParcoursDeFormation\ParcoursDeFormationFormAwareTrait;
use Application\Service\ParcoursDeFormation\ParcoursDeFormationServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ParcoursDeFormationController extends AbstractActionController
{
    use ParcoursDeFormationServiceAwareTrait;
    use ParcoursDeFormationFormAwareTrait;

    public function indexAction()
    {
        $parcoursCategorie = $this->getParcoursDeFormationService()->getParcoursDeFormationsByType(ParcoursDeFormation::TYPE_CATEGORIE);
        $parcoursMetier = $this->getParcoursDeFormationService()->getParcoursDeFormationsByType(ParcoursDeFormation::TYPE_METIER);

        return new ViewModel([
            'parcoursCategorie' => $parcoursCategorie,
            'parcoursMetier' => $parcoursMetier,
        ]);
    }

    public function afficherAction()
    {
        $parcours = $this->getParcoursDeFormationService()->getRequestedParcoursDeFormation($this);
        $reference = $this->getParcoursDeFormationService()->getReference($parcours);

        return new ViewModel([
            'title' => "Affichage du parcours de formation",
            'parcours' => $parcours,
            'reference' => $reference,
        ]);
    }

    public function ajouterAction()
    {
        $type = $this->params()->fromRoute('type');
        $parcours = new ParcoursDeFormation();
        $parcours->setType($type);

        $form = $this->getParcoursDeFormationForm();
        $form->setAttribute('action', $this->url()->fromRoute('parcours-de-formation/ajouter', ['type' => $type], [], true));
        $form->bind($parcours);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getParcoursDeFormationService()->create($parcours);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/parcours-de-formation/parcours-form');
        $vm->setVariables([
            'title' => "Ajout d'un parcours de formation",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction()
    {
        $parcours = $this->getParcoursDeFormationService()->getRequestedParcoursDeFormation($this);

        $form = $this->getParcoursDeFormationForm();
        $form->setAttribute('action', $this->url()->fromRoute('parcours-de-formation/modifier', ['parcours-de-formation' => $parcours->getId()], [], true));
        $form->bind($parcours);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getParcoursDeFormationService()->update($parcours);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/parcours-de-formation/parcours-form');
        $vm->setVariables([
            'title' => "Modification d'un parcours de formation",
            'form' => $form,
        ]);
        return $vm;
    }
}