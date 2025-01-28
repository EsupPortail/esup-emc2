<?php

namespace Structure\Service\Notification;

use Application\Provider\Role\RoleProvider as AppRoleProvider;
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

    public function getMailsAdministrationFonctionnelle(): ?string
    {
        $role = $this->getRoleService()->findByRoleId(AppRoleProvider::ADMIN_FONC);
        $users = $this->getUserService()->findByRole($role);
        $mails = array_map(function (User $a) {
            return $a->getEmail();
        }, $users);
        return implode(',', $mails);

    }

}