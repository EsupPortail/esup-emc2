<?php

namespace Application\Controller;

use Application\Entity\Db\Formation;
use Application\Entity\Db\FormationGroupe;
use Application\Entity\Db\FormationTheme;
use Application\Form\Formation\FormationFormAwareTrait;
use Application\Form\FormationGroupe\FormationGroupeFormAwareTrait;
use Application\Form\ModifierLibelle\ModifierLibelleFormAwareTrait;
use Application\Service\Formation\FormationGroupeServiceAwareTrait;
use Application\Service\Formation\FormationServiceAwareTrait;
use Application\Service\Formation\FormationThemeServiceAwareTrait;
use Application\Service\ParcoursDeFormation\ParcoursDeFormationServiceAwareTrait;
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

    /** INDEX *********************************************************************************************************/

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

    /** FOMRATION *****************************************************************************************************/

    public function afficherAction()
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);
        return new ViewModel([
            'title' => 'Affichage de la formation [' . $formation->getLibelle() . ']',
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

    /** FORMATION GROUPE **********************************************************************************************/

    public function afficherGroupeAction()
    {
        $groupe = $this->getFormationGroupeService()->getRequestedFormationGroupe($this);

        return new ViewModel([
            'title' => 'Affichage du groupe',
            'groupe' => $groupe,
        ]);
    }

    public function ajouterGroupeAction()
    {
        $groupe = new FormationGroupe();
        $form = $this->getFormationGroupeForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation-groupe/ajouter', [], [], true));
        $form->bind($groupe);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormationGroupeService()->create($groupe);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajouter un groupe de formation',
            'form' => $form,
        ]);
        return $vm;
    }

    public function editerGroupeAction()
    {
        $groupe = $this->getFormationGroupeService()->getRequestedFormationGroupe($this);
        $form = $this->getFormationGroupeForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation-groupe/editer', ['formation-groupe' => $groupe->getId()], [], true));
        $form->bind($groupe);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormationGroupeService()->update($groupe);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Modifier un groupe de formation',
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserGroupeAction()
    {
        $groupe = $this->getFormationGroupeService()->getRequestedFormationGroupe($this);
        $this->getFormationGroupeService()->historise($groupe);
        return $this->redirect()->toRoute('formation', [], ['fragment' => 'groupe'], true);
    }

    public function restaurerGroupeAction()
    {
        $groupe = $this->getFormationGroupeService()->getRequestedFormationGroupe($this);
        $this->getFormationGroupeService()->restore($groupe);
        return $this->redirect()->toRoute('formation', [], ['fragment' => 'groupe'], true);
    }

    public function detruireGroupeAction()
    {
        $groupe = $this->getFormationGroupeService()->getRequestedFormationGroupe($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getFormationGroupeService()->delete($groupe);
            exit();
        }

        $vm = new ViewModel();
        if ($groupe !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du groupe de formation [" . $groupe->getLibelle() . "]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('formation-groupe/detruire', ["formation-groupe" => $groupe->getId()], [], true),
            ]);
        }
        return $vm;
    }

    public function updateOrdreGroupeAction()
    {
        $ordre = explode("_",$this->params()->fromRoute('ordre'));
        $sort = [];
        $position = 1;
        foreach ($ordre as $item) {
            $sort[$item] = $position;
            $position++;
        }

        $groupes = $this->getFormationGroupeService()->getFormationsGroupes();
        foreach ($groupes as $groupe) {
            if (! isset($sort[$groupe->getId()]) AND $groupe->getOrdre() !== null) {
                $groupe->setOrdre(null);
                $this->getFormationGroupeService()->update($groupe);
            }
            if ($groupe->getOrdre() != $sort[$groupe->getId()]) {
                $groupe->setOrdre($sort[$groupe->getId()]);
                $this->getFormationGroupeService()->update($groupe);
            }
        }

        return new ViewModel();
    }
    /** FORMATION THEME ***********************************************************************************************/

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
        $form = $this->getModifierLibelleForm();
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
        $form = $this->getModifierLibelleForm();
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