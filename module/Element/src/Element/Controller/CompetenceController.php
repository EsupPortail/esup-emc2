<?php

namespace Element\Controller;

use Application\Entity\Db\Agent;
use Carriere\Service\Corps\CorpsServiceAwareTrait;
use Carriere\Service\Grade\GradeServiceAwareTrait;
use DateTime;
use Element\Entity\Db\Competence;
use Element\Entity\Db\CompetenceElement;
use Element\Entity\Db\CompetenceType;
use Element\Form\Competence\CompetenceFormAwareTrait;
use Element\Form\SelectionCompetence\SelectionCompetenceFormAwareTrait;
use Element\Service\Competence\CompetenceServiceAwareTrait;
use Element\Service\CompetenceDiscipline\CompetenceDisciplineServiceAwareTrait;
use Element\Service\CompetenceElement\CompetenceElementServiceAwareTrait;
use Element\Service\CompetenceTheme\CompetenceThemeServiceAwareTrait;
use Element\Service\CompetenceType\CompetenceTypeServiceAwareTrait;
use Element\Service\Niveau\NiveauServiceAwareTrait;
use FicheMetier\Provider\Parametre\FicheMetierParametres;
use FicheMetier\Service\FicheMetier\FicheMetierServiceAwareTrait;
use FicheMetier\Service\MissionPrincipale\MissionPrincipaleServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Referentiel\Service\Referentiel\ReferentielServiceAwareTrait;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;

class CompetenceController extends AbstractActionController
{
    use CompetenceServiceAwareTrait;
    use CompetenceDisciplineServiceAwareTrait;
    use CompetenceThemeServiceAwareTrait;
    use CompetenceTypeServiceAwareTrait;
    use CompetenceElementServiceAwareTrait;
    use CorpsServiceAwareTrait;
    use FicheMetierServiceAwareTrait;
    use GradeServiceAwareTrait;
    use MissionPrincipaleServiceAwareTrait;
    use NiveauServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use ReferentielServiceAwareTrait;
    use StructureServiceAwareTrait;

    use CompetenceFormAwareTrait;
    use SelectionCompetenceFormAwareTrait;

    /** INDEX *********************************************************************************************************/

    public function indexAction(): ViewModel
    {
        $types = $this->getCompetenceTypeService()->getCompetencesTypes(false, 'ordre');
//        $themes = $this->getCompetenceThemeService()->getCompetencesThemes();
//        $niveaux = $this->getNiveauService()->getMaitrisesNiveaux('Compétence', 'niveau', 'ASC', true);
//        $array = $this->getCompetenceService()->getCompetencesByTypes();

        return new ViewModel([
            'types' => $types,
//            'competencesByType' => $array,
//            'themes' => $themes,

//            'niveaux' => $niveaux,
        ]);
    }

    public function listingAction(): ViewModel
    {
        $types = $this->getCompetenceTypeService()->getCompetencesTypes(false, 'ordre');
        $themes = $this->getCompetenceThemeService()->getCompetencesThemes();
        $disciplines = $this->getCompetenceDisciplineService()->getCompetencesDisciplines();
        $referentiels = $this->getReferentielService()->getReferentiels();

        $params = $this->params()->fromQuery();
        $type = $this->getCompetenceTypeService()->getRequestedCompetenceType($this);

        if ($type === null) {
            $competences = $this->getCompetenceService()->getCompetencesWithFiltre($params);
        } else {
            $competences = $this->getCompetenceService()->getCompetencesByType($type);
        }

        return new ViewModel([
            'types' => $types,
            'themes' => $themes,
            'disciplines' => $disciplines,
            'referentiels' => $referentiels,
            'params' => $params,

            'type' => $type,
            'competences' => $competences,
        ]);
    }


    /** COMPETENCE ****************************************************************************************************/

