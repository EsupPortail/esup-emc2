<?php

namespace Metier\Controller;

use Laminas\Http\Header\Accept\FieldValuePart\AbstractFieldValuePart;
use Laminas\View\Model\JsonModel;
use Metier\Entity\Db\Domaine;
use Metier\Form\Domaine\DomaineFormAwareTrait;
use Metier\Service\Domaine\DomaineServiceAwareTrait;
use Metier\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Metier\View\Helper\TypeFonctionViewHelper;

class DomaineController extends AbstractActionController {
    use DomaineServiceAwareTrait;
    use FamilleProfessionnelleServiceAwareTrait;
    use DomaineFormAwareTrait;

    public function indexAction() : ViewModel
    {
        $type = $this->params()->fromQuery('type');
        $historise = $this->params()->fromQuery('historise');

        $domaines = $this->getDomaineService()->getDomaines();
        if ($type !== null and $type !== '') $domaines = array_filter($domaines, function (Domaine $m) use ($type) { return $m->getTypeFonction() === $type; });
        if ($historise !== null and $historise !== '') $domaines = array_filter($domaines, function (Domaine $m) use ($historise) { if ($historise === '1') return $m->estHistorise(); else return $m->estNonHistorise(); });

        return new ViewModel([
            'domaines' => $domaines,
            'types' => ['Soutien', 'Support'],
            'type' => $type,
            'historise' => $historise,
        ]);
    }

    public function afficherAction() : ViewModel
    {
        $domaine = $this->getDomaineService()->getRequestedDomaine($this);

        return new ViewModel([
            'title' => "Affichage du domaine",
            'domaine' => $domaine,

        ]);
    }

    public function ajouterAction() : ViewModel
    {
        $domaine = new Domaine();

        $form = $this->getDomaineForm();
        $form->setAttribute('action', $this->url()->fromRoute('domaine/ajouter', [], [], true));
        $form->bind($domaine);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getDomaineService()->create($domaine);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/default-form');
        $vm->setVariables([
            'title' => 'Ajouter un domaine',
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction() : ViewModel
    {
        $domaine = $this->getDomaineService()->getRequestedDomaine($this);

        $form = $this->getDomaineForm();
        $form->setAttribute('action', $this->url()->fromRoute('domaine/modifier', ['domaine' => $domaine->getId()], [], true));
        $form->bind($domaine);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getDomaineService()->update($domaine);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/default-form');
        $vm->setVariables([
            'title' => 'Modifier un domaine',
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction() : Response
    {
        $domaine = $this->getDomaineService()->getRequestedDomaine($this);
        if ($domaine !== null) {
            $this->getDomaineService()->historise($domaine);
        }
        return $this->redirect()->toRoute('domaine', [], [], true);
    }

    public function restaurerAction() : Response
    {
        $domaine = $this->getDomaineService()->getRequestedDomaine($this);
        if ($domaine !== null) {
            $this->getDomaineService()->restore($domaine);
        }
        return $this->redirect()->toRoute('domaine', [], [], true);
    }

    public function supprimerAction() : ViewModel
    {
        $domaine = $this->getDomaineService()->getRequestedDomaine($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getDomaineService()->delete($domaine);
            exit();
        }

        $vm = new ViewModel();
        if ($domaine !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du domaine " . $domaine->getLibelle(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('domaine/supprimer', ["domaine" => $domaine->getId()], [], true),
            ]);
        }
        return $vm;
    }

    public function domainesAsJsonAction() : JsonModel
    {
        $domaines = $this->getDomaineService()->getDomainesAsJson(true);
        return new JsonModel($domaines);
    }
}