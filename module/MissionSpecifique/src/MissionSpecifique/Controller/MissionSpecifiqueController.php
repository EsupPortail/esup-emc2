<?php

namespace MissionSpecifique\Controller;

use Application\Form\ModifierLibelle\ModifierLibelleFormAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use MissionSpecifique\Entity\Db\MissionSpecifique;
use MissionSpecifique\Form\MissionSpecifique\MissionSpecifiqueFormAwareTrait;
use MissionSpecifique\Service\MissionSpecifique\MissionSpecifiqueServiceAwareTrait;
use MissionSpecifique\Service\MissionSpecifiqueTheme\MissionSpecifiqueThemeServiceAwareTrait;
use MissionSpecifique\Service\MissionSpecifiqueType\MissionSpecifiqueTypeServiceAwareTrait;

class MissionSpecifiqueController extends AbstractActionController
{
    use MissionSpecifiqueServiceAwareTrait;
    use MissionSpecifiqueThemeServiceAwareTrait;
    use MissionSpecifiqueTypeServiceAwareTrait;

    use MissionSpecifiqueFormAwareTrait;
    use ModifierLibelleFormAwareTrait;

    /** Partie gestion des missions spécifiques  **********************************************************************/

    public function indexAction(): ViewModel
    {
        $typeId = $this->params()->fromQuery('type');
        $type = $this->getMissionSpecifiqueTypeService()->getMissionSpecifiqueType(($typeId) ? ((int)$typeId) : null);
        $themeId = $this->params()->fromQuery('theme');
        $theme = $this->getMissionSpecifiqueThemeService()->getMissionSpecifiqueTheme(($themeId) ? ((int)$themeId) : null);

        $missions = $this->getMissionSpecifiqueService()->getMissionsSpecifiques();
        if ($type !== null) $missions = array_filter($missions, function (MissionSpecifique $mission) use ($type) {
            return $mission->getType() === $type;
        });
        if ($theme !== null) $missions = array_filter($missions, function (MissionSpecifique $mission) use ($theme) {
            return $mission->getTheme() === $theme;
        });
        $types = $this->getMissionSpecifiqueTypeService()->getMissionsSpecifiquesTypes();
        $themes = $this->getMissionSpecifiqueThemeService()->getMissionsSpecifiquesThemes();

        return new ViewModel([
            'missions' => $missions,
            'types' => $types,
            'themes' => $themes,
            'params' => ['type' => $typeId, 'theme' => $themeId],
        ]);
    }

    /** Missions */

    public function afficherAction(): ViewModel
    {
        $mission = $this->getMissionSpecifiqueService()->getRequestedMissionSpecifique($this);

        return new ViewModel([
            'title' => "Affichage d'une mission spécifique",
            'mission' => $mission,
        ]);
    }

    public function ajouterAction(): ViewModel
    {
        $mission = new MissionSpecifique();
        $form = $this->getMissionSpecifiqueForm();
        $form->setAttribute('action', $this->url()->fromRoute('mission-specifique/ajouter'));
        $form->bind($mission);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMissionSpecifiqueService()->create($mission);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'une mission spécifique",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction(): ViewModel
    {
        $mission = $this->getMissionSpecifiqueService()->getRequestedMissionSpecifique($this);
        $form = $this->getMissionSpecifiqueForm();
        $form->setAttribute('action', $this->url()->fromRoute('mission-specifique/modifier', ['mission' => $mission->getId()], [], true));
        $form->bind($mission);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMissionSpecifiqueService()->update($mission);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification de la mission spécifique",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction(): Response
    {
        $mission = $this->getMissionSpecifiqueService()->getRequestedMissionSpecifique($this);
        $this->getMissionSpecifiqueService()->historise($mission);
        return $this->redirect()->toRoute('mission-specifique', [], [], true);
    }

    public function restaurerAction(): Response
    {
        $mission = $this->getMissionSpecifiqueService()->getRequestedMissionSpecifique($this);
        $this->getMissionSpecifiqueService()->restore($mission);
        return $this->redirect()->toRoute('mission-specifique', [], [], true);
    }

    public function detruireAction(): ViewModel
    {
        $mission = $this->getMissionSpecifiqueService()->getRequestedMissionSpecifique($this);
        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getMissionSpecifiqueService()->delete($mission);
            exit();
        }

        $vm = new ViewModel();
        if ($mission !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la mission spécifique  " . $mission->getLibelle(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('mission-specifique/detruire', ["mission" => $mission->getId()], [], true),
            ]);
        }
        return $vm;
    }
}