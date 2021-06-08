<?php

namespace Mailing\Service\Mailing;

use Application\Entity\Db\Agent;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use EntretienProfessionnel\Entity\Db\Campagne;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use Formation\Entity\Db\FormationInstance;
use Mailing\Model\Db\Mail;
use Mailing\Service\MailType\MailTypeServiceAwareTrait;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
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


        $texte = "<p><i>Ce courrier électronique vous a été adressé <strong>automatiquement</strong> par l'application EMC2. </i></p>" . $texte;

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
    public function sendMailType($code, array $variables) : ?Mail
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

            $mail = $this->sendMail($mails, $sujet, $corps);
            $mail->setMailtypeId($mailtype->getId());
            $this->update($mail);
            return $mail;
        }
        return null;
    }

    /**
     * @param string|null $texteInitial
     * @param array $variables
     * @return string
     */
    private function replaceMacros(?string $texteInitial, array $variables) : string
    {
        $matches = [];
        preg_match_all('/VAR\[[a-z,A-Z,0-9,#,_]*\]/', $texteInitial, $matches);

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
    private function getReplacementText(string $identifier, array $variables) : string
    {
        /**
         * @var Agent $agent
         * @var Campagne $campagne
         * @var EntretienProfessionnel $entretien
         * @var FormationInstance $instance
         */

        //TODO améliorant en récupérant le entre [] et puis en splittant avec # et tester récupération ou non de l'object ...
        switch ($identifier) {
            /** DATE **************************************************************************************************/
            case 'VAR[DATE#aujourdhui]' :
                $date = $this->getDateTime()->format('d/m/Y');
                return $date;
            /** APPLICATION *******************************************************************************************/
            case 'VAR[EMC2#nom]' :
                $lien = '<strong>EMC2</strong>';
                return $lien;
            case 'VAR[EMC2#lien]' :
                $lien = '<a href="' . 'https://emc2.unicaen.fr' . '">EMC2</a>';
                return $lien;
            /** AGENT *************************************************************************************************/
            case 'VAR[Agent#Denomination]' :
                $agent = $variables['agent'];
                return $agent->getDenomination();
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
            case 'VAR[ENTRETIEN#heure]' :
                $entretien = $variables['entretien'];
                return $entretien->getDateEntretien()->format('H:i');
            case 'VAR[ENTRETIEN#lieu]' :
                $entretien = $variables['entretien'];
                return $entretien->getLieu();
            case 'VAR[ENTRETIEN#lien_accepter]' :
                $entretien = $variables['entretien'];
                return '<a href="'.$this->rendererService->url('entretien-professionnel/accepter-entretien', ['entretien-professionnel' => $entretien->getId(), 'token' => $entretien->getToken()], ['force_canonical' => true], true).'">Acceptation de l\'entretien professionnel</a>';
            case 'VAR[ENTRETIEN#lien_entretien]' :
                $entretien = $variables['entretien'];
                return '<a href="'.$this->rendererService->url('entretien-professionnel/renseigner', ['entretien-professionnel' => $entretien->getId()], ['force_canonical' => true], true).'">Accéder à l\'entretien professionnel</a>';
            /** FORMATION **************************************************************************************************/
            case 'VAR[FORMATION#instance_id]' :
                $instance = $variables['formation-instance'];
                return $instance->getId();
            case 'VAR[FORMATION#libelle]' :
                $instance = $variables['formation-instance'];
                return $instance->getFormation()->getLibelle();
            case 'VAR[FORMATION#debut]' :
                $instance = $variables['formation-instance'];
                return $instance->getDebut();
            case 'VAR[FORMATION#fin]' :
                $instance = $variables['formation-instance'];
                return $instance->getFin();
            case 'VAR[FORMATION#Periode]' :
                $instance = $variables['formation-instance'];
                if ($instance->getDebut() === $instance->getFin()) return $instance->getDebut();
                return $instance->getDebut() ." au ". $instance->getFin();
            case 'VAR[FORMATION#inscription]' :
                $instance = $variables['formation-instance'];
                return ($instance->isAutoInscription())?"libre":"manuelle";
            case 'VAR[FORMATION#lien_session]' :
                $instance = $variables['formation-instance'];
                $url = $this->rendererService->url('formation-instance/afficher', ['formation-instance' => $instance->getId()], ['force_canonical' => true], true);
                $intitule = $instance->getFormation()->getLibelle() ."(#". $instance->getId() .")";
                return '<a href="'.$url.'">'.$intitule.'</a>';
            case 'VAR[FORMATION#Emargements]' :
                $instance = $variables['formation-instance'];
                $url = $this->rendererService->url('formation-instance/export-tous-emargements', ['formation-instance' => $instance->getId()], ['force_canonical' => true], true);
                return "Liste de émargements : <a href='".$url."'> PDF des émargements </a>";
        }
        return '<span style="color:red; font-weight:bold;">Macro inconnu (' . $identifier . ')</span>';
    }
    }