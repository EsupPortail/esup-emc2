<?php

namespace Application\Controller;

use Application\Entity\Db\ConfigurationFicheMetier;
use Application\Form\ConfigurationFicheMetier\ConfigurationFicheMetierFormAwareTrait;
use Application\Service\Configuration\ConfigurationServiceAwareTrait;
use Element\Entity\Db\Application;
use Element\Entity\Db\Competence;
use Element\Service\Application\ApplicationServiceAwareTrait;
use Element\Service\Competence\CompetenceServiceAwareTrait;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use FicheMetier\Service\FicheMetier\FicheMetierServiceAwareTrait;
use Laminas\Form\Element\Select;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\View\Model\ViewModel;
use UnicaenAutoform\Service\Formulaire\FormulaireServiceAwareTrait;

/** @method FlashMessenger flashMessenger() */

class ConfigurationController extends AbstractActionController  {
    use ConfigurationServiceAwareTrait;
    use ApplicationServiceAwareTrait;
    use CompetenceServiceAwareTrait;
    use FicheMetierServiceAwareTrait;
    use FormulaireServiceAwareTrait;
    use ConfigurationFicheMetierFormAwareTrait;


    public function indexAction(): ViewModel
    {
        $applications = $this->getConfigurationService()->getConfigurationsFicheMetier(Application::class);
        $competences  = $this->getConfigurationService()->getConfigurationsFicheMetier(Competence::class);

        $recopies = $this->getConfigurationService()->getConfigurationsEntretienProfessionnel();
        $formulaire_crep = $this->getFormulaireService()->getFormulaireByCode(EntretienProfessionnel::FORMULAIRE_CREP);
        $champs_crep = [];
        foreach ($formulaire_crep->getCategories() as $categorie) {
            foreach ($categorie->getChamps() as $champ) {
                $champs_crep[$champ->getId()] = $champ;
            }
        }

        $formulaire_cref = $this->getFormulaireService()->getFormulaireByCode(EntretienProfessionnel::FORMULAIRE_CREF);
        $champs_cref = [];
        foreach ($formulaire_cref->getCategories() as $categorie) {
            foreach ($categorie->getChamps() as $champ) {
                $champs_cref[$champ->getId()] = $champ;
            }
        }

        return new ViewModel([
            'applications' => $applications,
            'competences' => $competences,

            'recopies' => $recopies,
            'champsCREP' => $champs_crep,
            'champsCREF' => $champs_cref,
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

    public function detruireConfigurationFicheMetierAction(): Response
    {
        $configuration = $this->getConfigurationService()->getRequestedConfigurationFicheMetier($this);
        $this->getConfigurationService()->delete($configuration);
        return $this->redirect()->toRoute('configuration', [], [],true);
    }

    public function reappliquerConfigurationFicheMetierAction(): Response
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
