<?php

namespace Mailing\Service\Mailing;

use Application\Entity\Db\EntretienProfessionnel;
use Application\Entity\Db\EntretienProfessionnelCampagne;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Mailing\Model\Db\Mail;
use Mailing\Service\MailType\MailTypeServiceAwareTrait;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Entity\DateTimeAwareTrait;
use UnicaenUtilisateur\Entity\Db\User;
use Zend\Mail\Message;
use Zend\Mail\Transport\TransportInterface;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Mime;
use Zend\Mime\Part;
use Zend\View\Renderer\PhpRenderer;

class MailingService
{
    use EntityManagerAwareTrait;
    use MailTypeServiceAwareTrait;
    use DateTimeAwareTrait;

    /** @var TransportInterface */
    private $transport;
    private $redirectTo;
    private $doNotSend;

    /** @var PhpRenderer */
    public $rendererService;

    public function __construct(TransportInterface $transport, $redirectTo, $doNotSend)
    {
        $this->transport = $transport;
        $this->redirectTo = $redirectTo;
        $this->doNotSend = $doNotSend;
    }

    /**
     * @param Mail $mail
     */
    public function reEnvoi($mail)
    {
        $this->sendMail(explode(",", $mail->getDestinatires()), $mail->getSujet(), $mail->getCorps());
    }

    public function sendMail($to, $subject, $texte, $attachement_path = null)
    {
//        return true;

        $message = (new Message())->setEncoding('UTF-8');
        $message->setFrom('ne-pas-repondre@unicaen.fr', "PrEECoG");
        if (!is_array($to)) $to = [$to];
        if ($this->doNotSend) {
            $message->addTo($this->redirectTo);
        } else {
            $to = array_unique($to);
            $message->addTo($to);
        }

        $mail = new Mail();
        $mail->setDateEnvoi(new DateTime());
        $mail->setStatusEnvoi(Mail::PENDING);
        $mail->setDestinatires(is_array($to) ? implode(",", $to) : $to);
        $mail->setRedir($this->doNotSend);
        $mail->setSujet($subject);
        $mail->setCorps($texte);
        $this->create($mail);


        $sujet = '[PrEECoG] ' . $subject;
        if ($this->doNotSend) {
            $sujet .= ' {REDIR}';
        }
        $message->setSubject($sujet);


        $texte = "<p><i>Ce courrier électronique vous a été adressé <strong>automatiquement</strong> par l'application PrEECoG. </i></p>" . $texte;

        if ($this->doNotSend) {
            $texte .= "<br/><br/><hr/><br/>";
            $texte .= "Initialement envoyé à :";
            $texte .= "<ul>";
            foreach ($to as $t) $texte .= "<li>" . $t . "</li>";
            $texte .= "</ul>";

        }

        $parts = [];

        $part = new Part($texte);
        $part->type = Mime::TYPE_HTML;
        $part->charset = 'UTF-8';
        $parts[] = $part;

        if ($attachement_path) {
            $attachement = new Part();
            $attachement->setType("application/pdf");
            $attachement->setContent(file_get_contents($attachement_path));
            $filename = explode('/', $attachement_path);
            $attachement->setFileName($filename[2]);
            $attachement->setEncoding(Mime::ENCODING_BASE64);
            $attachement->setDisposition(Mime::DISPOSITION_ATTACHMENT);
            $parts[] = $attachement;
        }

        $body = new MimeMessage();
        $body->setParts($parts);
        $message->setBody($body);

        $this->transport->send($message);

        $this->changerStatus($mail, Mail::SUCCESS);

    }

    /**
     * @return Mail[]
     */
    public function getMails()
    {
        $qb = $this->getEntityManager()->getRepository(Mail::class)->createQueryBuilder('mail')
            ->orderBy('mail.id', 'DESC');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param int $mailId
     * @return Mail
     */
    public function getMail($mailId)
    {
        $qb = $this->getEntityManager()->getRepository(Mail::class)->createQueryBuilder('mail')
            ->andWhere('mail.id = :id')
            ->setParameter('id', $mailId);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs mails ont le même identifiant");
        }

        return $result;
    }

    /**
     * @param Mail $mail
     * @return Mail
     */
    public function create($mail)
    {
        try {
            $this->getEntityManager()->persist($mail);
            $this->getEntityManager()->flush($mail);
        } catch (ORMException $e) {
            throw new RuntimeException("Problème lors de la création du mail", $e);
        }
        return $mail;
    }

