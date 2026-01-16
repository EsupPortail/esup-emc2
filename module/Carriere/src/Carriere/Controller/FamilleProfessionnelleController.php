<?php

namespace Carriere\Controller;

use Carriere\Entity\Db\FamilleProfessionnelle;
use Carriere\Form\FamilleProfessionnelle\FamilleProfessionnelleFormAwareTrait;
use Carriere\Service\Correspondance\CorrespondanceServiceAwareTrait;
use Carriere\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;
use FicheMetier\Service\FicheMetier\FicheMetierServiceAwareTrait;
use FicheMetier\Service\MissionPrincipale\MissionPrincipaleServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class FamilleProfessionnelleController extends AbstractActionController
{
    use CorrespondanceServiceAwareTrait;
    use FamilleProfessionnelleServiceAwareTrait;
    use MissionPrincipaleServiceAwareTrait;
    use FamilleProfessionnelleFormAwareTrait;
    use FicheMetierServiceAwareTrait;

    public function indexAction(): ViewModel
    {
        $params = $this->params()->fromQuery();
        $familles = $this->getFamilleProfessionnelleService()->getFamillesProfessionnellesWithFilter($params);

        return new ViewModel([
            'familles' => $familles,
            'correspondances' => $this->getCorrespondanceService()->getCorrespondances(),
            'params' => $params
        ]);
    }

    public function afficherAction(): ViewModel
    {
        $famille = $this->getFamilleProfessionnelleService()->getRequestedFamilleProfessionnelle($this);

        $missions = $this->getMissionPrincipaleService()->getMissionsPrincipalesWithFiltre(['famille' => $famille->getId()]);
        $fichesmetiers = $this->getFicheMetierService()->getFichesMetiersWithFiltre(['famille' => $famille->getId()]);

        return new ViewModel([
            'title' => "Affichage de la famille professionnelle",
            'famille' => $famille,
            'missions' => $missions,
            'fichesmetiers' => $fichesmetiers,
        ]);
    }

    public function ajouterAction(): ViewModel
    {
        $famille = new FamilleProfessionnelle();

        $form = $this->getFamilleProfessionnelleForm();
        $form->setAttribute('action', $this->url()->fromRoute('famille-professionnelle/ajouter', [], [], true));
        $form->bind($famille);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFamilleProfessionnelleService()->create($famille);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/default-form');
        $vm->setVariables([
            'title' => 'Ajouter une nouvelle famille professionnelle',
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction(): ViewModel
    {
        $famille = $this->getFamilleProfessionnelleService()->getRequestedFamilleProfessionnelle($this);

        $form = $this->getFamilleProfessionnelleForm();
        $form->setAttribute('action', $this->url()->fromRoute('famille-professionnelle/modifier', ['famille-professionnelle' => $famille->getId()], [], true));
        $form->bind($famille);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFamilleProfessionnelleService()->update($famille);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/default-form');
        $vm->setVariables([
            'title' => 'Modifier une famille professionnelle',
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction(): Response
    {
        $famille = $this->getFamilleProfessionnelleService()->getRequestedFamilleProfessionnelle($this);

        if ($famille !== null) {
            $this->getFamilleProfessionnelleService()->historise($famille);
        }

        return $this->redirect()->toRoute('famille-professionnelle', [], [], true);
    }

    public function restaurerAction(): Response
    {
        $famille = $this->getFamilleProfessionnelleService()->getRequestedFamilleProfessionnelle($this);

        if ($famille !== null) {
            $this->getFamilleProfessionnelleService()->restore($famille);
        }

        return $this->redirect()->toRoute('famille-professionnelle', [], [], true);
    }

    public function supprimerAction(): ViewModel
    {
        $famille = $this->getFamilleProfessionnelleService()->getRequestedFamilleProfessionnelle($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getFamilleProfessionnelleService()->delete($famille);
            //return $this->redirect()->toRoute('home');
            exit();
        }

        $vm = new ViewModel();
        if ($famille !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la famille professionnelle" . $famille->getLibelle(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('famille-professionnelle/supprimer', ["famille-professionnelle" => $famille->getId()], [], true),
            ]);
        }
        return $vm;
    }

}