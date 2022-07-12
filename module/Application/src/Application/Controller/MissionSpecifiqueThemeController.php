<?php

namespace Application\Controller;

use Application\Entity\Db\MissionSpecifiqueTheme;
use Application\Form\ModifierLibelle\ModifierLibelleFormAwareTrait;
use Application\Service\MissionSpecifiqueTheme\MissionSpecifiqueThemeServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class MissionSpecifiqueThemeController extends AbstractActionController {
    use MissionSpecifiqueThemeServiceAwareTrait;
    use ModifierLibelleFormAwareTrait;

    public function indexAction() : ViewModel
    {
        $themes = $this->getMissionSpecifiqueThemeService()->getMissionsSpecifiquesThemes();

        return new ViewModel([
            "themes" => $themes,
        ]);
    }

    public function afficherAction() : ViewModel
    {
        $theme = $this->getMissionSpecifiqueThemeService()->getRequestedMissionSpecifiqueTheme($this);

        return new ViewModel([
            'title' => "Affichage d'un thème de mission spécifique",
            'theme' => $theme,
        ]);
    }

    public function ajouterAction() : ViewModel
    {
        $theme = new MissionSpecifiqueTheme();
        $form = $this->getModifierLibelleForm();
        $form->setAttribute('action', $this->url()->fromRoute('mission-specifique-theme/ajouter'));
        $form->bind($theme);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMissionSpecifiqueThemeService()->create($theme);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'un thème de mission spécifique",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction() : ViewModel
    {
        $theme = $this->getMissionSpecifiqueThemeService()->getRequestedMissionSpecifiqueTheme($this);
        $form = $this->getModifierLibelleForm();
        $form->setAttribute('action', $this->url()->fromRoute('mission-specifique-theme/modifier', ['theme' => $theme->getId()], [], true));
        $form->bind($theme);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMissionSpecifiqueThemeService()->update($theme);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'un thème de mission spécifique",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction() : Response
    {
        $theme = $this->getMissionSpecifiqueThemeService()->getRequestedMissionSpecifiqueTheme($this);
        $this->getMissionSpecifiqueThemeService()->historise($theme);
        return $this->redirect()->toRoute('mission-specifique-theme', [], [], true);
    }

    public function restaurerAction() : Response
    {
        $theme = $this->getMissionSpecifiqueThemeService()->getRequestedMissionSpecifiqueTheme($this);
        $this->getMissionSpecifiqueThemeService()->restore($theme);
        return $this->redirect()->toRoute('mission-specifique-theme', [], [], true);
    }

    public function detruireAction() : ViewModel
    {
        $theme = $this->getMissionSpecifiqueThemeService()->getRequestedMissionSpecifiqueTheme($this);
        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getMissionSpecifiqueThemeService()->delete($theme);
            exit();
        }

        $vm = new ViewModel();
        if ($theme !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du thème de mission spécifique  " . $theme->getLibelle(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('mission-specifique-theme/detruire', ["type" => $theme->getId()], [], true),
            ]);
        }
        return $vm;
    }
}