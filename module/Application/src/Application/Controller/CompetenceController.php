<?php

namespace Application\Controller;

use Application\Entity\Db\CompetenceTheme;
use Application\Entity\Db\CompetenceType;
use Application\Form\CompetenceTheme\CompetenceThemeFormAwareTrait;
use Application\Form\CompetenceType\CompetenceTypeFormAwareTrait;
use Application\Service\Competence\CompetenceServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CompetenceController extends AbstractActionController {
    use CompetenceServiceAwareTrait;
    use CompetenceThemeFormAwareTrait;
    use CompetenceTypeFormAwareTrait;

    public function indexAction()
    {
        $competences = $this->getCompetenceService()->getCompetences();
        $themes = $this->getCompetenceService()->getCompetencesThemes();
        $types = $this->getCompetenceService()->getCompetencesTypes();
        return new ViewModel([
            'competences' => $competences,
            'themes' => $themes,
            'types' => $types,
        ]);
    }

    /** COMPETENCE THEME **********************************************************************************************/

    public function ajouterCompetenceThemeAction()
    {
        $theme = new CompetenceTheme();
        $form = $this->getCompetenceThemeForm();
        $form->setAttribute('action', $this->url()->fromRoute('competence-theme/ajouter', [], [], true));
        $form->bind($theme);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()){
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCompetenceService()->createTheme($theme);
            }
        }

        $vm =  new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'un thème de compétences",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierCompetenceThemeAction()
    {
        $theme = $this->getCompetenceService()->getRequestedCompetenceTheme($this);
        $form = $this->getCompetenceThemeForm();
        $form->setAttribute('action', $this->url()->fromRoute('competence-theme/modifier', ['competence-theme' => $theme->getId()], [], true));
        $form->bind($theme);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()){
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCompetenceService()->updateTheme($theme);
            }
        }

        $vm =  new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'un thème de compétences",
            'form' => $form,
        ]);
        return $vm;
    }

    public function afficherCompetenceThemeAction()
    {
        $theme = $this->getCompetenceService()->getRequestedCompetenceTheme($this);
        return new ViewModel([
            'title' => "Affiche d'un thème de compétence",
            'theme' => $theme,
        ]);
    }

    public function historiserCompetenceThemeAction()
    {
        $theme = $this->getCompetenceService()->getRequestedCompetenceTheme($this);
        $this->getCompetenceService()->historiseTheme($theme);
        return $this->redirect()->toRoute('competence', [], [], true);
    }

    public function restaurerCompetenceThemeAction()
    {
        $theme = $this->getCompetenceService()->getRequestedCompetenceTheme($this);
        $this->getCompetenceService()->restoreTheme($theme);
        return $this->redirect()->toRoute('competence', [], [], true);
    }

    public function detruireCompetenceThemeAction()
    {
        $theme = $this->getCompetenceService()->getRequestedCompetenceTheme($this);
        $this->getCompetenceService()->deleteTheme($theme);
        return $this->redirect()->toRoute('competence', [], [], true);
    }

    /** COMPETENCE TYPE ***********************************************************************************************/

    public function ajouterCompetenceTypeAction()
    {
        $type = new CompetenceType();
        $form = $this->getCompetenceTypeForm();
        $form->setAttribute('action', $this->url()->fromRoute('competence-type/ajouter', [], [], true));
        $form->bind($type);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()){
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCompetenceService()->createType($type);
            }
        }

        $vm =  new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'un type de compétences",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierCompetenceTypeAction()
    {
        $type = $this->getCompetenceService()->getRequestedCompetenceType($this);
        $form = $this->getCompetenceTypeForm();
        $form->setAttribute('action', $this->url()->fromRoute('competence-type/modifier', ['competence-type' => $type->getId()], [], true));
        $form->bind($type);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()){
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCompetenceService()->updateType($type);
            }
        }

        $vm =  new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'un type de compétences",
            'form' => $form,
        ]);
        return $vm;
    }

    public function afficherCompetenceTypeAction()
    {
        $type = $this->getCompetenceService()->getRequestedCompetenceType($this);
        return new ViewModel([
            'title' => "Affiche d'un type de compétence",
            'type' => $type,
        ]);
    }

    public function historiserCompetenceTypeAction()
    {
        $type = $this->getCompetenceService()->getRequestedCompetenceType($this);
        $this->getCompetenceService()->historiseType($type);
        return $this->redirect()->toRoute('competence', [], [], true);
    }

    public function restaurerCompetenceTypeAction()
    {
        $type = $this->getCompetenceService()->getRequestedCompetenceType($this);
        $this->getCompetenceService()->restoreType($type);
        return $this->redirect()->toRoute('competence', [], [], true);
    }

    public function detruireCompetenceTypeAction()
    {
        $type = $this->getCompetenceService()->getRequestedCompetenceType($this);
        $this->getCompetenceService()->deleteType($type);
        return $this->redirect()->toRoute('competence', [], [], true);
    }
}

