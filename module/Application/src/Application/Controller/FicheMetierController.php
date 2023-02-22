<?php

namespace Application\Controller;

use Application\Entity\Db\Activite;
use Application\Form\Activite\ActiviteForm;
use Application\Form\Activite\ActiviteFormAwareTrait;
use Application\Form\SelectionFicheMetier\SelectionFicheMetierFormAwareTrait;
use Application\Provider\Etat\FicheMetierEtats;
use Application\Service\Activite\ActiviteServiceAwareTrait;
use Application\Service\ActiviteDescription\ActiviteDescriptionServiceAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\Configuration\ConfigurationServiceAwareTrait;
use Application\Service\FicheMetier\FicheMetierServiceAwareTrait;
use Application\Service\ParcoursDeFormation\ParcoursDeFormationServiceAwareTrait;
use Doctrine\ORM\ORMException;
use Element\Entity\Db\ApplicationElement;
use Element\Entity\Db\CompetenceElement;
use Element\Form\SelectionApplication\SelectionApplicationFormAwareTrait;
use Element\Form\SelectionCompetence\SelectionCompetenceFormAwareTrait;
use Element\Service\HasApplicationCollection\HasApplicationCollectionServiceAwareTrait;
use Element\Service\HasCompetenceCollection\HasCompetenceCollectionServiceAwareTrait;
use Formation\Form\SelectionFormation\SelectionFormationFormAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\View\Model\ViewModel;
use Metier\Form\SelectionnerMetier\SelectionnerMetierFormAwareTrait;
use Metier\Service\Domaine\DomaineServiceAwareTrait;
use Metier\Service\Metier\MetierServiceAwareTrait;
use UnicaenApp\Exception\RuntimeException;
use UnicaenEtat\Form\SelectionEtat\SelectionEtatFormAwareTrait;
use UnicaenEtat\Service\Etat\EtatServiceAwareTrait;
use UnicaenEtat\Service\EtatType\EtatTypeServiceAwareTrait;
use UnicaenRenderer\Service\Rendu\RenduServiceAwareTrait;

/** @method FlashMessenger flashMessenger() */
class FicheMetierController extends AbstractActionController
{
    /** Traits associés aux services */
    use ActiviteServiceAwareTrait;
    use ActiviteDescriptionServiceAwareTrait;
    use AgentServiceAwareTrait;
    use ConfigurationServiceAwareTrait;
    use DomaineServiceAwareTrait;
    use EtatServiceAwareTrait;
    use EtatTypeServiceAwareTrait;
    use FicheMetierServiceAwareTrait;
    use HasApplicationCollectionServiceAwareTrait;
    use HasCompetenceCollectionServiceAwareTrait;
    use MetierServiceAwareTrait;
    use ParcoursDeFormationServiceAwareTrait;
    use RenduServiceAwareTrait;

    /** Traits associés aux formulaires */
    use ActiviteFormAwareTrait;
    use SelectionApplicationFormAwareTrait;
    use SelectionCompetenceFormAwareTrait;
    use SelectionEtatFormAwareTrait;
    use SelectionFicheMetierFormAwareTrait;
    use SelectionFormationFormAwareTrait;
    use SelectionnerMetierFormAwareTrait;


    const REFERENS_SEP = "|";

    /** CRUD **********************************************************************************************************/

    public function indexAction(): ViewModel
    {
        $fromQueries = $this->params()->fromQuery();
        $etatId = $fromQueries['etat'] ?? null;
        $domaineId = $fromQueries['domaine'] ?? null;
        $expertise = $fromQueries['expertise'] ?? null;
        $params = ['etat' => $etatId, 'domaine' => $domaineId, 'expertise' => $expertise];

        $type = $this->getEtatTypeService()->getEtatTypeByCode(FicheMetierEtats::TYPE);
        $etats = $this->getEtatService()->getEtatsByType($type);
        $domaines = $this->getDomaineService()->getDomaines();

        $fichesMetiers = $this->getFicheMetierService()->getFichesMetiersWithFiltre($params);

        return new ViewModel([
            'params' => $params,
            'domaines' => $domaines,
            'etats' => $etats,
            'fiches' => $fichesMetiers,
        ]);
    }



    /** ACTIVITE LIEE *************************************************************************************************/

    public function ajouterNouvelleActiviteAction(): ViewModel
    {
        $fiche = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'id', true);

