<?php

namespace Element\Controller;

use Application\Service\Agent\AgentServiceAwareTrait;
use DateTime;
use Element\Entity\Db\Application;
use Element\Entity\Db\ApplicationTheme;
use Element\Form\Application\ApplicationForm;
use Element\Form\Application\ApplicationFormAwareTrait;
use Element\Form\ApplicationElement\ApplicationElementFormAwareTrait;
use Element\Service\Application\ApplicationServiceAwareTrait;
use Element\Service\ApplicationElement\ApplicationElementServiceAwareTrait;
use Element\Service\ApplicationTheme\ApplicationThemeServiceAwareTrait;
use Element\Service\Niveau\NiveauServiceAwareTrait;
use FicheMetier\Service\FicheMetier\FicheMetierServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Metier\Service\Domaine\DomaineServiceAwareTrait;
use Metier\Service\Metier\MetierServiceAwareTrait;
use UnicaenApp\View\Model\CsvModel;

class ApplicationController  extends AbstractActionController {
    use ApplicationServiceAwareTrait;
    use ApplicationThemeServiceAwareTrait;
    use ApplicationElementServiceAwareTrait;
    use AgentServiceAwareTrait;
    use DomaineServiceAwareTrait;
    use FicheMetierServiceAwareTrait;
    use NiveauServiceAwareTrait;
    use MetierServiceAwareTrait;
    use ApplicationFormAwareTrait;
    use ApplicationElementFormAwareTrait;

    /** APPLICATION ***************************************************************************************************/

    public function indexAction() : ViewModel
    {
        $groupeId = $this->params()->fromQuery('groupe');
        $activite = $this->params()->fromQuery('activite');
        /**
         * @var Application[] $applications
         * @var ApplicationTheme[] $groupes
         */
        if ($groupeId !== null AND $groupeId !== "") {
            $groupe = $this->getApplicationThemeService()->getApplicationTheme($groupeId);
            $applications = $this->getApplicationService()->getApplicationsGyGroupe($groupe);
        } else {
            $applications = $this->getApplicationService()->getApplications();
        }
        $groupes = $this->getApplicationThemeService()->getApplicationsGroupes('libelle');

        if ($activite === "1") $applications = array_filter($applications, function (Application $a) { return $a->isActif();});
        if ($activite === "0") $applications = array_filter($applications, function (Application $a) { return !$a->isActif();});

        return new ViewModel([
            'applications' => $applications,
            'activite' => $activite,
            'groupes' => $groupes,
            'groupeSelected' => $groupeId,
        ]);
}

    public function creerAction() : ViewModel
    {
        /** @var Application $application */
        $application = new Application();

        /** @var ApplicationForm $form */
        $form = $this->getApplicationForm();
        $form->setAttribute('action', $this->url()->fromRoute('element/application/creer', [], [], true));
        $form->bind($application);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);

            if ($form->isValid()) {
                $this->getApplicationService()->create($application);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajouter une application',
            'form' => $form,
        ]);
        return $vm;
    }

    public function editerAction() : ViewModel
    {
        /** @var Application $application */
        $applicationId = $this->params()->fromRoute('id');
        $application = $this->getApplicationService()->getApplication($applicationId);

        /** @var ApplicationForm $form */
        $form = $this->getApplicationForm();
        $form->setAttribute('action', $this->url()->fromRoute('element/application/editer', ['id' => $application->getId()], [], true));
        $form->bind($application);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);

            if ($form->isValid()) {
                $this->getApplicationService()->update($application);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Modifier une application',
            'form' => $form,
        ]);
        return $vm;
    }

    public function effacerAction() : ViewModel
    {
        $application = $this->getApplicationService()->getRequestedApplication($this, 'id');

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getApplicationService()->delete($application);
            //return $this->redirect()->toRoute('home');
            exit();
        }

        $vm = new ViewModel();
        if ($application !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de l'application [" . $application->getLibelle(). "]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('element/application/effacer', ["id" => $application->getId()], [], true),
            ]);
        }
        return $vm;
    }

    public function changerStatusAction()
    {
        $application = $this->getApplicationService()->getRequestedApplication($this, 'id');

        $application->setActif( !$application->isActif() );

        $this->getApplicationService()->update($application);
        return $this->redirect()->toRoute('element/application');
    }

    public function afficherAction() : ViewModel
    {
        $application = $this->getApplicationService()->getRequestedApplication($this, 'id');

        return new ViewModel([
            'title' => "Description de l'application",
            'application' => $application,
        ]);
    }

    public function historiserAction() : Response
    {
        $application = $this->getApplicationService()->getRequestedApplication($this, 'id');
        $this->getApplicationService()->historise($application);
        return $this->redirect()->toRoute('element/application', [], [], true);
    }

    public function restaurerAction() : Response
    {
        $application = $this->getApplicationService()->getRequestedApplication($this, 'id');
        $this->getApplicationService()->restore($application);
        return $this->redirect()->toRoute('element/application', [], [], true);
    }

    /** GESTION DES COMPETENCES ELEMENTS ==> Faire CONTROLLER ? *******************************************************/

    public function cartographieAction() : ViewModel
    {
        $domaines = $this->getDomaineService()->getDomaines();
        $metiers = $this->getMetierService()->getMetiers();
        $applications = $this->getApplicationService()->getApplications();

        $link = [];

        foreach ($metiers as $metier) {
            if ($metier->estNonHistorise()) {
                foreach ($metier->getFichesMetiers() as $ficheMetier) {
                    if ($ficheMetier->estNonHistorise() AND $ficheMetier->getEtat()->getCode() === 'FICHE_METIER_OK') {
                        foreach ($ficheMetier->getApplicationListe() as $applicationElement) {
                            if ($applicationElement->estNonHistorise()) $link[$metier->getLibelle()][$applicationElement->getApplication()->getLibelle()] = 1;
                        }
                    }
                }
            }
        }

        return new ViewModel([
            'domaines' => $domaines,
            'metiers' => $metiers,
            'applications' => $applications,
            'link' => $link,
        ]);
    }

    public function exporterCartographieAction() : CsvModel
    {
        $metiers = $this->getMetierService()->getMetiers();
        $applications = $this->getApplicationService()->getApplications();

        $headers = [];
        $headers[] = 'Métier';
        foreach ($applications as $application) $headers[] = $application->getLibelle();

        $link = [];
        foreach ($metiers as $metier) {
            $link[$metier->getLibelle()][] = $metier->getLibelle();

            foreach ($applications as $application) {
                $res = 0;
                foreach ($metier->getFichesMetiers() as $ficheMetier) {
                    if ($ficheMetier->hasApplication($application)) {
                        $res = 1;
                        break;
                    }
                }
                $link[$metier->getLibelle()][] = $res;
            }
        }

        $date = (new DateTime())->format('Ymd-His');
        $filename="export_utilisateur_".$date.".csv";
        $CSV = new CsvModel();
        $CSV->setDelimiter(';');
        $CSV->setEnclosure('"');
        $CSV->setHeader($headers);
        $CSV->setData($link);
        $CSV->setFilename($filename);
        return $CSV;
    }
}