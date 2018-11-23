<?php

namespace Application\Service\MailService;

use Application\Entity\Db\Role;
use Application\Entity\Db\User;
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
        $message->setFrom('preecog@unicaen.fr', "PrEECoG (ne pas répondre)");

        if (!is_array($to)) $to = [ $to ];
        if ($this->doNotSend) {
            $message->addTo($this->redirectTo);
        } else {
            $message->addTo($to);
        }



        $sujet = '[PrEECoG] ' . $subject;
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
//        $nom     = $utilisateur->getDisplayName();
//        $message->setTo($adresse,$nom);

        $subject = "";
        $texte   = "";
        if ($action === "ajout") {
            $subject = "Attribution du rôle ". $role->getRoleId().".";

            $texte  = "<p>Le rôle de <stong>".$role->getRoleId()."</stong> vient de vous être attribué.</p>";
            $texte .= "<br/>";
            $texte .= "<p>Veuillez ne pas répondre à ce message automatique.</p>";
            $texte .= "<p>Cordialement, <br/>L'équipe du catalogue de services</p>";
        }
        if ($action === "retrait") {
            $subject = "Retrait du rôle ". $role->getRoleId().".";

            $texte  = "<p>Le rôle de <stong>".$role->getRoleId()."</stong> vient de vous être retiré.</p>";
            $texte .= "<br/>";
            $texte .= "<p>Veuillez ne pas répondre à ce message automatique.</p>";
            $texte .= "<p>Cordialement, <br/>L'équipe du catalogue de services</p>";
        }

        $this->sendMail($adresse, $subject, $texte);
    }

}