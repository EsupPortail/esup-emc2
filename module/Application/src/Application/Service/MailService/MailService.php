<?php

namespace Application\Service\MailService;

use Application\Entity\Db\Fiche;
use Application\Entity\Db\Role;
use Application\Entity\Db\Service;
use Application\Entity\Db\User;
use Application\Service\CommonServiceAbstract;
use UnicaenAuth\Service\UserContext;
use Zend\Mail\Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mail\Transport\TransportInterface;
use Zend\Mime\Mime;
use Zend\Mime\Part;
use Zend\View\Renderer\PhpRenderer;

class MailService {

    /** @var TransportInterface */
    private $transport;
    private $redirectTo;
    private $doNotSend;

    /** @var PhpRenderer */
    public $rendererService;

    public function __construct(TransportInterface $transport, $redirectTo, $doNotSend)
    {
        $this->transport    = $transport;
        $this->redirectTo   = $redirectTo;
        $this->doNotSend    = $doNotSend;
    }

    /**
     * //TODO stocker en base les envois de mails
     * @return null|string
     */
    public function getEntityClass()
    {
        return null;
    }

    public function sendMail($to, $subject, $texte) {

        $message = (new Message())->setEncoding('UTF-8');
        $message->setFrom('catalogue-services@unicaen.fr', "Catalogue de services");

        if (!is_array($to)) $to = [ $to ];
        if ($this->doNotSend) {
            $message->addTo($this->redirectTo);
        } else {
            $message->addTo($to);
        }



        $sujet = '[catalogue-services] ' . $subject;
        if ($this->doNotSend) {
            $sujet .= ' {REDIR}';
        }
        $message->setSubject($sujet);

        if ($this->doNotSend) {
            $texte .= "<br/>";
            $texte .= "Initialement envoyé à :";
            $texte .= "<ul>";
            foreach ($to as $t) $texte .= "<li>".$t."</li>";
            $texte .= "</ul>";

        }

        $part = new Part($texte);
        $part->type = Mime::TYPE_HTML;
        $part->charset = 'UTF-8';
        $body = new MimeMessage();
        $body->addPart($part);
        $message->setBody($body);

        $this->transport->send($message);
    }

    public function sendTestMail() {
        /** @var UserContext $userContext */
        $userContext = $this->getServiceLocator()->get('UnicaenAuth\Service\UserContext');
        $currentUser = $userContext->getDbUser();
        $adresse = $userContext->getLdapUser()->getEmail();

        $texte  = "<p>Ceci est un courrier électronique de test envoyé par l'application <strong>Catalogue de services</strong> à <strong>".$currentUser->getDisplayName()."</strong> (<em>".$currentUser->getEmail()."</em>).</p>";
        $texte .= "<br/>";
        $texte .= "<p>Veuillez ne pas répondre à ce message automatique.</p>";
        $texte .= "<p>Cordialement, <br/>L'équipe du catalogue de services</p>";

        $this->sendMail($adresse, 'Courrier électronique de test', $texte);
    }

    /**
     * @param string $action
     * @param Role $role
     * @param User $utilisateur
     */
    public function sendChangementRole($action, $role, $utilisateur)
    {
        $message = (new Message())->setEncoding('UTF-8');
        $adresse = $utilisateur->getEmail();
        $nom     = $utilisateur->getDisplayName();
        $message->setFrom('catalogue-services@unicaen.fr', "Catalogue de services");
        $message->setTo($adresse,$nom);

        $texte = "";
        if ($action === "ajout") {
            $message->setSubject("[catalogue-services] Attribution du rôle ". $role->getRoleId());

            $texte  = "<p>Le rôle de <stong>".$role->getRoleId()."</stong> vient de vous être attribué.</p>";
            $texte .= "<br/>";
            $texte .= "<p>Veuillez ne pas répondre à ce message automatique.</p>";
            $texte .= "<p>Cordialement, <br/>L'équipe du catalogue de services</p>";
        }
        if ($action === "retrait") {
            $message->setSubject("[catalogue-services] Retrait du rôle ". $role->getRoleId());

            $texte  = "<p>Le rôle de <stong>".$role->getRoleId()."</stong> vient de vous être retiré.</p>";
            $texte .= "<br/>";
            $texte .= "<p>Veuillez ne pas répondre à ce message automatique.</p>";
            $texte .= "<p>Cordialement, <br/>L'équipe du catalogue de services</p>";
        }

        // corps du message au format HTML
        $part = new Part($texte);
        $part->type = Mime::TYPE_HTML;
        $part->charset = 'UTF-8';
        $body = new MimeMessage();
        $body->addPart($part);
        $message->setBody($body);

        $this->transport->send($message);
    }

