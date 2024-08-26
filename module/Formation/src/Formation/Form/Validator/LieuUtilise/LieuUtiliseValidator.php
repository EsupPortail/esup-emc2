<?php

namespace Formation\Form\Validator\LieuUtilise;

use DateTime;
use Formation\Entity\Db\Seance;
use Formation\Service\Lieu\LieuServiceAwareTrait;
use Laminas\Validator\AbstractValidator;

class LieuUtiliseValidator extends AbstractValidator
{
    use LieuServiceAwareTrait;

     const UTILISE = 'UTILISE';

    protected array $messageTemplates = [
        self::UTILISE => "Un probleme %value% : %texte%" ,
    ];

    protected array $messageVariables = [
        'texte' => "test",
    ];

    protected $test = "ceci est un test";

    public function isValid($value): bool
    {


        $this->setValue("<ul><li>Choucroute</li><li>Cassoulet</li></ul>");
//        $lieux = $this->getLieuService()->getLieux($value);
        $this->error(self::UTILISE);
        $this->messageTemplates[self::UTILISE] = "<ul><li>Choucroute</li><li>Cassoulet</li></ul>";

        return false;
//        if ($context['type'] === Seance::TYPE_VOLUME) return true;
//        $lieu = $this->getLieuService()->getLieu($context['lieu-sas']['id']);
//        $dateDebut =  DateTime::createFromFormat('d/m/Y H:i', $context['jour'].' '.$context['debut']);
//        $dateFin =  DateTime::createFromFormat('d/m/Y H:i', $context['jour'].' '.$context['fin']);
//        if ($lieu !== null) {
//            $seances = $lieu->isUtilisee($dateDebut, $dateFin);
//            if (empty($seances)) {
//                $this->messageVariables["liste"] = "choucroute";
//            }
//        }
//        return (($lieu !== null) AND empty($seances));
    }
}