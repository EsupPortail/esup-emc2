<?php

namespace Indicateur\Controller;

use DateTime;
use Indicateur\Entity\Db\Indicateur;
use Indicateur\Form\Indicateur\IndicateurFormAwareTrait;
use Indicateur\Service\Abonnement\AbonnementServiceAwareTrait;
use Indicateur\Service\Indicateur\IndicateurServiceAwareTrait;
use UnicaenApp\View\Model\CsvModel;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class IndicateurController extends AbstractActionController {
    use IndicateurServiceAwareTrait;
    use UserServiceAwareTrait;
    use AbonnementServiceAwareTrait;

    use IndicateurFormAwareTrait;

    public function indexAction()
    {
        $indicateurs = $this->getIndicateurService()->getIndicateurs();
        $user = $this->getUserService()->getConnectedUser();
        $abonnements = $this->getAbonnementService()->getAbonnementsByUser($user);

        $array = [];
        foreach ($abonnements as $abonnement) {
            $array [$abonnement->getIndicateur()->getId()] = $abonnement;
        }

        return new ViewModel([
            'indicateurs' => $indicateurs,
            'abonnements' => $array,
        ]);
    }

    public function afficherAction() : ViewModel
    {
        $indicateur = $this->getIndicateurService()->getRequestedIndicateur($this);

        $exists = $this->getIndicateurService()->verifierExistanceMaterializedView($indicateur->getViewId());
        if ($exists === true) $result = $this->getIndicateurService()->getIndicateurData($indicateur);

        return new ViewModel([
            'indicateur' => $indicateur,
            'exists' => $exists,
            'header' => ($exists)?$result[0]:null,
            'data' =>   ($exists)?$result[1]:null,
        ]);
    }

    public function rafraichirAction()
    {
        $indicateur = $this->getIndicateurService()->getRequestedIndicateur($this);
        $this->getIndicateurService()->refresh($indicateur);
        return $this->redirect()->toRoute('indicateur/afficher', ['indicateur' => $indicateur->getId()], [], true);
    }

    public function creerAction()
    {
        $indicateur = new Indicateur();
        $form = $this->getIndicateurForm();
        $form->setAttribute('action', $this->url()->fromRoute('indicateur/creer', [], [], true));
        $form->bind($indicateur);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getIndicateurService()->create($indicateur);
                $this->getIndicateurService()->createView($indicateur);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('indicateur/default/default-form');
        $vm->setVariables([
            'title' => 'Création d\'un nouvel indicateur',
            'form'  => $form,
        ]);
        return $vm;
    }

    public function modifierAction()
    {
        $indicateur = $this->getIndicateurService()->getRequestedIndicateur($this);
        $form = $this->getIndicateurForm();
        $form->setAttribute('action', $this->url()->fromRoute('indicateur/modifier', [], [], true));
        $form->bind($indicateur);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getIndicateurService()->update($indicateur);
                $this->getIndicateurService()->updateView($indicateur);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('indicateur/default/default-form');
        $vm->setVariables([
            'title' => 'Modification d\'un indicateur',
            'form'  => $form,
        ]);
        return $vm;
    }

    public function detruireAction()
    {
        $indicateur = $this->getIndicateurService()->getRequestedIndicateur($this);
        $this->getIndicateurService()->dropView($indicateur);
        $this->getIndicateurService()->delete($indicateur);
        return $this->redirect()->toRoute('indicateurs', [], [], true);
    }

    public function exporterAction()
    {
        $indicateur = $this->getIndicateurService()->getRequestedIndicateur($this);
        $date = new DateTime();
        $filename = "preecog_".$indicateur->getViewId()."_".$date->format('Ymd-Hms').".csv";

        $result = $this->getIndicateurService()->getIndicateurData($indicateur);

        $CSV = new CsvModel();
        $CSV->setDelimiter(';');
        $CSV->setEnclosure('"');
        $CSV->setHeader($result[0]);
        $CSV->setData($result[1]);
        $CSV->setFilename($filename);

        return $CSV;
    }

    public function rafraichirTousAction()
    {
        $indicateurs = $this->getIndicateurService()->getIndicateurs();
        foreach ($indicateurs as $indicateur) {
            $this->getIndicateurService()->refresh($indicateur);
        }
        exit();
    }

    public function rafraichirConsoleAction() {
        $indicateurs = $this->getIndicateurService()->getIndicateurs();
        foreach ($indicateurs as $indicateur) {
            $this->getIndicateurService()->refresh($indicateur);
        }
    }
}