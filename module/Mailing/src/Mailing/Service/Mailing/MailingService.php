<?php

namespace Mailing\Service\Mailing;

use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Mailing\Model\Db\Mail;
use Mailing\Service\MailType\MailTypeServiceAwareTrait;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenRenderer\Service\Contenu\ContenuServiceAwareTrait;
use UnicaenUtilisateur\Entity\DateTimeAwareTrait;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Mail\Message;
use Zend\Mail\Transport\TransportInterface;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Mime;
use Zend\Mime\Part;
use Zend\View\Renderer\PhpRenderer;

class MailingService
{
    use EntityManagerAwareTrait;
    use ContenuServiceAwareTrait;
    use MailTypeServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use UserServiceAwareTrait;
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

    /** GESTION DE L'ENTITE *******************************************************************************************/
    /**
     * @param Mail $mail
     * @return Mail
     */
    public function create(Mail $mail) : Mail
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
    public function update(Mail $mail) : Mail
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
     * @return Mail
     */
    public function delete(Mail $mail) : Mail
    {
        try {
            $this->getEntityManager()->remove($mail);
            $this->getEntityManager()->flush();
        } catch (ORMException $e) {
            throw new RuntimeException("Problème lors de la destruction du mail", $e);
        }
        return $mail;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(Mail::class)->createQueryBuilder('mail');
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return Mail[]
     */
    public function getMails(string $champ = 'id', string $ordre = 'DESC') : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('mail.' . $champ,  $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param int $mailId
     * @return Mail|null
     */
    public function getMail(int $mailId) : ?Mail
    {
        $qb = $this->createQueryBuilder()
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
     * @param string $type
     * @param int $id
     * @param string $champ
     * @param string $ordre
     * @return Mail[]
     */
    public function getMailsByAttachement(string $type, int $id, string $champ = 'id', string $ordre = 'DESC') : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('mail.attachementType = :type')
            ->andWhere('mail.attachementId = :id')
            ->setParameter('type', $type)
            ->setParameter('id', $id)
            ->orderBy('mail.' . $champ,  $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $term
     * @return array
     */
    public function findAdressetByTerm(string $term) {
        $qb = $this->createQueryBuilder()
            ->select('mail.destinataires')
            ->andWhere("LOWER(mail.destinataires) like :search")
            ->setParameter('search', '%'.strtolower($term).'%')
            ->groupBy('mail.destinataires');
        $result = $qb->getQuery()->getResult();

        $adresses = [];
        foreach ($result as $item) $adresses[] = $item['destinataires'];
        return $adresses;
    }

    public function formatAdresseJSON(array $adresses)
    {
        $position = 1;
        $result = [];
        foreach ($adresses as $adresse) {
            $extra = "";
            $result[] = array(
                'id' => $position++,
                'label' => $adresse,
                'extra' => $extra,
            );
        }
        usort($result, function ($a, $b) {
            return strcmp($a['label'], $b['label']);
        });
        return $result;
    }

    /**
     * @param array $filtre
     * @param string $champ
     * @param string $ordre
     * @return Mail[]
     */
    public function getMailsWithFiltre(array $filtre, string $champ = 'id', string $ordre = 'DESC') : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('mail.' . $champ, $ordre);
    ;
        if (isset($filtre['adresse']) AND $filtre['adresse'] != '') {
            $qb = $qb->andWhere('mail.destinataires = :adresse')->setParameter('adresse', $filtre['adresse']);
        }
        if (isset($filtre['etat']) AND $filtre['etat'] != '') {
            $qb = $qb->andWhere('mail.statusEnvoi = :etat')->setParameter('etat', $filtre['etat']);
        }
        if (isset($filtre['type']) AND $filtre['type'] != '') {
            $qb = $qb->andWhere('mail.mailtype_id = :type')->setParameter('type', $filtre['type']);
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** FONCTION D'ENVOI DE MAIL **************************************************************************************/

    /**
     * @param $to
     * @param $subject
     * @param $texte
     * @param null $attachement_path
     * @return Mail
     */
    public function sendMail($to, $subject, $texte, $attachement_path = null)
    {
        $message = (new Message())->setEncoding('UTF-8');

        $email = 'ne-pas-repondre@unicaen.fr';
        if ($this->getParametreService()->getParametreByCode('GLOBAL','EMAIL') AND $this->getParametreService()->getParametreByCode('GLOBAL','EMAIL')->getValeur() !== null) {
            $email = $this->getParametreService()->getParametreByCode('GLOBAL','EMAIL')->getValeur();
        }
        $name = 'Application';
        if ($this->getParametreService()->getParametreByCode('GLOBAL','NAME') AND $this->getParametreService()->getParametreByCode('GLOBAL','NAME')->getValeur() !== null) {
            $name = $this->getParametreService()->getParametreByCode('GLOBAL','NAME')->getValeur();
        }
        $message->setFrom($email, $name);
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


        $sujet = '['.$name.'] ' . $subject;
        if ($this->doNotSend) {
            $sujet .= ' {REDIR}';
        }
        $message->setSubject($sujet);


        $texte = "<p><i>Ce courrier électronique vous a été adressé <strong>automatiquement</strong> par l'application ".$name.". </i></p>" . $texte;

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
        return $mail;
    }

    public function sendTestMail()
    {
        $user = $this->getUserService()->getConnectedUser();
        $mail = $this->sendMail($user->getEmail(), "Ceci est un test", "Ceci est un test");
        return $mail;
    }

    /**
     * @param Mail $mail
     * @return Mail
     */
    public function reEnvoi(Mail $mail) : Mail
    {
        $mail = $this->sendMail(explode(",", $mail->getDestinatires()), $mail->getSujet(), $mail->getCorps());
        return $mail;
    }

    /**
     * @param Mail $mail
     * @param int $status
     * @return Mail
     */
    public function changerStatus(Mail $mail, int $status) : Mail
    {
        $mail->setStatusEnvoi($status);
        $mail = $this->update($mail);
        return $mail;
    }

    /**
     * @param Mail|null $mail
     * @param string $type
     * @param int $id
     * @return Mail|null
     */
    public function addAttachement(?Mail $mail, string $type, int $id) : ?Mail
    {
        if ($mail === null) return null;
        $mail->setAttachementType($type);
        $mail->setAttachementId($id);
        $this->update($mail);
        return $mail;
    }

    /** MAIL FROM MAIL TYPE *******************************************************************************************/

    /**
     * @param string code
     * @param array $variables
     * @return Mail|null
     */
    public function sendMailType(string $code, array $variables) : ?Mail
    {
        $mailtype = $this->getMailTypeService()->getMailTypeByCode($code);
        $contenu = null;
        if ($mailtype !== null AND $mailtype->getContenu() !== null) {
            $contenu = $this->getContenuService()->generateContenu($code, $variables);
        }

        if ($contenu === null) {
            throw new RuntimeException("Le mail type [" . $code . "] n'existe pas !", 0, null);
        }

        if ($mailtype->isActif()) {
            $sujet = $contenu->getSujet();
            $sujet = html_entity_decode(strip_tags($sujet));
            $corps = $contenu->getCorps();

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

            $mail = $this->sendMail($mails, $sujet, $corps);
            $mail->setMailtypeId($mailtype->getId());
            $this->update($mail);
            return $mail;
        }
        return null;
    }

}