<?php

namespace Element\Controller;

use Element\Entity\Db\CompetenceTheme;
use Element\Form\CompetenceTheme\CompetenceThemeFormAwareTrait;
use Element\Service\CompetenceTheme\CompetenceThemeServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class CompetenceThemeController extends AbstractActionController {
    use CompetenceThemeServiceAwareTrait;
    use CompetenceThemeFormAwareTrait;

    public function indexAction() : ViewModel
    {
        $themes = $this->getCompetenceThemeService()->getCompetencesThemes();
        return new ViewModel([
            'themes' => $themes,
        ]);
    }


    public function afficherAction() : ViewModel
    {
        $theme = $this->getCompetenceThemeService()->getRequestedCompetenceTheme($this);
        return new ViewModel([
            'title' => "Affiche d'un thème de compétence",
            'theme' => $theme,
        ]);
    }

    public function ajouterAction() : ViewModel
    {
        $theme = new CompetenceTheme();
        $form = $this->getCompetenceThemeForm();
        $form->setAttribute('action', $this->url()->fromRoute('element/competence-theme/ajouter', [], [], true));
        $form->bind($theme);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCompetenceThemeService()->create($theme);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('element/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'un thème de compétences",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction() : ViewModel
    {
        $theme = $this->getCompetenceThemeService()->getRequestedCompetenceTheme($this);
        $form = $this->getCompetenceThemeForm();
        $form->setAttribute('action', $this->url()->fromRoute('element/competence-theme/modifier', ['competence-theme' => $theme->getId()], [], true));
        $form->bind($theme);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCompetenceThemeService()->update($theme);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('element/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'un thème de compétences",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction() : Response
    {
        $theme = $this->getCompetenceThemeService()->getRequestedCompetenceTheme($this);
        $this->getCompetenceThemeService()->historise($theme);
        return $this->redirect()->toRoute('element/competence-theme', [], [], true);
    }

    public function restaurerAction() : Response
    {
        $theme = $this->getCompetenceThemeService()->getRequestedCompetenceTheme($this);
        $this->getCompetenceThemeService()->restore($theme);
        return $this->redirect()->toRoute('element/competence-theme', [], [], true);
    }

    public function detruireAction() : ViewModel
    {
        $theme = $this->getCompetenceThemeService()->getRequestedCompetenceTheme($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getCompetenceThemeService()->delete($theme);
            exit();
        }

        $vm = new ViewModel();
        if ($theme !== null) {
            $vm->setTemplate('element/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de le thème de compétence  " . $theme->getLibelle(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('element/competence-theme/detruire', ["competence-theme" => $theme->getId()], [], true),
            ]);
        }
        return $vm;
    }
}