    /**
     * @param User $user
     * @param Service $service
     */
    public function notifierNouveauRedacteur($user, $service)
    {
        $vars = [
            'role' => 'rédacteur',
            'sevice' => $service->getLibelle(),
        ];
        $sujet = 'Vous êtes maintenant un des rédacteurs du service "'.$service->getLibelle().'"';

        //$message = $this->rendererService->render(  'application/mail/attribution', $vars);
        $message  = "<p>";
        $message .= "   Bonjour,";
        $message .= "</p>";
        $message .= "<p>";
        $message .= "   Vous venez d'être nommé&#183;e comme <strong>".$vars['role']."</strong> du service <strong>".$service->getLibelle()."</strong>.";
        $message .= "   Vous pouvez maintenant :";
        $message .= "<ul>";
        $message .= "    <li> rédiger les fiches associées à ce service ;</li>";
        $message .= "    <li> éditer les fiches associées à ce service ;</li>";
        $message .= "    <li> demander la validation des fiches associées à ce service ;</li>";
        $message .= "</ul>";
        $message .= "</p>";
        $message .= "<p>";
        $message .= "Cordialement, <br/>";
        $message .= "L'équipe du catalogue de services";
        $message .= "</p>";
        $message .= "<p>";
        $message .=  "<i>Merci de ne pas répondre à ce mail envoyé automatiquement par l'application.</i>";
        $message .= "</p>";

        $this->sendMail($user->getEmail(), $sujet, $message);
    }

    public function notifierNouveauValidateur($user, $service)
    {
        $vars = [
            'role' => 'validateur',
            'sevice' => $service->getLibelle(),
        ];
        $sujet = 'Vous êtes maintenant un des rédacteurs du service "'.$service->getLibelle().'"';

        //$message = $this->rendererService->render(  'application/mail/attribution', $vars);
        $message  = "<p>";
        $message .= "   Bonjour,";
        $message .= "</p>";
        $message .= "<p>";
        $message .= "   Vous venez d'être nommé&#183;e comme <strong>".$vars['role']."</strong> du service <strong>".$service->getLibelle()."</strong>.";
        $message .= "   Vous pouvez maintenant :";
        $message .= "<ul>";
        $message .= "    <li> rédiger les fiches associées à ce service ;</li>";
        $message .= "    <li> éditer les fiches associées à ce service ;</li>";
        $message .= "    <li> demander la validation des fiches associées à ce service ;</li>";
        $message .= "    <li> valider des fiches associées à ce service ;</li>";
        $message .= "</ul>";
        $message .= "</p>";
        $message .= "<p>";
        $message .= "Cordialement, <br/>";
        $message .= "L'équipe du catalogue de services";
        $message .= "</p>";
        $message .= "<p>";
        $message .=  "<i>Merci de ne pas répondre à ce mail envoyé automatiquement par l'application.</i>";
        $message .= "</p>";

        $this->sendMail($user->getEmail(), $sujet, $message);

    }

    public function notifierPerteRedacteur($user, $service)
    {
        $vars = [
            'role' => 'rédacteur',
            'sevice' => $service->getLibelle(),
        ];
        $sujet = 'Vous n\'êtes plus un des rédacteurs du service "'.$service->getLibelle().'"';

        $message  = "<p>";
        $message .= "   Bonjour,";
        $message .= "</p>";
        $message .= "<p>";
        $message .= "   Vous venez de perdre le rôle de <strong>".$vars['role']."</strong> du service <strong>".$service->getLibelle()."</strong>.";
        $message .= "<p>";
        $message .= "Cordialement, <br/>";
        $message .= "L'équipe du catalogue de services";
        $message .= "</p>";
        $message .= "<p>";
        $message .=  "<i>Merci de ne pas répondre à ce mail envoyé automatiquement par l'application.</i>";
        $message .= "</p>";

        $this->sendMail($user->getEmail(), $sujet, $message);

    }

    public function notifierPerteValidateur($user, $service)
    {
        $vars = [
            'role' => 'validateur',
            'sevice' => $service->getLibelle(),
        ];
        $sujet = 'Vous n\'êtes plus un des rédacteurs du service "'.$service->getLibelle().'"';

        //$message = $this->rendererService->render(  'application/mail/attribution', $vars);
        $message  = "<p>";
        $message .= "   Bonjour,";
        $message .= "</p>";
        $message .= "<p>";
        $message .= "   Vous venez de perdre le rôle de <strong>".$vars['role']."</strong> du service <strong>".$service->getLibelle()."</strong>.";
        $message .= "</p>";
        $message .= "<p>";
        $message .= "Cordialement, <br/>";
        $message .= "L'équipe du catalogue de services";
        $message .= "</p>";
        $message .= "<p>";
        $message .=  "<i>Merci de ne pas répondre à ce mail envoyé automatiquement par l'application.</i>";
        $message .= "</p>";

        $this->sendMail($user->getEmail(), $sujet, $message);

    }

