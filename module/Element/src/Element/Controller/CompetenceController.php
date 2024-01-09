<?php

namespace Element\Controller;

use Element\Entity\Db\Competence;
use Element\Form\Competence\CompetenceFormAwareTrait;
use Element\Form\SelectionCompetence\SelectionCompetenceFormAwareTrait;
use Element\Service\Competence\CompetenceServiceAwareTrait;
use Element\Service\CompetenceElement\CompetenceElementServiceAwareTrait;
use Element\Service\CompetenceTheme\CompetenceThemeServiceAwareTrait;
use Element\Service\CompetenceType\CompetenceTypeServiceAwareTrait;
use Element\Service\Niveau\NiveauServiceAwareTrait;
use FicheMetier\Service\FicheMetier\FicheMetierServiceAwareTrait;
use FicheMetier\Service\MissionPrincipale\MissionPrincipaleServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class CompetenceController extends AbstractActionController
{
    use CompetenceServiceAwareTrait;
    use CompetenceThemeServiceAwareTrait;
    use CompetenceTypeServiceAwareTrait;
    use CompetenceElementServiceAwareTrait;
    use FicheMetierServiceAwareTrait;
    use MissionPrincipaleServiceAwareTrait;
    use NiveauServiceAwareTrait;

    use CompetenceFormAwareTrait;
    use SelectionCompetenceFormAwareTrait;

    /** INDEX *********************************************************************************************************/

    public function indexAction() : ViewModel
    {
        $types = $this->getCompetenceTypeService()->getCompetencesTypes('ordre');
        $themes = $this->getCompetenceThemeService()->getCompetencesThemes();
        $niveaux = $this->getNiveauService()->getMaitrisesNiveaux('Compétence', 'niveau', 'ASC', true);
        $array = $this->getCompetenceService()->getCompetencesByTypes();

        return new ViewModel([
            'competencesByType' => $array,
            'themes' => $themes,
            'types' => $types,
            'niveaux' => $niveaux,
        ]);
    }

    /** COMPETENCE ****************************************************************************************************/

    public function afficherAction() : ViewModel
    {
        $competence = $this->getCompetenceService()->getRequestedCompetence($this);
        $agents = $this->getCompetenceElementService()->getAgentsHavinCompetenceFromAgent($competence);
        $missions = $this->getMissionPrincipaleService()->getMissionsHavingCompetence($competence);
        $fiches = $this->getFicheMetierService()->getFichesMetiersByCompetence($competence);
        return new ViewModel([
            'title' => "Affiche d'une compétence",
            'competence' => $competence,
            'agents' => $agents,
            'missions' => $missions,
            'fiches' => $fiches,
        ]);
    }

    public function ajouterAction() : ViewModel
    {
        $type = $this->getCompetenceTypeService()->getRequestedCompetenceType($this);
        $competence = new Competence();
        if ($type !== null) $competence->setType($type);
        $form = $this->getCompetenceForm();
        $form->setAttribute('action', $this->url()->fromRoute('element/competence/ajouter', [], [], true));
        $form->bind($competence);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCompetenceService()->create($competence);
                $competence->setSource("EMC2");
                $competence->setIdSource($competence->getId());
                $this->getCompetenceService()->update($competence);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'une compétence",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction() : ViewModel
    {
        $competence = $this->getCompetenceService()->getRequestedCompetence($this);
        $form = $this->getCompetenceForm();
        $form->setAttribute('action', $this->url()->fromRoute('element/competence/modifier', ['competence' => $competence->getId()], [], true));
        $form->bind($competence);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCompetenceService()->update($competence);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/default-form');
        $vm->setVariables([
            'title' => "Modification d'une compétence",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction() : Response
    {
        $competence = $this->getCompetenceService()->getRequestedCompetence($this);
        $this->getCompetenceService()->historise($competence);
        return $this->redirect()->toRoute('element/competence', [], [], true);
    }

    public function restaurerAction() : Response
    {
        $competence = $this->getCompetenceService()->getRequestedCompetence($this);
        $this->getCompetenceService()->restore($competence);
        return $this->redirect()->toRoute('element/competence', [], [], true);
    }

    public function detruireAction() : ViewModel
    {
        $competence = $this->getCompetenceService()->getRequestedCompetence($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getCompetenceService()->delete($competence);
            exit();
        }

        $vm = new ViewModel();
        if ($competence !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la compétence  " . $competence->getLibelle(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('element/competence/detruire', ["competence" => $competence->getId()], [], true),
            ]);
        }
        return $vm;
    }

    public function substituerAction() : ViewModel
    {
        $competence = $this->getCompetenceService()->getRequestedCompetence($this);

        $form = $this->getSelectionCompetenceForm();
        $form->setAttribute('action', $this->url()->fromRoute('element/competence/substituer', ['competence' => $competence->getId()], [], true));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $competenceSub = $this->getCompetenceService()->getCompetence($data['competences'][0]);

            if ($competenceSub AND $competenceSub !== $competence) {
                $elements = $this->getCompetenceElementService()->getElementsByCompetence($competence);
                foreach ($elements as $element) {
                    $element->setCompetence($competenceSub);
                    $this->getCompetenceElementService()->update($element);
                }
                $this->getCompetenceService()->delete($competence);
            }

        }

        $vm = new ViewModel();
        $vm->setTemplate('default/default-form');
        $vm->setVariables([
           'title' => "Sélection de la compétence qui remplacera [".$competence->getLibelle()."]",
           'form' => $form,
        ]);
        return $vm;
    }

}