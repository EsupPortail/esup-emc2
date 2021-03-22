<?php

namespace UnicaenUtilisateur\Event\Listener;

use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Event\UserRoleSelectedEvent;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

/**
 * Classe abstraites pour les classes désirant scruter un événement déclenché lors de la sélection d'un
 * rôle utilisateur.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 * @see UserAuthenticatedEvent
 */
abstract class UserRoleSelectedEventAbstractListener implements ListenerAggregateInterface, EntityManagerAwareInterface
{
    use EntityManagerAwareTrait;

    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = [];

    /**
     * Renseigne les relations 'intervenant' et 'personnel' avant que l'objet soit persisté.
     *
     * @param UserRoleSelectedEvent $e
     */
    abstract public function postSelection(UserRoleSelectedEvent $e);

    /**
     * {@inheritdoc}
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $sharedEvents      = $events->getSharedManager();
        $this->listeners[] = $sharedEvents->attach(
            'UnicaenAuth\Service\UserContext',
            UserRoleSelectedEvent::POST_SELECTION,
            [$this, 'postSelection'],
            100);
    }

    /**
     * Detach all previously attached listeners
     *
     * @param EventManagerInterface $events
     *
     * @return void
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }
}