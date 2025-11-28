<?php

namespace FicheMetier\Controller;

use Application\Entity\Db\ConfigurationFicheMetier;
use Application\Form\ConfigurationFicheMetier\ConfigurationFicheMetierFormAwareTrait;
use Application\Service\Configuration\ConfigurationServiceAwareTrait;
use Element\Entity\Db\Application;
use Element\Entity\Db\Competence;
use Element\Service\Application\ApplicationServiceAwareTrait;
use Element\Service\Competence\CompetenceServiceAwareTrait;
use FicheMetier\Provider\Parametre\FicheMetierParametres;
use FicheMetier\Service\FicheMetier\FicheMetierServiceAwareTrait;
use Laminas\Form\Element\Select;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\View\Model\ViewModel;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;

/** @method FlashMessenger flashMessenger() */

class ConfigurationController extends AbstractActionController
{
    use ApplicationServiceAwareTrait;
    use CompetenceServiceAwareTrait;
    use ConfigurationServiceAwareTrait; //todo à deplacer
    use FicheMetierServiceAwareTrait;
    use ParametreServiceAwareTrait;

    use ConfigurationFicheMetierFormAwareTrait;

    public function competenceAction() : ViewModel
    {
        $defaultCompetence =  $this->getConfigurationService()->getConfigurationsFicheMetier(Competence::class);
        $parametres        =  $this->getParametreService()->getParametresByCategorieCode(FicheMetierParametres::TYPE);

        return new ViewModel([
            'defaultCompetence' => $defaultCompetence,
            'parametres' => $parametres,
        ]);
    }

    public function applicationAction() : ViewModel
    {
        $defaultApplication =  $this->getConfigurationService()->getConfigurationsFicheMetier(Application::class);
        $parametres        =  $this->getParametreService()->getParametresByCategorieCode(FicheMetierParametres::TYPE);

        return new ViewModel([
            'defaultApplication' => $defaultApplication,
            'parametres' => $parametres,
        ]);
    }

    public function ajouterAction()
    {
        $entity = $this->params()->fromRoute('type');
        $select = [];
        $type = "none";

        switch ($entity) {
            case  'application' :
                $select = $this->getApplicationService()->getApplicationsAsOptions();
                $type = Application::class;
                break;
            case 'competence' :
                $select = $this->getCompetenceService()->getCompetencesAsGroupOptions();
                $type = Competence::class;
                break;
        }
        $configuration = new ConfigurationFicheMetier();

        $form = $this->getConfigurationFicheMetierForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier/configuration/ajouter', ['type' => $entity], [], true));
        $form->bind($configuration);
        $form->get('operation')->setValue('ajout');
        $form->get('type')->setValue($type);
        /** @var Select $selectElement */
        $selectElement = $form->get('select');
        $selectElement->setValueOptions($select);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getConfigurationService()->create($configuration);
                exit();
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/default-form');
        $vm->setVariables([
            'title' => "Ajouter une ". $entity." par défaut",
            'form' => $form,
        ]);
        return $vm;
    }

    public function retirerAction(): Response
    {
        $configuration = $this->getConfigurationService()->getRequestedConfigurationFicheMetier($this, 'id');
        $this->getConfigurationService()->delete($configuration);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        /** @see ConfigurationController::indexAction() */
        return $this->redirect()->toRoute('fiche-metier/configuration', [], [],true);
    }

    public function reappliquerAction(): Response
    {
        $entity = $this->params()->fromRoute('type');
        $type = null;

        switch ($entity) {
            case  'application' :
                $type = Application::class;
                break;
            case 'competence' :
                $type = Competence::class;
                break;
        }

        $fiches = $this->getFicheMetierService()->getFichesMetiers();
        foreach ($fiches as $fiche) {
            $fiche = $this->getConfigurationService()->addDefaultToFicheMetier($fiche, $type);
            $this->getFicheMetierService()->update($fiche);
        }
        $this->flashMessenger()->addSuccessMessage("Ré-application terminée");
        /** @see ConfigurationController::indexAction() */
        return $this->redirect()->toRoute('fiche-metier/configuration', [], [],true);
    }

}