    /**
     * @param User $currentUser
     * @param Fiche $fiche
     */
    public function validationDemander($currentUser, $fiche)
    {
        $sujet = 'Demande de validation de la fiche "'.$fiche->getTitre().'"';

        $url = $this->rendererService->url('fiche/gerer', ['fiche' => $fiche->getId(), 'langue' => $fiche->getLangue()->getNom()], ['force_canonical' => true], true);

        $to = [];
        $validateurs = $fiche->getService()->getValidateur();
        /** @var User $validateur */
        foreach ($validateurs as $validateur) {
            $to[] = $validateur->getEmail();
        }

        $message  = "<p>";
        $message .= "   Bonjour,";
        $message .= "</p>";
        $message .= "<p>";
        $message .= $currentUser->getDisplayName().' vient de demander la validation de la fiche "'.$fiche->getTitre().'". ';
        $message .= "Vous pouvez dés maintenant vous rendre dans l'application pour valider celle-ci.";
        $message .= "</p>";
        $message .= "<p>";
        $message .= "Vous pouvez consulter cette fiche à l'adresse suivante : ". $url;
        $message .= "</p>";
        $message .= "Cordialement, <br/>";
        $message .= "L'équipe du catalogue de services";
        $message .= "</p>";
        $message .= "<p>";
        $message .=  "<i>Merci de ne pas répondre à ce mail envoyé automatiquement par l'application.</i>";
        $message .= "</p>";


        $this->sendMail($to, $sujet, $message);
    }

    /**
     * @param User $currentUser
     * @param Fiche $fiche
     */
    public function miseEnLigne($currentUser, $fiche)
    {
        $sujet = 'Mise en ligne de la fiche "'.$fiche->getTitre().'"';

        $to = [];
        $validateurs = $fiche->getService()->getValidateur();
        $redacteurs = $fiche->getService()->getRedacteur();
        $users = array_merge($validateurs->toArray(), $redacteurs->toArray());
        /** @var User $user */
        foreach ($users as $user) {
            $to[] = $user->getEmail();
        }

        $message  = "<p>";
        $message  = "<p>";
        $message .= "   Bonjour,";
        $message .= "</p>";
        $message .= "<p>";
        $message .= $currentUser->getDisplayName().' vient de mettre en ligne la fiche "'.$fiche->getTitre().'". ';
        $message .= "Vous pouvez dés maintenant consulter celle-ci dans le catalogue de services.";
        $message .= "<p>";
        $message .= "Cordialement, <br/>";
        $message .= "L'équipe du catalogue de services";
        $message .= "</p>";
        $message .= "<p>";
        $message .=  "<i>Merci de ne pas répondre à ce mail envoyé automatiquement par l'application.</i>";
        $message .= "</p>";

        $this->sendMail($to, $sujet, $message);
    }

    public function refuserFicher($currentUser, $fiche, $remarque = null)
    {
        $sujet = 'Refus de la fiche "'.$fiche->getTitre().'"';

        $to = [];
        $validateurs = $fiche->getService()->getValidateur();
        $redacteurs = $fiche->getService()->getRedacteur();
        $users = array_merge($validateurs->toArray(), $redacteurs->toArray());
        /** @var User $user */
        foreach ($users as $user) {
            $to[] = $user->getEmail();
        }

        $url = $this->rendererService->url('fiche/gerer', ['fiche' => $fiche->getId(), 'langue' => $fiche->getLangue()->getNom()], ['force_canonical' => true], true);

        $message  = "<p>";
        $message .= "   Bonjour,";
        $message .= "</p>";
        $message .= "<p>";
        $message .= $currentUser->getDisplayName().' vient de refuser la fiche "<a href="'.$url.'">'.$fiche->getTitre().'</a>". ';
        $message .= "Cette fiche n'est de nouveau à l'état de brouillon dans le catalogue de services.";
        $message .= "Merci de modifier celle-ci en tenant compte de la remarque du validateur : <strong>".$remarque."</strong>.";
        $message .= "<p>";
        $message .= "Cordialement, <br/>";
        $message .= "L'équipe du catalogue de services";
        $message .= "</p>";
        $message .= "<p>";
        $message .=  "<i>Merci de ne pas répondre à ce mail envoyé automatiquement par l'application.</i>";
        $message .= "</p>";

        $this->sendMail($to, $sujet, $message);
    }
}