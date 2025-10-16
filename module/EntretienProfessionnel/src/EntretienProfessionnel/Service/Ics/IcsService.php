<?php

namespace EntretienProfessionnel\Service\Ics;

use Application\Provider\Parametre\GlobalParametres;
use DateInterval;
use DateTime;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;

class IcsService
{
    use ParametreServiceAwareTrait;

    public function generateInvitation(EntretienProfessionnel $e): string
    {
        $now = new DateTime();
        $debut = $e->getDateEntretien();
        $dureeEnMinute = $e->getDureeEstimee()*60;
        $fin = DateTime::createFromFormat("YmdHis", $e->getDateEntretien()->format("YmdHis"))->add(new DateInterval('PT'.$dureeEnMinute.'M'));
        $lieu = $e->getLieu();
        $agentDenomination = trim($e->getAgent()->getDenomination());
        $agentEmail = $e->getAgent()->getEmail();
        $responsableDenomination = trim($e->getResponsable()->getDenomination());
        $responsableEmail = $e->getResponsable()->getEmail();

        $uid = "EMC2-campagne-" . $e->getCampagne()->getId() . "-entretien-id" . $e->getId();
        $titre = "Entretien professionnel de " . $agentDenomination . " pour la campagne " . $e->getCampagne()->getAnnee();
        $description = $titre . " - Responsable d'entretien professionnel :" . $responsableDenomination;
        $createdAt = $now->format('Ymd') . "T" . $now->format('His') . "Z";
        $startAt = $debut->format('Ymd') . "T" . $debut->format('His');
        $endAt = $fin->format('Ymd') . "T" . $fin->format('His');

        $ics = <<<EOS
BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//EMC2//NONSGML v1.0//EN
METHOD:REQUEST
BEGIN:VTIMEZONE
TZID:Europe/Paris
BEGIN:STANDARD
DTSTART:20241029T030000
TZOFFSETFROM:+0200
TZOFFSETTO:+0100
TZNAME:CET
END:STANDARD
BEGIN:DAYLIGHT
DTSTART:20250330T020000
TZOFFSETFROM:+0100
TZOFFSETTO:+0200
TZNAME:CEST
END:DAYLIGHT
END:VTIMEZONE
BEGIN:VEVENT
UID:$uid
DTSTAMP:$createdAt
DTSTART;TZID=Europe/Paris:$startAt
DTEND;TZID=Europe/Paris:$endAt
SUMMARY:$titre
DESCRIPTION:$description
LOCATION:$lieu
ORGANIZER;CN=$responsableDenomination:mailto:$responsableEmail
ATTENDEE;CN=$agentDenomination;RSVP=TRUE;ROLE=REQ-PARTICIPANT:mailto:$agentEmail
STATUS:CONFIRMED
END:VEVENT
END:VCALENDAR
EOS;

        $filepath = 'upload/event_' . ((new DateTime())->format('YmdHis')) . '.ics';
        file_put_contents($filepath, $ics);
        return $filepath;
    }

    public function generateAnnulation(EntretienProfessionnel $e): string
    {
        $now = new DateTime();
        $debut = $e->getDateEntretien();
        $fin = DateTime::createFromFormat("YmdHis", $e->getDateEntretien()->format("YmdHis"))->add(new DateInterval('PT2H'));
        $lieu = $e->getLieu();
        $agentDenomination = trim($e->getAgent()->getDenomination());
        $agentEmail = $e->getAgent()->getEmail();
        $responsableDenomination = trim($e->getResponsable()->getDenomination());
        $responsableEmail = $e->getResponsable()->getEmail();


        $uid = "EMC2-campagne-" . $e->getCampagne()->getId() . "-entretien-id" . $e->getId();
        $titre = "Entretien professionnel de " . $agentDenomination . " pour la campagne " . $e->getCampagne()->getAnnee();
        $description = $titre . " - Responsable d'entretien professionnel :" . $responsableDenomination;
        $createdAt = $now->format('Ymd') . "T" . $now->format('His') . "Z";
        $startAt = $debut->format('Ymd') . "T" . $debut->format('His');
        $endAt = $fin->format('Ymd') . "T" . $fin->format('His');

        $ics = <<<EOS
BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//EMC2//NONSGML v1.0//EN
CALSCALE:GREGORIAN
METHOD:CANCEL
BEGIN:VEVENT
UID:$uid
DTSTAMP:$createdAt
DTSTART;TZID=Europe/Paris:$startAt
DTEND;TZID=Europe/Paris:$endAt
SUMMARY:$titre
DESCRIPTION:$description
LOCATION:$lieu
ORGANIZER;CN=$responsableDenomination:mailto:$responsableEmail
ATTENDEE;CN=$agentDenomination;RSVP=TRUE;ROLE=REQ-PARTICIPANT:mailto:$agentEmail
STATUS:CANCELLED
SEQUENCE:9999
END:VEVENT
END:VCALENDAR
EOS;

        $filepath = 'upload/event_' . ((new DateTime())->format('YmdHis')) . '.ics';
        file_put_contents($filepath, $ics);
        return $filepath;
    }
}

