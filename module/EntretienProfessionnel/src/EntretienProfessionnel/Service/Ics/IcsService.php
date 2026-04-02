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
        $fin = $e->getDateFin();
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

BEGIN:DAYLIGHT
DTSTART:19700329T020000
RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=-1SU
TZOFFSETFROM:+0100
TZOFFSETTO:+0200
TZNAME:CEST
END:DAYLIGHT

BEGIN:STANDARD
DTSTART:19701025T030000
RRULE:FREQ=YEARLY;BYMONTH=10;BYDAY=-1SU
TZOFFSETFROM:+0200
TZOFFSETTO:+0100
TZNAME:CET
END:STANDARD

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
        $fin = $e->getDateFin();
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

BEGIN:DAYLIGHT
DTSTART:19700329T020000
RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=-1SU
TZOFFSETFROM:+0100
TZOFFSETTO:+0200
TZNAME:CEST
END:DAYLIGHT

BEGIN:STANDARD
DTSTART:19701025T030000
RRULE:FREQ=YEARLY;BYMONTH=10;BYDAY=-1SU
TZOFFSETFROM:+0200
TZOFFSETTO:+0100
TZNAME:CET
END:STANDARD

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