    /**
     * @param Mail $mail
     * @return Mail
     */
    public function update($mail)
    {
        try {
            $this->getEntityManager()->flush($mail);
        } catch (ORMException $e) {
            throw new RuntimeException("Problème lors de la mise à jour du mail", $e);
        }
        return $mail;
    }

    /**
     * @param Mail $mail
     */
    public function delete($mail)
    {
        try {
            $this->getEntityManager()->remove($mail);
            $this->getEntityManager()->flush();
        } catch (ORMException $e) {
            throw new RuntimeException("Problème lors de la destruction du mail", $e);
        }
    }

    /**
     * @param Mail $mail
     * @param int $status
     * @return Mail
     */
    public function changerStatus($mail, $status)
    {
        $mail->setStatusEnvoi($status);
        $mail = $this->update($mail);
        return $mail;
    }

    /** MAIL FROM MAIL TYPE *******************************************************************************************/

    /**
     * @param string code
     * @param array $variables
     */
    public function sendMailType($code, array $variables)
    {
        $mailtype = $this->getMailTypeService()->getMailTypeByCode($code);
        if ($mailtype === null) {
            throw new RuntimeException("Le mail type [" . $code . "] n'existe pas !", 0, null);
        }

        if ($mailtype->isActif()) {
            $sujet = $mailtype->getSujet();
            $sujet = $this->replaceMacros($sujet, $variables);
            $sujet = html_entity_decode(strip_tags($sujet));

            $corps = $mailtype->getCorps();
            $corps = $this->replaceMacros($corps, $variables);

            $mails = [];

            if (isset($variables['mailing']) and $variables['mailing'] !== "") $mails[] = $variables['mailing'];

            if (isset($variables['user'])) {
                /** @var User $user */
                $destinataire = $variables['user'];
                if (is_array($destinataire)) {
                    foreach ($destinataire as $user) {
                        $mails[] = $user->getEmail();
                    }
                } else {
                    $mails[] = $destinataire->getEmail();
                }
            }

            $this->sendMail($mails, $sujet, $corps);
        }
    }

    /**
     * @param string $texteInitial
     * @param array $variables
     * @return string
     */
    private function replaceMacros($texteInitial, $variables)
    {
        $matches = [];
        preg_match_all('/VAR\[[a-z,A-Z,0-9,#]*\]/', $texteInitial, $matches);

        $patterns = array_unique($matches[0]);
        $replacements = [];
        foreach ($patterns as $pattern) {
            $replacements[] = $this->getReplacementText($pattern, $variables);
        }
        $text = str_replace($patterns, $replacements, $texteInitial);

        return $text;
    }

    /**
     * @param string $identifier
     * @param array $variables
     * @return string
     */
    private function getReplacementText($identifier, $variables)
    {
        /**
         * @var EntretienProfessionnelCampagne $campagne
         * @var EntretienProfessionnel $entretien
         */

        //TODO améliorant en récupérant le entre [] et puis en splittant avec # et tester récupération ou non de l'object ...
        switch ($identifier) {
            /** DATE **************************************************************************************************/
            case 'VAR[DATE#aujourdhui]' :
                $date = $this->getDateTime()->format('d/m/Y');
                return $date;
            /** APPLICATION *******************************************************************************************/
            case 'VAR[PREECOG#lien]' :
                $lien = '<a href="' . 'https://preecog.unicaen.fr' . '">PrEECoG</a>';
                return $lien;
            /** CAMPAGNE **********************************************************************************************/
            case 'VAR[CAMPAGNE#annee]' :
                $campagne = $variables['campagne'];
                return $campagne->getAnnee();
            case 'VAR[CAMPAGNE#debut]' :
                $campagne = $variables['campagne'];
                return $campagne->getDateDebut()->format('d/m/Y');
            case 'VAR[CAMPAGNE#fin]' :
                $campagne = $variables['campagne'];
                return $campagne->getDateFin()->format('d/m/Y');
            /** ENTRETIEN *********************************************************************************************/
//                {title: 'Entretien : date', description: 'Date de l\'entretien', content: 'VAR[ENTRETIEN#date]'},
            case 'VAR[ENTRETIEN#agent]' :
                $entretien = $variables['entretien'];
                return $entretien->getAgent()->getDenomination();
            case 'VAR[ENTRETIEN#responsable]' :
                $entretien = $variables['entretien'];
                return $entretien->getResponsable()->getDisplayName();
            case 'VAR[ENTRETIEN#date]' :
                $entretien = $variables['entretien'];
                return $entretien->getDateEntretien()->format('d/m/Y');
        }
        return '<span style="color:red; font-weight:bold;">Macro inconnu (' . $identifier . ')</span>';
    }
}