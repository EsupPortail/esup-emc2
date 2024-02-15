<?php

namespace Formation\Controller;

use Formation\Entity\Db\Formateur;
use Formation\Form\Formateur\FormateurFormAwareTrait;
use Formation\Service\Formateur\FormateurServiceAwareTrait;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\Form\User\UserFormAwareTrait;
use UnicaenUtilisateur\Form\User\UserRechercheFormAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class FormateurController extends AbstractActionController
{
    use FormationInstanceServiceAwareTrait;
    use FormateurServiceAwareTrait;
    use UserServiceAwareTrait;
    use FormateurFormAwareTrait;
    use UserFormAwareTrait;
    use UserRechercheFormAwareTrait;

    public function indexAction(): ViewModel
    {
        $params = $this->params()->fromQuery();
        $formateurs = $this->getFormateurService()->getFormateursWithFiltre($params);
        return new ViewModel([
            'formateurs' => $formateurs,
            'params' => $params,
        ]);
    }

    public function afficherAction(): ViewModel
    {
        $formateur = $this->getFormateurService()->getRequestedFormateur($this);
        return new ViewModel([
            'formateur' => $formateur,
        ]);
    }

    public function ajouterAction(): ViewModel
    {

        $formateur = new Formateur();

        $form = $this->getFormateurForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/formateur/ajouter', [], [], true));
        $form->bind($formateur);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormateurService()->create($formateur);
                exit();
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('formation/formateur/modifier');
        $vm->setVariables([
            'title' => "Ajout d'un·e formateur·trice",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction(): ViewModel
    {

        $formateur = $this->getFormateurService()->getRequestedFormateur($this);

        $form = $this->getFormateurForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/formateur/modifier', ['formateur' => $formateur->getId()], [], true));
        $form->bind($formateur);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormateurService()->update($formateur);
                exit();
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('formation/formateur/modifier');
        $vm->setVariables([
            'title' => "Modification d'un·e formateur·trice",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction(): Response
    {
        $formateur = $this->getFormateurService()->getRequestedFormateur($this);
        $this->getFormateurService()->historise($formateur);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('formation/formateur',[],[],true);
    }

    public function restaurerAction(): Response
    {
        $formateur = $this->getFormateurService()->getRequestedFormateur($this);
        $this->getFormateurService()->restore($formateur);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('formation/formateur',[],[],true);
    }

    public function supprimerAction(): ViewModel
    {
        $formateur = $this->getFormateurService()->getRequestedFormateur($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getFormateurService()->delete($formateur);
            exit();
        }

        $vm = new ViewModel();
        if ($formateur !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du formateur de formation du [" . $formateur->getDenomination() . "]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('formation/formateur/supprimer', ["formateur" => $formateur->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** Gestion des comptes utilisateurs associés *********************************************************************/

    public function creerCompteAction(): ViewModel
    {
        $formateur = $this->getFormateurService()->getRequestedFormateur($this);

        $user = new User();
        $user->setEmail($formateur->getEmail());
        $user->setPassword('db');
        $user->setState(1);

        $form = $this->getUserForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/formateur/creer-compte', ['formateur' => $formateur->getId()], [], true));
        $form->bind($user);
        if ($formateur->getType() === Formateur::TYPE_FORMATEUR) {
            $form->get('prenom')->setValue($formateur->getPrenom());
            $form->get('nom')->setValue($formateur->getNom());
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->userService->createLocal($user);
                $formateur->setUtilisateur($user);
                $this->getFormateurService()->update($formateur);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Créer et associer un compte",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function associerCompteAction(): ViewModel
    {
        $formateur = $this->getFormateurService()->getRequestedFormateur($this);
        $form = $this->getUserRechercheForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/formateur/associer-compte', ['formateur' => $formateur->getId()], [], true));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                [$source, $id] = explode('||', $data['utilisateurId']);
                $user = null;
                if ($source === 'app') $user = $this->getUserService()->find($id);
                if ($user !== null) {
                    $formateur->setUtilisateur($user);
                    $this->getFormateurService()->update($formateur);
                }
                exit();
            }
        }

        $js =<<<EOS
$(function () {
$('#utilisateur')
            .autocompleteUnicaen({
                elementDomId: 'utilisateur-id',
                source: '/utilisateur/rechercher',
                html: true,
                minLength: 3,
                delay: 750,
                search: function (event, ui) {
                    $('#selectionner').hide();
                },
                select: function (event, ui) {
                    let id = ui.item.id;
                    let label = ui.item.label;
                    $('#utilisateur').val(label);
                    $('#utilisateur-id').val(id);
                    const t = id.split("||");
                    if (t[0] === 'app') {
                        text = '<i class="icon icon-link"></i> Associer l\'utilisateur';
                    }
                    $('#selectionner').html(text).show();

                    return false;
                },
            })
            .autocomplete("instance")._renderItem = function (ul, item) {
            let template = '<span id=\"{id}\">{label} <span class=\"extra\">{extra}</span></span>';
            let markup = template
                .replace('{id}', item.id ? item.id : '')
                .replace('{label}', item.label ? item.label : '')
                .replace('{extra}', item.extra ? item.extra : '');
            markup = '<a id="autocomplete-item-' + item.id + '">' + markup + "</a>";
            let li = $("<li></li>");
            if (item.id) {
                const t = item.id.split("||");
                if (t[0] == 'app') {
                    li.addClass('bg-success');
                }
            }
            li = li.data("item.autocomplete", item).append(markup).appendTo(ul);
            // mise en évidence du motif dans chaque résultat de recherche
            highlight($('#utilisateur').val(), li, 'sas-highlight');
            // si l'item ne possède pas d'id, on fait en sorte qu'il ne soit pas sélectionnable
            if (!item.id) {
                li.click(function () {
                    return false;
                });
            }

            return li;
        };
});
EOS;


        $vm = new ViewModel([
            'title' => "Assocation d'un compte existant",
            'form' => $form,
            'js' => $js,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function deassocierCompteAction(): Response
    {
        $formateur = $this->getFormateurService()->getRequestedFormateur($this);
        $formateur->setUtilisateur(null);
        $this->getFormateurService()->update($formateur);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('formation/formateur', [], [], true);
    }

    /** Fonctions de recherche *****************************************************************************************/

    public function rechercherAction(): JsonModel
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $formateurs = $this->getFormateurService()->getFormateursByTerm($term);
            $result = $this->getFormateurService()->formatFormateursJSON($formateurs);
            return new JsonModel($result);
        }
        exit;
    }

    public function rechercherRattachementAction(): JsonModel
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $rattachements = $this->getFormateurService()->getRattachementByTerm($term);
            $result = $this->getFormateurService()->formatRattachementsJSON($rattachements);
            return new JsonModel($result);
        }
        exit;
    }
}