<?php

namespace Formation\Controller;

use Formation\Entity\Db\FormationTheme;
use Formation\Form\FormationTheme\FormationThemeFormAwareTrait;
use Formation\Service\FormationTheme\FormationThemeServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FormationThemeController extends AbstractActionController {
    use FormationThemeServiceAwareTrait;
    use FormationThemeFormAwareTrait;

    public function afficherThemeAction()
    {
        $theme = $this->getFormationThemeService()->getRequestedFormationTheme($this);

        return new ViewModel([
            'title' => 'Affichage du thème',
            'theme' => $theme,
        ]);
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
                $this->getFormationThemeService()->create($theme);
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

    public function editerThemeAction()
    {
        $theme = $this->getFormationThemeService()->getRequestedFormationTheme($this);
        $form = $this->getFormationThemeForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation-theme/editer', ['formation-theme' => $theme->getId()], [], true));
        $form->bind($theme);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormationThemeService()->update($theme);
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
        $theme = $this->getFormationThemeService()->getRequestedFormationTheme($this);
        $this->getFormationThemeService()->historise($theme);
        return $this->redirect()->toRoute('formation', [], ['fragment' => 'theme'], true);
    }

    public function restaurerThemeAction()
    {
        $theme = $this->getFormationThemeService()->getRequestedFormationTheme($this);
        $this->getFormationThemeService()->restore($theme);
        return $this->redirect()->toRoute('formation', [], ['fragment' => 'theme'], true);
    }

    public function detruireThemeAction()
    {
        $theme = $this->getFormationThemeService()->getRequestedFormationTheme($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getFormationThemeService()->delete($theme);
            exit();
        }

        $vm = new ViewModel();
        if ($theme !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du thème de formation [" . $theme->getLibelle() . "]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('formation-theme/detruire', ["formation-theme" => $theme->getId()], [], true),
            ]);
        }
        return $vm;
    }
}