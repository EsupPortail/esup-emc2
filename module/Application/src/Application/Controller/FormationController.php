<?php

namespace Application\Controller;

use Application\Entity\Db\Formation;
use Application\Entity\Db\FormationTheme;
use Application\Form\Formation\FormationFormAwareTrait;
use Application\Form\FormationTheme\FormationThemeFormAwareTrait;
use Application\Service\Formation\FormationServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FormationController extends AbstractActionController
{
    use FormationServiceAwareTrait;
    use FormationFormAwareTrait;
    use FormationThemeFormAwareTrait;

    public function indexAction()
    {
        $formations = $this->getFormationService()->getFormations('libelle');
        $themes = $this->getFormationService()->getFormationsThemes();
        return new ViewModel([
            'formations' => $formations,
            'themes' => $themes,
        ]);
    }

    public function afficherAction()
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);
        return new ViewModel([
            'title' => 'Affichage de la formation ['.$formation->getLibelle().']',
            'formation' => $formation,
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
//        if ($formation === null) $formation = $this->getFormationService()->getLastFormation();

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
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Edition d\'une formation',
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction()
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);
        $this->getFormationService()->historise($formation);
        return $this->redirect()->toRoute('formation', [], [], true);
    }

    public function restaurerAction()
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);
        $this->getFormationService()->restore($formation);
        return $this->redirect()->toRoute('formation', [], [], true);

    }

    public function detruireAction()
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);
        $this->getFormationService()->delete($formation);
        return $this->redirect()->toRoute('formation', [], [], true);
    }

    public function ajouterThemeAction()
    {
        $theme = new FormationTheme();
        $form = $this->getFormationThemeForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation-theme/ajouter', [], [], true));
        $form->bind($theme);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormationService()->createTheme($theme);
                //return $this->redirect()->toRoute('formation', [], [], true);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajouter un thème de formation',
            'form' => $form,
        ]);
        return $vm;
    }

    public function afficherThemeAction()
    {
        $theme = $this->getFormationService()->getRequestedFormationTheme($this);

        return new ViewModel([
           'title' => 'Affichage du thème',
           'theme' => $theme,
        ]);
    }

    public function editerThemeAction()
    {
        $theme = $this->getFormationService()->getRequestedFormationTheme($this);
        $form = $this->getFormationThemeForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation-theme/editer', ['formation-theme' => $theme->getId()], [], true));
        $form->bind($theme);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormationService()->updateTheme($theme);
                //return $this->redirect()->toRoute('formation', [], [], true);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Modifier un thème de formation',
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserThemeAction()
    {
        $theme = $this->getFormationService()->getRequestedFormationTheme($this);
        $this->getFormationService()->historizeTheme($theme);
        return $this->redirect()->toRoute('formation', [], [], true);
    }

    public function restaurerThemeAction()
    {
        $theme = $this->getFormationService()->getRequestedFormationTheme($this);
        $this->getFormationService()->restoreTheme($theme);
        return $this->redirect()->toRoute('formation', [], [], true);
    }

    public function detruireThemeAction()
    {
        $theme = $this->getFormationService()->getRequestedFormationTheme($this);
        $this->getFormationService()->deleteTheme($theme);
        return $this->redirect()->toRoute('formation', [], [], true);
    }
}