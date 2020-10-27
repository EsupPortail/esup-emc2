<?php

namespace Formation\Controller;

use Application\Form\ModifierLibelle\ModifierLibelleFormAwareTrait;
use Application\Service\ParcoursDeFormation\ParcoursDeFormationServiceAwareTrait;
use Formation\Entity\Db\Formation;
use Formation\Form\Formation\FormationFormAwareTrait;
use Formation\Form\FormationGroupe\FormationGroupeFormAwareTrait;
use Formation\Service\Formation\FormationServiceAwareTrait;
use Formation\Service\FormationGroupe\FormationGroupeServiceAwareTrait;
use Formation\Service\FormationTheme\FormationThemeServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FormationController extends AbstractActionController
{
    use FormationServiceAwareTrait;
    use FormationGroupeServiceAwareTrait;
    use FormationThemeServiceAwareTrait;
    use ParcoursDeFormationServiceAwareTrait;

    use FormationFormAwareTrait;
    use FormationGroupeFormAwareTrait;
    use ModifierLibelleFormAwareTrait;

    public function indexAction()
    {
        $formations = $this->getFormationService()->getFormations('libelle');
        $groupes = $this->getFormationGroupeService()->getFormationsGroupes();
        $themes = $this->getFormationThemeService()->getFormationsThemes();
        return new ViewModel([
            'formations' => $formations,
            'groupes' => $groupes,
            'themes' => $themes,
        ]);
    }

    public function ajouterAction()
    {
        $formation = new Formation();
        $form = $this->getFormationForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/ajouter', [], [], true));
        $form->bind($formation);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormationService()->create($formation);
                exit;
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajouter une formation',
            'form' => $form,
        ]);
        return $vm;
    }

    public function editerAction()
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);

        $form = $this->getFormationForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/editer', ['formation' => $formation->getId()], [], true));
        $form->bind($formation);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormationService()->update($formation);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('formation/formation/modifier');
        $vm->setVariables([
            'title' => 'Edition d\'une formation',
            'formation' => $formation,
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction()
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);
        $this->getFormationService()->historise($formation);
        return $this->redirect()->toRoute('formation', [], ['fragment' => 'formation'], true);
    }

    public function restaurerAction()
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);
        $this->getFormationService()->restore($formation);
        return $this->redirect()->toRoute('formation', [], ['fragment' => 'formation'], true);

    }

    public function detruireAction()
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getFormationService()->delete($formation);
            exit();
        }

        $vm = new ViewModel();
        if ($formation !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la formation [" . $formation->getLibelle() . "]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('formation/detruire', ["formation" => $formation->getId()], [], true),
            ]);
        }
        return $vm;
    }

    public function modifierFormationInformationsAction()
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);

        $form = $this->getFormationForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/modifier-formation-informations', ['formation' => $formation->getId()], [], true));
        $form->bind($formation);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormationService()->update($formation);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Modifier les informations de la formation',
            'formation' => $formation,
            'form' => $form,
        ]);
        return $vm;
    }
}