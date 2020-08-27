<?php

namespace Application\Controller;

use Application\Entity\Db\ParcoursDeFormation;
use Application\Form\AjouterFormation\AjouterFormationFormAwareTrait;
use Application\Form\ModifierLibelle\ModifierLibelleFormAwareTrait;
use Application\Form\ModifierRattachement\ModifierRattachementFormAwareTrait;
use Application\Form\ParcoursDeFormation\ParcoursDeFormationFormAwareTrait;
use Application\Service\Formation\FormationServiceAwareTrait;
use Application\Service\ParcoursDeFormation\ParcoursDeFormationServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ParcoursDeFormationController extends AbstractActionController
{
    use FormationServiceAwareTrait;
    use ParcoursDeFormationServiceAwareTrait;
    use AjouterFormationFormAwareTrait;
    use ParcoursDeFormationFormAwareTrait;
    use ModifierLibelleFormAwareTrait;
    use ModifierRattachementFormAwareTrait;

    public function indexAction()
    {
        $parcoursCategorie = $this->getParcoursDeFormationService()->getParcoursDeFormationsByType(ParcoursDeFormation::TYPE_CATEGORIE);
        $parcoursMetier = $this->getParcoursDeFormationService()->getParcoursDeFormationsByType(ParcoursDeFormation::TYPE_METIER);
        $parcoursVide = $this->getParcoursDeFormationService()->getParcoursDeFormationsByType(null);

        return new ViewModel([
            'parcoursCategorie' => $parcoursCategorie,
            'parcoursMetier' => $parcoursMetier,
            'parcoursVide' => $parcoursVide,
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
//        $type = $this->params()->fromRoute('type');
//        $parcours = new ParcoursDeFormation();
//        $parcours->setType($type);
//
//        $form = $this->getParcoursDeFormationForm();
//        $form->setAttribute('action', $this->url()->fromRoute('parcours-de-formation/ajouter', ['type' => $type], [], true));
//        $form->bind($parcours);
//
//        /** @var Request $request */
//        $request = $this->getRequest();
//        if ($request->isPost()) {
//            $data = $request->getPost();
//            $form->setData($data);
//            if ($form->isValid()) {
//                $this->getParcoursDeFormationService()->create($parcours);
//            }
//        }
//
//        $vm = new ViewModel();
//        $vm->setTemplate('application/parcours-de-formation/parcours-form');
//        $vm->setVariables([
//            'title' => "Ajout d'un parcours de formation",
//            'form' => $form,
//        ]);
//        return $vm;

        $parcours = new ParcoursDeFormation();
        $this->getParcoursDeFormationService()->create($parcours);

        return $this->redirect()->toRoute('parcours-de-formation/modifier', ['parcours-de-formation' => $parcours->getId()], [], true);
    }

    public function modifierAction()
    {
        $parcours = $this->getParcoursDeFormationService()->getRequestedParcoursDeFormation($this);
        $reference = $this->getParcoursDeFormationService()->getReference($parcours);



//        $form = $this->getParcoursDeFormationForm();
//        $form->setAttribute('action', $this->url()->fromRoute('parcours-de-formation/modifier', ['parcours-de-formation' => $parcours->getId()], [], true));
//        $form->bind($parcours);
//
//        /** @var Request $request */
//        $request = $this->getRequest();
//        if ($request->isPost()) {
//            $data = $request->getPost();
//            $form->setData($data);
//            if ($form->isValid()) {
//                $this->getParcoursDeFormationService()->update($parcours);
//            }
//        }
//
//        $vm = new ViewModel();
//        $vm->setTemplate('application/parcours-de-formation/parcours-form');
//        $vm->setVariables([
//            'title' => "Modification d'un parcours de formation",
//            'form' => $form,
//        ]);
//        return $vm;

        return new ViewModel([
            'parcours' => $parcours,
            'reference' => $reference,
        ]);
    }

    public function modifierLibelleAction()
    {
        $parcours = $this->getParcoursDeFormationService()->getRequestedParcoursDeFormation($this);

        $form = $this->getModifierLibelleForm();
        $form->setAttribute('action', $this->url()->fromRoute('parcours-de-formation/modifier-libelle', ["parcours-de-formation" => $parcours->getId()], [], true));
        $form->bind($parcours);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $parcours->setLibelle($data['libelle']);
            $this->parcoursDeFormationService->update($parcours);
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modifier le libellé du parcours de formation",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierRattachementAction()
    {
        $parcours = $this->getParcoursDeFormationService()->getRequestedParcoursDeFormation($this);

        $form = $this->getModifierRattachementForm();
        $form->setAttribute('action', $this->url()->fromRoute('parcours-de-formation/modifier-rattachement', ["parcours-de-formation" => $parcours->getId()], [], true));
        $form->bind($parcours);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->parcoursDeFormationService->update($parcours);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modifier le rattachement du parcours de formation",
            'form' => $form,
        ]);
        return $vm;
    }

    public function ajouterFormationAction()
    {
        $parcours = $this->getParcoursDeFormationService()->getRequestedParcoursDeFormation($this);

        $form = $this->getAjouterFormationForm();
        $form->setAttribute('action', $this->url()->fromRoute('parcours-de-formation/ajouter-formation', ['parcours-de-formation' => $parcours->getId()], [], true));
        $form->bind($parcours);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if (isset($data['formation'])) {
                foreach ($data['formation'] as $id) {
                    $formation = $this->getFormationService()->getFormation($id);
                    $parcours->addFormation($formation);
                }
                $this->parcoursDeFormationService->update($parcours);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajouter une ou plusieurs formations',
            'form' => $form,
        ]);
        return $vm;
    }

    public function retirerFormationAction()
    {
        $parcours = $this->getParcoursDeFormationService()->getRequestedParcoursDeFormation($this);
        $formation = $this->getFormationService()->getRequestedFormation($this);

        $parcours->removeFormation($formation);
        $this->getParcoursDeFormationService()->update($parcours);

        return $this->redirect()->toRoute('parcours-de-formation/modifier', ['parcours-de-formation' => $parcours->getId()], [], true);
    }

    private function getFragment(ParcoursDeFormation $parcours)
    {
        $type = $parcours->getType();
        if ($type === ParcoursDeFormation::TYPE_METIER) return "metier";
        if ($type === ParcoursDeFormation::TYPE_CATEGORIE) return "categorie";
        return "";
    }

    public function historiserAction()
    {
        $parcours = $this->getParcoursDeFormationService()->getRequestedParcoursDeFormation($this);
        $this->getParcoursDeFormationService()->historise($parcours);
        return $this->redirect()->toRoute('parcours-de-formation', [], ['fragment' => $this->getFragment($parcours)], true);
    }

    public function restaurerAction()
    {
        $parcours = $this->getParcoursDeFormationService()->getRequestedParcoursDeFormation($this);
        $this->getParcoursDeFormationService()->restore($parcours);
        return $this->redirect()->toRoute('parcours-de-formation', [], ['fragment' => $this->getFragment($parcours)], true);
    }

    public function detruireAction()
    {
        /** @var ParcoursDeFormation $parcours */
        $parcours = $this->getParcoursDeFormationService()->getRequestedParcoursDeFormation($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getParcoursDeFormationService()->delete($parcours);
            exit();
        }

        $vm = new ViewModel();
        if ($parcours !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du parcours de formation [" . $parcours->getLibelle() . "]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('parcours-de-formation/detruire', ["parcours-de-formation" => $parcours->getId()], [], true),
            ]);
        }
        return $vm;
    }
}