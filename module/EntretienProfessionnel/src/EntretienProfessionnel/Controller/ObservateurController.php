<?php

namespace EntretienProfessionnel\Controller;

use Application\Service\Agent\AgentServiceAwareTrait;
use DateTime;
use EntretienProfessionnel\Entity\Db\Observateur;
use EntretienProfessionnel\Form\ImporterObservateur\ImporterObservateurFormAwareTrait;
use EntretienProfessionnel\Form\Observateur\ObservateurFormAwareTrait;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use EntretienProfessionnel\Service\Observateur\ObservateurServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class ObservateurController extends AbstractActionController {
    use AgentServiceAwareTrait;
    use CampagneServiceAwareTrait;
    use EntretienProfessionnelServiceAwareTrait;
    use ObservateurServiceAwareTrait;
    use UserServiceAwareTrait;
    use ImporterObservateurFormAwareTrait;
    use ObservateurFormAwareTrait;

    public function indexAction(): ViewModel
    {
        $params = $this->params()->fromQuery();

        $observateurs = $this->getObservateurService()->getObservateursWithFiltre($params);
        $campagnes = $this->getCampagneService()->getCampagnes();

        return new ViewModel([
            'observateurs' => $observateurs,
            'campagnes' => $campagnes,
            'params' => $params,
        ]);
    }

    public function indexObservateurAction(): ViewModel
    {
        $user = $this->getUserService()->getConnectedUser();
        $observateurs = $this->getObservateurService()->getObservateursByUser($user, false);

        $campagnes = [];
        $entretiens = [];
        foreach ($observateurs as $observateur) {
            $entretien = $observateur->getEntretienProfessionnel();
            $campagne = $entretien->getCampagne();
            $campagnes[$campagne->getId()] = $campagne;
            $entretiens[$campagne->getId()][] = $entretien;
        }

        return new ViewModel([
            'campagnes' => $campagnes,
            'entretiens' => $entretiens,
        ]);
    }

    public function ajouterAction(): ViewModel
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this);

        $observateur = new Observateur();
        $observateur->setEntretienProfessionnel($entretien);
        $form = $this->getObservateurForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/observateur/ajouter', ['entretien-professionnel' => $entretien?->getId()], [], true));
        $form->bind($observateur);


        if ($entretien !== null) {
            $form->get('entretien')->setAttribute('readonly', true);
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getObservateurService()->create($observateur);
                exit();
            }
        }

        $titre = "Ajout d'un·e observateur·trice pour l'entretien professionnel";
        if ($entretien !== null) $titre .= " de ". $entretien->getAgent()->getDenomination(true);
        $vm = new ViewModel([
            'title' => $titre,
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function modifierAction(): ViewModel
    {
        $observateur = $this->getObservateurService()->getRequestedObservateur($this);

        $form = $this->getObservateurForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/observateur/modifier', ['observateur' => $observateur->getId()], [], true));
        $form->bind($observateur);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getObservateurService()->update($observateur);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Modification de l'observateur·trice pour l'entretien professionnel de ". $observateur->getEntretienProfessionnel()->getAgent()->getDenomination(true),
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function historiserAction(): Response
    {
        $observateur = $this->getObservateurService()->getRequestedObservateur($this);
        $this->getObservateurService()->historise($observateur);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('entretien-professionnel/acceder', ['entretien-professionnel' => $observateur->getEntretienProfessionnel()->getId()], [], true);
    }

    public function restaurerAction(): Response
    {
        $observateur = $this->getObservateurService()->getRequestedObservateur($this);
        $this->getObservateurService()->restore($observateur);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('entretien-professionnel/acceder', ['entretien-professionnel' => $observateur->getEntretienProfessionnel()->getId()], [], true);
    }

    public function supprimerAction(): ViewModel
    {
        $observateur = $this->getObservateurService()->getRequestedObservateur($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getObservateurService()->delete($observateur);
            exit();
        }

        $vm = new ViewModel();
        if ($observateur !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de l'obervateur·trice " . $observateur->getUser()->getDisplayName(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('entretien-professionnel/observateur/supprimer', ["observateur" => $observateur->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** IMPORTATION ***************************************************************************************************/

    public function importerAction(): ViewModel
    {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);
        $entretiens = ($campagne)?$this->getEntretienProfessionnelService()->getEntretiensProfessionnelsByCampagne($campagne):[];

        $form = $this->getImporterObservateurForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/observateur/importer', ['campagne' => $campagne?->getId()], [], true));

        if ($campagne) $form->get('campagne')->setAttribute('readonly', true); // OR DISABLED ?

        $request = $this->getRequest();
        if ($request->isPost()) {
            $error = [];
            $data = $request->getPost();
            $file = $request->getFiles();

            $fichier_path = $file['fichier']['tmp_name'];
            $mode = $data['mode'];

            if ($campagne === null AND isset($data['campagne'])) {
                $campagne = $this->getCampagneService()->getCampagne($data['campagne']);
                $entretiens = $this->getEntretienProfessionnelService()->getEntretiensProfessionnelsByCampagne($campagne);

                foreach ($entretiens as $entretien) {
                    $agent = $entretien->getAgent();
                    $dictionnaireEntretien[$agent->getId()][] = $entretien;
                    if (!empty($entretien->getObservateurs())) {
                        foreach ($entretien->getObservateurs() as $observateur) {
                            $dictionnaireObservateur[$agent->getId()][] = $observateur;
                        }
                    }
                }
            }

            //reading
            $array = [];
            $warning = [];

            if ($fichier_path === null or $fichier_path === '') {
                $error[] = "Aucun fichier !";
            } else {
                $handle = fopen($fichier_path, "r");

                while ($content = fgetcsv($handle, 0, ";")) {
                    $array[] = $content;
                }

                $newObservateurs = [];

                foreach ($array as $line) {
                    [$agentId, $observateurId] = $line;

                    $agent = $this->getAgentService()->getAgent($agentId);
                    $observateur = $this->getAgentService()->getAgent($observateurId);

                    if ($agent === null) $error[] = "Aucun·e Agent·e de trouvé·e pour l'identifiant ".$agentId;
                    if ($observateur === null) $error[] = "Aucun·e observateur·trice de trouvé·e pour l'identifiant ".$observateurId;
                    if ($observateur !== null AND $observateur->getUtilisateur() === null) $warning[] = "Aucun·e utilisateur·trice pour l'observateur ".$observateur->getDenomination(true);


                    if ($agent !== null AND $observateur !== null) {
                        $entretiens_ = $dictionnaireEntretien[$agent->getId()]??[];

                        if (empty($entretiens_)) {
                            $warning[] = "L'agent·e ".$agent->getDenomination(true)." n'a pas d'entretien professionnel pour la campagne ".$campagne->getAnnee();
                        } else {
                            $observateurs_ = $dictionnaireObservateur[$agent->getId()]??[];
                            if (!empty($observateurs_)) {
                                foreach ($observateurs_ as $observateur_) {
                                    if ($mode === 'preview') $warning[] = "L'observateur·trice ".$observateur_->getUser()->getDisplayName()." sera historisé·e";
                                    if ($mode === 'import') $warning[] = "L'observateur·trice ".$observateur_->getUser()->getDisplayName()." ont été historisé·e";
                                }
                            }

                            foreach ($entretiens_ as $entretien_) {
                                if ($mode === 'import') {
                                    foreach ($entretien_->getObservateurs() as $observateur_) $this->getObservateurService()->historise($observateur_);
                                }
                                if ($observateur->getUtilisateur()) {
                                    $nobservateur = new Observateur();
                                    $nobservateur->setEntretienProfessionnel($entretien_);
                                    $nobservateur->setUser($observateur->getUtilisateur());
                                    $nobservateur->setDescription("Observateur·trice ajouté·e par importation csv le " . (new DateTime())->format('d/m/Y à H:i:s'));

                                    $newObservateurs[] = $nobservateur;
                                    if ($mode === 'import') $this->getObservateurService()->create($nobservateur);

                                }
                            }
//                            $todoEntretien[$agent->getId()][] = $observateur;
//                            foreach ($entretiens_ as $entretien) {
//                                /** @var Observateur $observateur */

//                            }
                        }
                    }
                }
            }

            $vm = new ViewModel([
                'title' => "Importer des observateurs",
                'form' => $form,
                'warning' => $warning,
                'error' => $error,
                'campagne' => $campagne,
                'entretiens' => $entretiens,
                'data' => $data,
                'newObservateurs' => $newObservateurs,
            ]);
            return $vm;
        }


        $vm = new ViewModel([
            'title' => "Importer des observateurs",
            'form' => $form,
            'campagne' => $campagne,
            'entretiens' => $entretiens,
            'data' => null,
        ]);
        return $vm;
    }

    /** FONCTION DE RECHERCHE *****************************************************************************************/

    public function rechercherAction(): JsonModel
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $observateurs = $this->getObservateurService()->getObservateursByTerm($term);
            $result = $this->getObservateurService()->formatObservateurJSON($observateurs);
            return new JsonModel($result);
        }
        exit;
    }
}