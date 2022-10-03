<?php

namespace Structure\Service\Notification;

use Application\Constant\RoleConstant;
use Application\Service\Macro\MacroServiceAwareTrait;
use Application\Service\Url\UrlServiceAwareTrait;
use Structure\Entity\Db\Structure;
use Structure\Entity\Db\StructureGestionnaire;
use Structure\Entity\Db\StructureResponsable;
use Structure\Provider\Template\MailTemplates;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenMail\Entity\Db\Mail;
use UnicaenMail\Service\Mail\MailServiceAwareTrait;
use UnicaenRenderer\Service\Rendu\RenduServiceAwareTrait;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\Service\Role\RoleServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class NotificationService
{
    use MailServiceAwareTrait;
    use RenduServiceAwareTrait;
    use RoleServiceAwareTrait;
    use StructureServiceAwareTrait;
    use UserServiceAwareTrait;
    use MacroServiceAwareTrait;
    use UrlServiceAwareTrait;

    /** RECUPERATION DES MAILS *************************/

    public function getMailsAdministrationFonctionnelle(): array
    {
        $role = $this->getRoleService()->findByRoleId(RoleConstant::ADMIN_FONC);
        $users = $this->getUserService()->findByRole($role);
        $mails = array_map(function (User $a) {
            return $a->getEmail();
        }, $users);
        return $mails;

    }

    /** GESTION DES INSCRIPTIONS **************************************************************************************/

    public function triggerInformations(Structure $structure): ?Mail
    {
        $vars = [
            'structure' => $structure,
            'MacroService' => $this->getMacroService(),
            'UrlService' => $this->getUrlService(),
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(MailTemplates::UPDATE_INFOS, $vars);

        $emails = [];

        if (empty($emails)) {
            $gestionnaires = $this->getStructureService()->getGestionnaires($structure);
            if (!empty($gestionnaires)) $emails = array_map(function (StructureGestionnaire $a) {
                return $a->getAgent()->getEmail();
            }, $gestionnaires);
        }
        if (empty($emails)) {
            $responsables = $this->getStructureService()->getResponsables($structure);
            if (!empty($responsables)) $emails = array_map(function (StructureResponsable $a) {
                return $a->getAgent()->getEmail();
            }, $responsables);
        }
//        if (empty($emails)) {
//            $emails = $this->getMailsAdministrationFonctionnelle();
//        }

        if (empty($emails)) return null;
        $mail = $this->getMailService()->sendMail($emails, $rendu->getSujet(), $rendu->getCorps());
        $mail->setMotsClefs([$structure->generateTag(), $rendu->getTemplate()->generateTag()]);
        $this->getMailService()->update($mail);
        return $mail;
    }

}