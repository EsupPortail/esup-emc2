<?php

namespace Application\Controller;

use Application\Entity\Db\Competence;
use Application\Entity\Db\CompetenceTheme;
use Application\Entity\Db\CompetenceType;
use Application\Form\Competence\CompetenceFormAwareTrait;
use Application\Form\CompetenceTheme\CompetenceThemeFormAwareTrait;
use Application\Form\CompetenceType\CompetenceTypeFormAwareTrait;
use Application\Service\Competence\CompetenceServiceAwareTrait;
use Application\Service\CompetenceTheme\CompetenceThemeServiceAwareTrait;
use Application\Service\CompetenceType\CompetenceTypeServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CompetenceController extends AbstractActionController {
    use CompetenceServiceAwareTrait;
    use CompetenceThemeServiceAwareTrait;
    use CompetenceTypeServiceAwareTrait;
    use CompetenceFormAwareTrait;
    use CompetenceThemeFormAwareTrait;
    use CompetenceTypeFormAwareTrait;

    public function indexAction()
    {
        $competencesByType = [];
        $types = $this->getCompetenceTypeService()->getCompetencesTypes('ordre');
        $themes = $this->getCompetenceThemeService()->getCompetencesThemes();
        $competences = $this->getCompetenceService()->getCompetences();


        foreach ($types as $type) {
            $competences = $this->getCompetenceService()->getCompetencesByType($type);
            $competencesByType[] = [
                'type' => $type,
                'competences' => $competences,
            ];
        }
        $competencesByType[] = [
            'type' => null,
            'competences' => $this->getCompetenceService()->getCompetencesSansType(),
        ];

        return new ViewModel([
            'competencesByType' => $competencesByType,
            'themes' => $themes,
            'types' => $types,
        ]);
    }

    /** COMPETENCE ****************************************************************************************************/

    public function ajouterAction()
    {
        $type = $this->getCompetenceTypeService()->getRequestedCompetenceType($this);
        $competence = new Competence();
        if ($type !== null) $competence->setType($type);
        $form = $this->getCompetenceForm();
        $form->setAttribute('action', $this->url()->fromRoute('competence/ajouter', [], [], true));
        $form->bind($competence);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()){
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCompetenceService()->create($competence);
            }
        }

        $vm =  new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'une compétence",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction()
    {
        $competence = $this->getCompetenceService()->getRequestedCompetence($this);
        $form = $this->getCompetenceForm();
        $form->setAttribute('action', $this->url()->fromRoute('competence/modifier', ['competence' => $competence->getId()], [], true));
        $form->bind($competence);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()){
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCompetenceService()->update($competence);
            }
        }

        $vm =  new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'une compétence",
            'form' => $form,
        ]);
        return $vm;
    }

    public function afficherAction()
    {
        $competence = $this->getCompetenceService()->getRequestedCompetence($this);
        return new ViewModel([
            'title' => "Affiche d'une compétence",
            'competence' => $competence,
        ]);
    }

    public function historiserAction()
    {
        $competence = $this->getCompetenceService()->getRequestedCompetence($this);
        $this->getCompetenceService()->historise($competence);
        return $this->redirect()->toRoute('competence', [], [], true);
    }

    public function restaurerAction()
    {
        $competence = $this->getCompetenceService()->getRequestedCompetence($this);
        $this->getCompetenceService()->restore($competence);
        return $this->redirect()->toRoute('competence', [], [], true);
    }

    public function detruireAction()
    {
        $competence = $this->getCompetenceService()->getRequestedCompetence($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getCompetenceService()->delete($competence);
            //return $this->redirect()->toRoute('competence', [], [], true);
            exit();
        }

        $vm = new ViewModel();
        if ($competence !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la compétence  " . $competence->getLibelle(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('competence/detruire', ["competence" => $competence->getId()], [], true),
            ]);
        }
        return $vm;
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
                $this->getCompetenceThemeService()->create($theme);
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
        $theme = $this->getCompetenceThemeService()->getRequestedCompetenceTheme($this);
        $form = $this->getCompetenceThemeForm();
        $form->setAttribute('action', $this->url()->fromRoute('competence-theme/modifier', ['competence-theme' => $theme->getId()], [], true));
        $form->bind($theme);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()){
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCompetenceThemeService()->update($theme);
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
        $theme = $this->getCompetenceThemeService()->getRequestedCompetenceTheme($this);
        return new ViewModel([
            'title' => "Affiche d'un thème de compétence",
            'theme' => $theme,
        ]);
    }

    public function historiserCompetenceThemeAction()
    {
        $theme = $this->getCompetenceThemeService()->getRequestedCompetenceTheme($this);
        $this->getCompetenceThemeService()->historise($theme);
        return $this->redirect()->toRoute('competence', [], [], true);
    }

    public function restaurerCompetenceThemeAction()
    {
        $theme = $this->getCompetenceThemeService()->getRequestedCompetenceTheme($this);
        $this->getCompetenceThemeService()->restore($theme);
        return $this->redirect()->toRoute('competence', [], [], true);
    }

    public function detruireCompetenceThemeAction()
    {
        $theme = $this->getCompetenceThemeService()->getRequestedCompetenceTheme($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getCompetenceThemeService()->delete($theme);
            //return $this->redirect()->toRoute('competence', [], [], true);
            exit();
        }

        $vm = new ViewModel();
        if ($theme !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de le thème de compétence  " . $theme->getLibelle(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('competence-theme/detruire', ["competence-theme" => $theme->getId()], [], true),
            ]);
        }
        return $vm;
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
                $this->getCompetenceTypeService()->create($type);
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
        $type = $this->getCompetenceTypeService()->getRequestedCompetenceType($this);
        $form = $this->getCompetenceTypeForm();
        $form->setAttribute('action', $this->url()->fromRoute('competence-type/modifier', ['competence-type' => $type->getId()], [], true));
        $form->bind($type);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()){
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCompetenceTypeService()->update($type);
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
        $type = $this->getCompetenceTypeService()->getRequestedCompetenceType($this);
        return new ViewModel([
            'title' => "Affiche d'un type de compétence",
            'type' => $type,
        ]);
    }

    public function historiserCompetenceTypeAction()
    {
        $type = $this->getCompetenceTypeService()->getRequestedCompetenceType($this);
        $this->getCompetenceTypeService()->historise($type);
        return $this->redirect()->toRoute('competence', [], [], true);
    }

    public function restaurerCompetenceTypeAction()
    {
        $type = $this->getCompetenceTypeService()->getRequestedCompetenceType($this);
        $this->getCompetenceTypeService()->restore($type);
        return $this->redirect()->toRoute('competence', [], [], true);
    }

    public function detruireCompetenceTypeAction()
    {
        $type = $this->getCompetenceTypeService()->getRequestedCompetenceType($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getCompetenceTypeService()->delete($type);
            //return $this->redirect()->toRoute('competence', [], [], true);
            exit();
        }

        $vm = new ViewModel();
        if ($type !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de le type de compétence  " . $type->getLibelle(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('competence-type/detruire', ["competence-type" => $type->getId()], [], true),
            ]);
        }
        return $vm;
    }
}

