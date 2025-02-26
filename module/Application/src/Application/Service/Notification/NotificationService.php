<?php

namespace Application\Service\Notification;

use Application\Service\Macro\MacroServiceAwareTrait;
use Application\Service\Url\UrlServiceAwareTrait;
use UnicaenMail\Service\Mail\MailServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenRenderer\Service\Rendu\RenduServiceAwareTrait;

class NotificationService
{
    use MacroServiceAwareTrait;
    use MailServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use RenduServiceAwareTrait;
    use UrlServiceAwareTrait;


    public function mergeAdresses(array $adresses): string
    {
        $adresses = array_filter($adresses, function (?string $a) {
            return $a !== null and $a !== '';
        });
        return implode(',', $adresses);
    }

}