    public function afficherAction(): ViewModel
    {
        $competence = $this->getCompetenceService()->getRequestedCompetence($this);
        $agents = $this->getCompetenceElementService()->getAgentsHavinCompetenceFromAgent($competence);
        $fiches = $this->getFicheMetierService()->getFichesMetiersByCompetence($competence);

        $displayCodeFonction = $this->getParametreService()->getValeurForParametre(FicheMetierParametres::TYPE, FicheMetierParametres::CODE_FONCTION);
        return new ViewModel([
            'title' => "Affichage d'une compétence",
            'competence' => $competence,
            'agents' => $agents,
            'fiches' => $fiches,
            'displayCodeFonction' => $displayCodeFonction,
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
                $competence->setReference($competence->getReference() ?? $competence->getId());
                $this->getCompetenceService()->update($competence);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('element/competence/modifier');
        $vm->setVariables([
            'title' => "Ajout d'une compétence",
            'form' => $form,
            'type' => $type,
            'spec' => $this->getCompetenceTypeService()->getCompetenceTypeByCode(CompetenceType::CODE_SPECIFIQUE),
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
        $vm->setTemplate('element/competence/modifier');
        $vm->setVariables([
            'title' => "Modification d'une compétence",
            'form' => $form,
            'type' => $competence->getType(),
            'spec' => $this->getCompetenceTypeService()->getCompetenceTypeByCode(CompetenceType::CODE_SPECIFIQUE),
        ]);
        return $vm;
    }

    public function historiserAction(): Response
    {
        $competence = $this->getCompetenceService()->getRequestedCompetence($this);
        $this->getCompetenceService()->historise($competence);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('element/competence', [], [], true);
    }

    public function restaurerAction(): Response
    {
        $competence = $this->getCompetenceService()->getRequestedCompetence($this);
        $this->getCompetenceService()->restore($competence);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
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

                    $competence = $this->getCompetenceService()->getCompetence($competenceId !== '' ? $competenceId : null);
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
                    $agents = array_filter($agents, function ($agent) use ($structure) {
                        return $agent->hasAffectationPrincipale($structure);
                    });
                }
                if ($query['corps']['id']) {
                    $corps = $this->getCorpsService()->getCorp($query['corps']['id']);
                    $agents = array_filter($agents, function (Agent $agent) use ($corps) {
                        return $agent->hasCorps($corps);
                    });
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

    public function importerAction(): ViewModel
    {
        $error = [];
        $warning = [];
        $info = [];

        $data = null;
        $file = null;
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $mode = ($data["mode"] !== "") ? $data["mode"] : null;
            if ($mode === null) $error[] = "Aucun mode sélectionné";
            $referentiel = $this->getReferentielService()->getReferentiel($data["referentiel"] !== "" ? $data["referentiel"] : null);
            if ($referentiel === null) $error[] = "Aucun référentiel sélectionné";

            $files = $request->getFiles()->toArray();
            $file = !empty($files) ? current($files) : null;

            $filename = $data['filename'] ?? null;
            $filepath = $data['filepath'] ?? null;

            if (($file === null or $file['tmp_name'] === "") and $filepath === null) {
                $error[] = "Aucun fichier fourni";
            } else {
                if ($filepath === null) {
                    $filepath = '/tmp/import_competence_' . (new DateTime())->getTimestamp() . '.csv';
                    $filename = $file['name'];
                    copy($file['tmp_name'], $filepath);
                }
            }
            $result = [];


            if ($filepath !== null and $filepath !== "" and $referentiel !== null and $mode !== null) {
                $result = $this->getCompetenceService()->import($filepath, $referentiel, $mode, $info, $warning, $error);
            }

            if ($mode === 'import') {
                foreach ($result['competences']??[] as $competence) {
                // Bricolage pour satisfaire Marseille
                    $codesFicheMetier = explode('|', $competence->getCodesEmploiType() ?? "");
                    $codesFicheMetier = array_map('trim', $codesFicheMetier);
                    $codesFicheMetier = array_filter($codesFicheMetier, function (string $a) {
                        return $a !== '';
                    });
                    foreach ($codesFicheMetier as $codeFicheMetier) {
                        $fichemetier = $this->getFicheMetierService()->getFicheMetierByReferentielAndCode($referentiel, $codeFicheMetier);
                        if ($fichemetier === null) {
                            $warning[] = "La fiche metier [" . $referentiel->getLibelleCourt() . "|" . $codeFicheMetier . "] n'existe pas";
                        } else {
                            if (!$fichemetier->hasCompetence($competence)) {
                                $element = new CompetenceElement();
                                $element->setCompetence($competence);
                                $this->getCompetenceElementService()->create($element);
                                $fichemetier->addCompetenceElement($element);
                                $this->getFicheMetierService()->update($fichemetier);
                                $info[] = "Ajout de la compétence [" . $competence->getReference() ."|". $competence->getLibelle() ."] a été ajouté à la fiche metier [" . ($fichemetier->getReference() ?? ("Fiche #" . $fichemetier->getId())) . "]";
                            }
                        }
                    }
                    $codesFonction = explode('|', $competence->getCodesFonction() ?? "");
                    $codesFonction = array_map('trim', $codesFonction);
                    $codesFonction = array_filter($codesFonction, function (string $a) {
                        return $a !== '';
                    });
                    foreach ($codesFonction as $codeFonction) {
                        $fichesmetiers = $this->getFicheMetierService()->getFichesMetiersByCodeFonction($codeFonction);
                        if (empty($fichesmetiers)) {
                            $warning[] = "Aucune fiche metier utilise le code [" . $codeFonction . "]";
                        }
                        foreach ($fichesmetiers as $fichemetier) {
                            if (!$fichemetier->hasCompetence($competence)) {
                                $element = new CompetenceElement();
                                $element->setCompetence($competence);
                                $this->getCompetenceElementService()->create($element);
                                $fichemetier->addCompetenceElement($element);
                                $this->getFicheMetierService()->update($fichemetier);
                                $info[] = "Ajout de la compétence [" . $competence->getReference() ."|". $competence->getLibelle() ."] a été ajouté à la fiche metier [" . ($fichemetier->getReference() ?? ("Fiche #" . $fichemetier->getId())) . "]";
                            }
                        }
                    }
                }
            }
        }

        return new ViewModel([
            'referentiels' => $this->getReferentielService()->getReferentiels(),
            'competences' => $result['competences'] ?? [],
            'info' => $info,
            'warning' => $warning,
            'error' => $error,

            'data' => $data,
            'file' => $file,
            'filepath' => $filepath ?? null,
            'filename' => $filename ?? null,
        ]);
    }

}