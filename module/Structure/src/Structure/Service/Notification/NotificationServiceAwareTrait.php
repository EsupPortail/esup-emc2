<?php

namespace Structure\Service\Notification;

trait NotificationServiceAwareTrait {

    private NotificationService $notificationService;

    public function getNotificationService(): NotificationService
    {
        return $this->notificationService;
    }

    public function setNotificationService(NotificationService $notificationService): void
    {
        $this->notificationService = $notificationService;
    }
}