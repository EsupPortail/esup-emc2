<?php

namespace FichePoste\Service\Notification;

trait NotificationServiceAwareTrait {

    private NotificationService $notificationService;

    public function getNotificationService(): NotificationService
    {
        return $this->notificationService;
    }

    public function setNotificationService(NotificationService $notificationService): NotificationService
    {
        $this->notificationService = $notificationService;
        return $this->notificationService;
    }


}