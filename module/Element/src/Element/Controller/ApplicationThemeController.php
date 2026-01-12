<?php

namespace Element\Controller;

use Element\Form\ApplicationTheme\ApplicationThemeFormAwareTrait;
use Element\Service\ApplicationTheme\ApplicationThemeServiceAwareTrait;
use Element\Entity\Db\ApplicationTheme;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class ApplicationThemeController extends AbstractActionController {
    use ApplicationThemeServiceAwareTrait;
    use ApplicationThemeFormAwareTrait;

    public function indexAction() : ViewModel
    {
        $themes = $this->getApplicationThemeService()->getApplicationsThemes();

        return new ViewModel([
            'themes' => $themes,
        ]);
    }

    public function afficherAction() : ViewModel
    {
        $groupe = $this->getApplicationThemeService()->getRequestedApplicationTheme($this);

        return new ViewModel([
            'title' => "Affichage du groupe d'application",
            'groupe' => $groupe,
        ]);
    }

    public function ajouterAction() : ViewModel
    {
        $groupe = new ApplicationTheme();

        $form = $this->getApplicationThemeForm();
        $form->setAttribute('action', $this->url()->fromRoute('element/application-theme/ajouter',[],[], true));
        $form->bind($groupe);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getApplicationThemeService()->create($groupe);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'un thème d'application",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction() : ViewModel
    {
        $groupe = $this->getApplicationThemeService()->getRequestedApplicationTheme($this);

        $form = $this->getApplicationThemeForm();
        $form->setAttribute('action', $this->url()->fromRoute('element/application-theme/editer',[],[], true));
        $form->bind($groupe);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getApplicationThemeService()->update($groupe);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/default-form');
        $vm->setVariables([
            'title' => "Modification d'un thème d'application",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction() : Response
    {
        $groupe = $this->getApplicationThemeService()->getRequestedApplicationTheme($this);
        $this->getApplicationThemeService()->historise($groupe);
        return $this->redirect()->toRoute('element/application-theme', [], [], true);
    }

    public function restaurerAction() : Response
    {
        $groupe = $this->getApplicationThemeService()->getRequestedApplicationTheme($this);
        $this->getApplicationThemeService()->restore($groupe);
        return $this->redirect()->toRoute('element/application-theme', [], [], true);
    }

    public function detruireAction() : ViewModel
    {
        $groupe = $this->getApplicationThemeService()->getRequestedApplicationTheme($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getApplicationThemeService()->delete($groupe);
            exit();
        }

        $vm = new ViewModel();
        if ($groupe !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du groupe d'application [" . $groupe->getLibelle(). "]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('element/application-theme/detruire', ["application-groupe" => $groupe->getId()], [], true),
            ]);
        }
        return $vm;
    }

}