<?php

namespace Element\Controller;

use Application\Entity\Db\Agent;
use Carriere\Service\Corps\CorpsServiceAwareTrait;
use Carriere\Service\Grade\GradeServiceAwareTrait;
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
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Structure\Service\Structure\StructureServiceAwareTrait;

class CompetenceController extends AbstractActionController
{
    use CompetenceServiceAwareTrait;
    use CompetenceThemeServiceAwareTrait;
    use CompetenceTypeServiceAwareTrait;
    use CompetenceElementServiceAwareTrait;
    use CorpsServiceAwareTrait;
    use FicheMetierServiceAwareTrait;
    use GradeServiceAwareTrait;
    use MissionPrincipaleServiceAwareTrait;
    use NiveauServiceAwareTrait;
    use StructureServiceAwareTrait;

    use CompetenceFormAwareTrait;
    use SelectionCompetenceFormAwareTrait;

    /** INDEX *********************************************************************************************************/

    public function indexAction(): ViewModel
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

    public function afficherAction(): ViewModel
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

    public function ajouterAction(): ViewModel
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
                $competence->setIdSource($competence->getIdSource() ?? $competence->getId());
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

    public function modifierAction(): ViewModel
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

    public function historiserAction(): Response
    {
        $competence = $this->getCompetenceService()->getRequestedCompetence($this);
        $this->getCompetenceService()->historise($competence);
        return $this->redirect()->toRoute('element/competence', [], [], true);
    }

    public function restaurerAction(): Response
    {
        $competence = $this->getCompetenceService()->getRequestedCompetence($this);
        $this->getCompetenceService()->restore($competence);
        return $this->redirect()->toRoute('element/competence', [], [], true);
    }

    public function detruireAction(): ViewModel
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

    public function substituerAction(): ViewModel
    {
        $competence = $this->getCompetenceService()->getRequestedCompetence($this);

        $form = $this->getSelectionCompetenceForm();
        $form->setAttribute('action', $this->url()->fromRoute('element/competence/substituer', ['competence' => $competence->getId()], [], true));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $competenceSub = $this->getCompetenceService()->getCompetence($data['competences'][0]);

            if ($competenceSub and $competenceSub !== $competence) {
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
            'title' => "Sélection de la compétence qui remplacera [" . $competence->getLibelle() . "]",
            'form' => $form,
        ]);
        return $vm;
    }

    public function rechercherAction(): JsonModel
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $competences = $this->getCompetenceService()->getCompetencesByTerm($term);
            $result = $this->getCompetenceService()->formatCompetencesJSON($competences);
            return new JsonModel($result);
        }
        exit;
    }

    /** Fonction associée à la recherche d'éléments ayant un sous-ensemble de comptences
     *  TODO/QUID décaller dans un CompetenceElementController ???
     **/

    public function rechercherAgentsAction(): ViewModel
    {
        $query = $this->params()->fromQuery();
        $agents = [];
        $competences = [];
        $criteres = [];
        $structure = null;
        $corps = null;

        if (!empty($query)) {
            $criteres = [];
            foreach ($query as $key => $value) {
                if (str_contains($key, 'competence-filtre_')) {
                    $group = substr($key, strlen('competence-filtre_'));
                    $competenceId = $value['id'];
                    $operateur = $query['operateur_' . $group];
                    $niveau = $query['niveau_' . $group];

                    $competence = $this->getCompetenceService()->getCompetence($competenceId !== ''?$competenceId:null);
                    if ($competence) {
                        $criteres[] = [
                            'id' => $group,
                            'competence' => $competence,
                            'operateur' => $operateur,
                            'niveau' => $niveau,
                        ];
                        $competences[] = $competence;
                    }

                }
                $agents = [];
                if (!empty($criteres)) {
                    $agents = $this->getCompetenceElementService()->getAgentsHavingCompetencesWithCriteres($criteres);
                }

                if ($query['structure']['id']) {
                    $structure = $this->getStructureService()->getStructure($query['structure']['id']);
                    $agents = array_filter($agents, function ($agent) use ($structure) { return $agent->hasAffectationPrincipale($structure); });
                }
                if ($query['corps']['id']) {
                    $corps = $this->getCorpsService()->getCorp($query['corps']['id']);
                    $agents = array_filter($agents, function (Agent $agent) use ($corps) { return $agent->hasCorps($corps); });
                }
            }
        }

        $niveaux = $this->getNiveauService()->getMaitrisesNiveaux("Competence");
        return new ViewModel([
            'niveaux' => $niveaux,
            'query' => $query,
            'agents' => $agents,
            'competences' => $competences,
            'criteria' => $criteres,
            'structureFiltre' => $structure,
            'corpsFiltre' => $corps,
        ]);
    }

}