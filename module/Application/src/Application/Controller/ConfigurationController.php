<?php

namespace Application\Controller;

use Element\Entity\Db\Application;
use Element\Entity\Db\Competence;
use Application\Entity\Db\ConfigurationFicheMetier;
use Formation\Entity\Db\Formation;
use Application\Form\ConfigurationFicheMetier\ConfigurationFicheMetierFormAwareTrait;
use Element\Service\Application\ApplicationServiceAwareTrait;
use Element\Service\Competence\CompetenceServiceAwareTrait;
use Application\Service\Configuration\ConfigurationServiceAwareTrait;
use Application\Service\FicheMetier\FicheMetierServiceAwareTrait;
use Formation\Service\Formation\FormationServiceAwareTrait;
use UnicaenAutoform\Service\Formulaire\FormulaireServiceAwareTrait;
use Zend\Form\Element\Select;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Zend\View\Model\ViewModel;

/** @method FlashMessenger flashMessenger() */

class ConfigurationController extends AbstractActionController  {
    use ConfigurationServiceAwareTrait;
    use ApplicationServiceAwareTrait;
    use CompetenceServiceAwareTrait;
    use FormationServiceAwareTrait;
    use FicheMetierServiceAwareTrait;
    use FormulaireServiceAwareTrait;
    use ConfigurationFicheMetierFormAwareTrait;


    public function indexAction()
    {
        $applications = $this->getConfigurationService()->getConfigurationsFicheMetier(Application::class);
        $competences  = $this->getConfigurationService()->getConfigurationsFicheMetier(Competence::class);
        $formations   = $this->getConfigurationService()->getConfigurationsFicheMetier(Formation::class);

        $recopies = $this->getConfigurationService()->getConfigurationsEntretienProfessionnel();
        $formulaire = $this->getFormulaireService()->getFormulaire(1);
        $champs = [];
        foreach ($formulaire->getCategories() as $categorie) {
            foreach ($categorie->getChamps() as $champ) {
                $champs[$champ->getId()] = $champ;
            }
        }

        return new ViewModel([
            'applications' => $applications,
            'competences' => $competences,
            'formations' => $formations,

            'recopies' => $recopies,
            'champs' => $champs,
        ]);
    }

    public function ajouterConfigurationFicheMetierAction()
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
            case 'formation' :
                $select = $this->getFormationService()->getFormationsAsOptions();
                $type = Formation::class;
                break;
        }
        $configuration = new ConfigurationFicheMetier();

        $form = $this->getConfigurationFicheMetierForm();
        $form->setAttribute('action', $this->url()->fromRoute('configuration/ajouter-configuration-fiche-metier', ['type' => $entity], [], true));
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
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajouter une ". $entity." par défaut",
            'form' => $form,
        ]);
        return $vm;
    }

    public function detruireConfigurationFicheMetierAction()
    {
        $configuration = $this->getConfigurationService()->getRequestedConfigurationFicheMetier($this);
        $this->getConfigurationService()->delete($configuration);
        return $this->redirect()->toRoute('configuration', [], [],true);
    }

    public function reappliquerConfigurationFicheMetierAction()
    {
        $fiches = $this->getFicheMetierService()->getFichesMetiers();
        foreach ($fiches as $fiche) {
            $fiche = $this->getConfigurationService()->addDefaultToFicheMetier($fiche);
            $this->getFicheMetierService()->update($fiche);
        }
        $this->flashMessenger()->addSuccessMessage("Ré-application terminée");
        return $this->redirect()->toRoute('configuration', [], [],true);

    }
}