        $activite = new Activite();
        /** @var ActiviteForm $form */
        $form = $this->getActiviteForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier-type/ajouter-nouvelle-activite', ['id' => $fiche->getId()], [], true));
        $form->bind($activite);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getActiviteService()->create($activite);
                $this->getActiviteService()->updateLibelle($activite, $data);
                $this->getActiviteService()->createFicheMetierActivite($fiche, $activite);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajouter une nouvelle activité',
            'form' => $form,
        ]);
        return $vm;

    }

    public function ajouterActiviteExistanteAction(): ViewModel
    {
        $fiche = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'id', true);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $activite = $this->getActiviteService()->getActivite($data['activite']["id"]);
            $this->getActiviteService()->createFicheMetierActivite($fiche, $activite);
        }

        return new ViewModel([
            'title' => 'Ajouter une activité existante',
            'url' => $this->url()->fromRoute('fiche-metier-type/ajouter-activite-existante', ['id' => $fiche->getId()], [], true),
        ]);
    }

    public function retirerActiviteAction(): Response
    {
        $coupleId = $this->params()->fromRoute('id');
        $couple = $this->getActiviteService()->getFicheMetierActivite($coupleId);

        $this->getActiviteService()->removeFicheMetierActivite($couple);

        return $this->redirect()->toRoute('fiche-metier-type/editer', ['id' => $couple->getFiche()->getId()], [], true);
    }

    public function deplacerActiviteAction(): Response
    {
        $direction = $this->params()->fromRoute('direction');
        $coupleId = $this->params()->fromRoute('id');
        $couple = $this->getActiviteService()->getFicheMetierActivite($coupleId);

        if ($direction === 'up') $this->getActiviteService()->moveUp($couple);
        if ($direction === 'down') $this->getActiviteService()->moveDown($couple);

        $this->getActiviteService()->updateFicheMetierActivite($couple);

        return $this->redirect()->toRoute('fiche-metier-type/editer', ['id' => $couple->getFiche()->getId()], [], true);
    }

    /** ACTIONS LIEES AUX ELEMENTS ************************************************************************************/

    public function afficherApplicationsAction(): ViewModel
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $applications = $this->getFicheMetierService()->getApplicationsDictionnaires($fichemetier, true);

        return new ViewModel([
            'fichemetier' => $fichemetier,
            'applications' => $applications,
        ]);
    }

    public function clonerApplicationsAction(): ViewModel
    {
        $ficheMetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');

        $form = $this->getSelectionFicheMetierForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier-type/cloner-applications', ['fiche-metier' => $ficheMetier->getId()], [], true));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $ficheClone = $this->getFicheMetierService()->getFicheMetier($data['fiche-metier']);

            if ($ficheClone !== null) {
                try {
                    /** @var CompetenceElement[] $oldCollection */
                    $oldCollection = $ficheMetier->getApplicationCollection();
                    foreach ($oldCollection as $element) $element->historiser();

                    $newCollection = $ficheClone->getApplicationListe();
                    foreach ($newCollection as $element) {
                        $newElement = new ApplicationElement();
                        $newElement->setApplication($element->getApplication());
                        $newElement->setCommentaire("Clonée depuis la fiche #" . $ficheClone->getId());
                        $newElement->setNiveauMaitrise($element->getNiveauMaitrise());
                        $this->getFicheMetierService()->getEntityManager()->persist($newElement);
                        $ficheMetier->addApplicationElement($newElement);
                    }
                    $this->getFicheMetierService()->getEntityManager()->flush();
                } catch (ORMException $e) {
                    throw new RuntimeException("Un problème est survenu en base de donnée", 0, $e);
                }
            }

        }

        $vm = new ViewModel();
        $vm->setVariables([
            'title' => "Cloner les applications d'une autre fiche métier",
            'form' => $form,
        ]);
        return $vm;
    }

    public function afficherCompetencesAction(): ViewModel
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $competences = $this->getFicheMetierService()->getCompetencesDictionnaires($fichemetier, true);

        return new ViewModel([
            'fichemetier' => $fichemetier,
            'competences' => $competences,
        ]);
    }

    public function clonerCompetencesAction(): ViewModel
    {
        $ficheMetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');

        $form = $this->getSelectionFicheMetierForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier-type/cloner-competences', ['fiche-metier' => $ficheMetier->getId()], [], true));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $ficheClone = $this->getFicheMetierService()->getFicheMetier($data['fiche-metier']);

            if ($ficheClone !== null) {
                try {
                    /** @var CompetenceElement[] $oldCollection */
                    $oldCollection = $ficheMetier->getCompetenceCollection();
                    foreach ($oldCollection as $element) $element->historiser();

                    $newCollection = $ficheClone->getCompetenceListe();
                    foreach ($newCollection as $element) {
                        $newElement = new CompetenceElement();
                        $newElement->setCompetence($element->getCompetence());
                        $newElement->setCommentaire("Clonée depuis la fiche #" . $ficheClone->getId());
                        $newElement->setNiveauMaitrise($element->getNiveauMaitrise());
                        $this->getFicheMetierService()->getEntityManager()->persist($newElement);
                        $ficheMetier->addCompetenceElement($newElement);
                    }
                    $this->getFicheMetierService()->getEntityManager()->flush();
                } catch (ORMException $e) {
                    throw new RuntimeException("Un problème est survenu en base de donnée", 0, $e);
                }
            }

        }

        $vm = new ViewModel();
        $vm->setVariables([
            'title' => "Cloner les compétences d'une autre fiche métier",
            'form' => $form,
        ]);
        return $vm;
    }

    /** Expertise  ****************************************************************************************************/

    public function changerExpertiseAction(): Response
    {
        $fiche = $this->getFicheMetierService()->getRequestedFicheMetier($this);
        if ($fiche->hasExpertise()) {
            $fiche->setExpertise(false);
        } else {
            $fiche->setExpertise(true);
        }
        $this->getFicheMetierService()->update($fiche);

        return $this->redirect()->toRoute('fiche-metier-type/editer', ['id' => $fiche->getId()], [], true);
    }

    /** Graphique *****************************************************************************************************/

    public function graphiqueCompetencesAction(): ViewModel
    {
        $ficheMetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $agent = $this->getAgentService()->getRequestedAgent($this);

        $dictionnaire = $this->getFicheMetierService()->getCompetencesDictionnaires($ficheMetier, true);
        $dictionnaire = array_filter($dictionnaire, function ($item) {
            return ($item['entite'])->isClef();
        });
        $labels = [];
        $values = [];

        $valuesFiche = [];
        foreach ($dictionnaire as $entry) {
            /** @var CompetenceElement $element */
            $element = $entry['entite'];
            $labels[] = substr($element->getLibelle(), 0, 200);
            $valuesFiche[] = ($element->getNiveauMaitrise()) ? $element->getNiveauMaitrise()->getNiveau() : "'-'";
        }
        $values[] = [
            'title' => "pré-requis",
            'values' => $valuesFiche,
            'color' => "255,0,0",
        ];

        if ($agent !== null) {
            $valuesAgent = [];
            /** @var CompetenceElement[] $competences */
            $competences = $agent->getCompetenceListe();
            foreach ($dictionnaire as $entry) {
                /** @var CompetenceElement $element */
                $element = $entry['entite'];
                $id = $element->getCompetence()->getId();
                $niveau = (isset($competences[$id]) and $competences[$id]->getNiveauMaitrise()) ? $competences[$id]->getNiveauMaitrise()->getNiveau() : "'-'";
                $valuesAgent[] = $niveau;
            }
            $values[] = [
                'title' => "Acquis",
                'values' => $valuesAgent,
                'color' => "0,255,0",
            ];
        }

        $libelle = $ficheMetier->getMetier()->getLibelle();
        $vm = new ViewModel([
            'title' => "Diagramme des compétences pour la fiche métier <strong>" . $libelle . "</strong>",
            'agent' => $agent,
            'label' => $labels,
            'values' => $values,
        ]);
        $vm->setTemplate('application/fiche-metier/graphique-radar');
        return $vm;
    }

    public function graphiqueApplicationsAction(): ViewModel
    {
        $ficheMetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $agent = $this->getAgentService()->getRequestedAgent($this);

        $dictionnaire = $this->getFicheMetierService()->getApplicationsDictionnaires($ficheMetier, true);
        $dictionnaire = array_filter($dictionnaire, function ($item) {
            return ($item['entite'])->isClef();
        });
        $labels = [];
        $values = [];

        $valuesFiche = [];
        foreach ($dictionnaire as $entry) {
            /** @var ApplicationElement $element */
            $element = $entry['entite'];
            $labels[] = substr($element->getLibelle(), 0, 200);
            $valuesFiche[] = ($element->getNiveauMaitrise()) ? $element->getNiveauMaitrise()->getNiveau() : "'-'";
        }
        $values[] = [
            'title' => "pré-requis",
            'values' => $valuesFiche,
            'color' => "255,0,0",
        ];

        if ($agent !== null) {
            $valuesAgent = [];
            /** @var ApplicationElement[] $applications */
            $applications = $agent->getApplicationListe();
            foreach ($dictionnaire as $entry) {
                /** @var ApplicationElement $element */
                $element = $entry['entite'];
                $id = $element->getApplication()->getId();
                $niveau = (isset($applications[$id]) and $applications[$id]->getNiveauMaitrise()) ? $applications[$id]->getNiveauMaitrise()->getNiveau() : "'-'";
                $valuesAgent[] = $niveau;
            }
            $values[] = [
                'title' => "Acquis",
                'values' => $valuesAgent,
                'color' => "0,255,0",
            ];
        }

        $libelle = $ficheMetier->getMetier()->getLibelle();
        $vm = new ViewModel([
            'title' => "Diagramme des applications pour la fiche métier <strong>" . $libelle . "</strong>",
            'agent' => $agent,
            'label' => $labels,
            'values' => $values,
        ]);
        $vm->setTemplate('application/fiche-metier/graphique-radar');
        return $vm;
    }

}