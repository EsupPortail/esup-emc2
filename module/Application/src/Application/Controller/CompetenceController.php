<?php

namespace Application\Controller;

use Application\Entity\Db\Agent;
use Application\Entity\Db\Competence;
use Application\Entity\Db\CompetenceElement;
use Application\Entity\Db\CompetenceTheme;
use Application\Entity\Db\CompetenceType;
use Application\Entity\Db\FicheMetier;
use Application\Form\Competence\CompetenceFormAwareTrait;
use Application\Form\CompetenceElement\CompetenceElementFormAwareTrait;
use Application\Form\CompetenceType\CompetenceTypeFormAwareTrait;
use Application\Form\ModifierLibelle\ModifierLibelleFormAwareTrait;
use Application\Form\SelectionCompetence\SelectionCompetenceFormAwareTrait;
use Application\Service\Activite\ActiviteServiceAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\Competence\CompetenceServiceAwareTrait;
use Application\Service\CompetenceElement\CompetenceElementServiceAwareTrait;
use Application\Service\CompetenceTheme\CompetenceThemeServiceAwareTrait;
use Application\Service\CompetenceType\CompetenceTypeServiceAwareTrait;
use Application\Service\FicheMetier\FicheMetierServiceAwareTrait;
use Application\Service\MaitriseNiveau\MaitriseNiveauServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CompetenceController extends AbstractActionController
{
    use ActiviteServiceAwareTrait;
    use AgentServiceAwareTrait;
    use CompetenceServiceAwareTrait;
    use MaitriseNiveauServiceAwareTrait;
    use CompetenceThemeServiceAwareTrait;
    use CompetenceTypeServiceAwareTrait;
    use CompetenceElementServiceAwareTrait;
    use FicheMetierServiceAwareTrait;

    use CompetenceFormAwareTrait;
    use CompetenceElementFormAwareTrait;
    use CompetenceTypeFormAwareTrait;
    use ModifierLibelleFormAwareTrait;
    use SelectionCompetenceFormAwareTrait;

    /** INDEX *********************************************************************************************************/

    public function indexAction()
    {
        $types = $this->getCompetenceTypeService()->getCompetencesTypes('ordre');
        $themes = $this->getCompetenceThemeService()->getCompetencesThemes();
        $niveaux = $this->getMaitriseNiveauService()->getMaitrisesNiveaux('Compétence', 'niveau', 'ASC', true);
        $array = $this->getCompetenceService()->getCompetencesByTypes();

        return new ViewModel([
            'competencesByType' => $array,
            'themes' => $themes,
            'types' => $types,
            'niveaux' => $niveaux,
        ]);
    }

    /** COMPETENCE ****************************************************************************************************/

    public function ajouterAction()
    {
        $type = $this->getCompetenceTypeService()->getRequestedCompetenceType($this);
        $competence = new Competence();
        if ($type !== null) $competence->setType($type);
        $form = $this->getCompetenceForm();
        $form->setAttribute('action', $this->url()->fromRoute('competence/ajouter', [], [], true));
        $form->bind($competence);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCompetenceService()->create($competence);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'une compétence",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction()
    {
        $competence = $this->getCompetenceService()->getRequestedCompetence($this);
        $form = $this->getCompetenceForm();
        $form->setAttribute('action', $this->url()->fromRoute('competence/modifier', ['competence' => $competence->getId()], [], true));
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
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'une compétence",
            'form' => $form,
        ]);
        return $vm;
    }

    public function afficherAction()
    {
        $competence = $this->getCompetenceService()->getRequestedCompetence($this);
        $activites = $this->getActiviteService()->getActivitesbyCompetence($competence);
        $fiches = $this->getFicheMetierService()->getFichesMetiersByCompetence($competence);
        return new ViewModel([
            'title' => "Affiche d'une compétence",
            'competence' => $competence,
            'activites' => $activites,
            'fiches' => $fiches,
        ]);
    }

    public function historiserAction()
    {
        $competence = $this->getCompetenceService()->getRequestedCompetence($this);
        $this->getCompetenceService()->historise($competence);
        return $this->redirect()->toRoute('competence', [], [], true);
    }

    public function restaurerAction()
    {
        $competence = $this->getCompetenceService()->getRequestedCompetence($this);
        $this->getCompetenceService()->restore($competence);
        return $this->redirect()->toRoute('competence', [], [], true);
    }

    public function detruireAction()
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
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la compétence  " . $competence->getLibelle(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('competence/detruire', ["competence" => $competence->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** COMPETENCE THEME **********************************************************************************************/

    public function afficherCompetenceThemeAction()
    {
        $theme = $this->getCompetenceThemeService()->getRequestedCompetenceTheme($this);
        return new ViewModel([
            'title' => "Affiche d'un thème de compétence",
            'theme' => $theme,
        ]);
    }

    public function ajouterCompetenceThemeAction()
    {
        $theme = new CompetenceTheme();
        $form = $this->getModifierLibelleForm();
        $form->setAttribute('action', $this->url()->fromRoute('competence-theme/ajouter', [], [], true));
        $form->bind($theme);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCompetenceThemeService()->create($theme);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'un thème de compétences",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierCompetenceThemeAction()
    {
        $theme = $this->getCompetenceThemeService()->getRequestedCompetenceTheme($this);
        $form = $this->getModifierLibelleForm();
        $form->setAttribute('action', $this->url()->fromRoute('competence-theme/modifier', ['competence-theme' => $theme->getId()], [], true));
        $form->bind($theme);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCompetenceThemeService()->update($theme);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'un thème de compétences",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserCompetenceThemeAction()
    {
        $theme = $this->getCompetenceThemeService()->getRequestedCompetenceTheme($this);
        $this->getCompetenceThemeService()->historise($theme);
        return $this->redirect()->toRoute('competence', [], [], true);
    }

    public function restaurerCompetenceThemeAction()
    {
        $theme = $this->getCompetenceThemeService()->getRequestedCompetenceTheme($this);
        $this->getCompetenceThemeService()->restore($theme);
        return $this->redirect()->toRoute('competence', [], [], true);
    }

    public function detruireCompetenceThemeAction()
    {
        $theme = $this->getCompetenceThemeService()->getRequestedCompetenceTheme($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getCompetenceThemeService()->delete($theme);
            exit();
        }

        $vm = new ViewModel();
        if ($theme !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de le thème de compétence  " . $theme->getLibelle(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('competence-theme/detruire', ["competence-theme" => $theme->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** COMPETENCE TYPE ***********************************************************************************************/

    public function ajouterCompetenceTypeAction()
    {
        $type = new CompetenceType();
        $form = $this->getCompetenceTypeForm();
        $form->setAttribute('action', $this->url()->fromRoute('competence-type/ajouter', [], [], true));
        $form->bind($type);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCompetenceTypeService()->create($type);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'un type de compétences",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierCompetenceTypeAction()
    {
        $type = $this->getCompetenceTypeService()->getRequestedCompetenceType($this);
        $form = $this->getCompetenceTypeForm();
        $form->setAttribute('action', $this->url()->fromRoute('competence-type/modifier', ['competence-type' => $type->getId()], [], true));
        $form->bind($type);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCompetenceTypeService()->update($type);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'un type de compétences",
            'form' => $form,
        ]);
        return $vm;
    }

    public function afficherCompetenceTypeAction()
    {
        $type = $this->getCompetenceTypeService()->getRequestedCompetenceType($this);
        return new ViewModel([
            'title' => "Affiche d'un type de compétence",
            'type' => $type,
        ]);
    }

    public function historiserCompetenceTypeAction()
    {
        $type = $this->getCompetenceTypeService()->getRequestedCompetenceType($this);
        $this->getCompetenceTypeService()->historise($type);
        return $this->redirect()->toRoute('competence', [], [], true);
    }

    public function restaurerCompetenceTypeAction()
    {
        $type = $this->getCompetenceTypeService()->getRequestedCompetenceType($this);
        $this->getCompetenceTypeService()->restore($type);
        return $this->redirect()->toRoute('competence', [], [], true);
    }

    public function detruireCompetenceTypeAction()
    {
        $type = $this->getCompetenceTypeService()->getRequestedCompetenceType($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getCompetenceTypeService()->delete($type);
            exit();
        }

        $vm = new ViewModel();
        if ($type !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de le type de compétence  " . $type->getLibelle(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('competence-type/detruire', ["competence-type" => $type->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** IMPORT ET REMPLACEMENT ****************************************************************************************/

    public function importerAction()
    {
        $file_path = "/tmp/competence_referens3.csv";
        $content = file_get_contents($file_path);

        $types = [
            'Compétences comportementales' => $this->getCompetenceTypeService()->getCompetenceType(1),
            'Compétences opérationnelles'  => $this->getCompetenceTypeService()->getCompetenceType(2),
            'Connaissances'                => $this->getCompetenceTypeService()->getCompetenceType(3),
        ];

        $lines = explode("\n", $content);
        $nbLine = count($lines);

        for($position = 1 ; $position < $nbLine; $position++) {
            $line = $lines[$position];
            $elements = explode(";", $line);
            $domaine = $elements[0];
            $registre = $elements[1];
            $libelle = $elements[2];
            $definition = $elements[3];
            $id = ((int)$elements[4]);

            if ($libelle !== null and $libelle !== '') {
                //Existe-t-elle ?
                $theme = $this->getCompetenceThemeService()->getCompetenceThemeByLibelle($domaine);
                if ($theme === null) {
                    $theme = new CompetenceTheme();
                    $theme->setLibelle($domaine);
                    $this->getCompetenceThemeService()->create($theme);
                }
                $competence = $this->getCompetenceService()->getCompetenceByIdSource("REFERENS 3", $id);
                $new_competence = ($competence === null);
                if ($new_competence) {
                    $competence = new Competence();
                }
                $competence->setLibelle($libelle);
                if ($definition !== 'Définition en attente' and $definition !== 'Définition non nécessaire') $competence->setDescription($definition); else $competence->setDescription(null);
                $competence->setType($types[$registre]);
                $competence->setTheme($theme);
                $competence->setSource(Competence::SOURCE_REFERENS3);
                $competence->setIdSource($id);
                if ($new_competence) {
                    $this->getCompetenceService()->create($competence);
                } else {
                    $this->getCompetenceService()->update($competence);
                }
            }
        }

        return $this->redirect()->toRoute('competence',[],[], true);
    }

    public function substituerAction() : ViewModel
    {
        $competence = $this->getCompetenceService()->getRequestedCompetence($this);

        $form = $this->getSelectionCompetenceForm();
        $form->setAttribute('action', $this->url()->fromRoute('competence/substituer', ['competence' => $competence->getId()], [], true));

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
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
           'title' => "Sélection de la compétence qui remplacera [".$competence->getLibelle()."]",
           'form' => $form,
        ]);
        return $vm;
    }

    /** GESTION DES COMPETENCES ELEMENTS ==> Faire CONTROLLER ? *******************************************************/

    public function ajouterCompetenceElementAction()
    {
        $type = $this->params()->fromRoute('type');
        $multiple = $this->params()->fromQuery('multiple');

        $hasCompetenceCollection = null;
        switch($type) {
            case Agent::class : $hasCompetenceCollection = $this->getAgentService()->getRequestedAgent($this, 'id');
                break;
            case FicheMetier::class : $hasCompetenceCollection = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'id');
                break;
        }
        $clef = $this->params()->fromRoute('clef');

        if ($hasCompetenceCollection !== null) {
            $element = new CompetenceElement();

            $form = $this->getCompetenceElementForm();

            if ($multiple === '1') {
                $form->get('competence')->setAttribute('multiple', 'multiple');
                $form->remove('clef');
                $form->remove('niveau');
            }

            $form->setAttribute('action', $this->url()->fromRoute('competence/ajouter-competence-element',
                ['type' => $type, 'id' => $hasCompetenceCollection->getId(), 'clef' => $clef],
                ['query' => ['multiple' => $multiple]], true));
            $form->bind($element);
            if ($clef === 'masquer') $form->masquerClef();

            $request = $this->getRequest();
            if ($request->isPost()) {
                $data = $request->getPost();
                if ($multiple !== '1') {
                    $form->setData($data);
                    if ($form->isValid()) {
                        $this->getCompetenceElementService()->create($element);
                        $hasCompetenceCollection->addCompetenceElement($element);
                        switch ($type) {
                            case Agent::class :
                                $this->getAgentService()->update($hasCompetenceCollection);
                                break;
                            case FicheMetier::class :
                                $this->getFicheMetierService()->update($hasCompetenceCollection);
                                break;
                        }
                    }
                } else {
                    $niveau = $this->getMaitriseNiveauService()->getMaitriseNiveau($data['niveau']);
                    $clef = (isset($data['clef']) AND $data['clef'] === "1")?true:false;
                    foreach ($data['competence'] as $competenceId) {
                        $competence = $this->getCompetenceService()->getCompetence($competenceId);
                        if ($competence !== null AND !$hasCompetenceCollection->hasCompetence($competence)) {
                            $competenceElement = new CompetenceElement();
                            $competenceElement->setClef($clef);
                            $competenceElement->setCompetence($competence);
                            $competenceElement->setNiveauMaitrise($niveau);
                            $competenceElement->setClef($clef);
                            $hasCompetenceCollection->addCompetenceElement($competenceElement);
                            $this->getCompetenceElementService()->create($competenceElement);
                        }
                    }
                    switch ($type) {
                        case Agent::class :
                            $this->getAgentService()->update($hasCompetenceCollection);
                            break;
                        case FicheMetier::class :
                            $this->getFicheMetierService()->update($hasCompetenceCollection);
                            break;
                    }
                }
            }

            $vm = new ViewModel([
                'title' => "Ajout d'une compétence",
                'form' => $form,
            ]);
            $vm->setTemplate('application/default/default-form');
            return $vm;
        }
        exit();
    }
}