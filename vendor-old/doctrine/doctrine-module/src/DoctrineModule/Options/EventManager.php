<?php

declare(strict_types=1);

namespace DoctrineModule\Options;

use Laminas\Stdlib\AbstractOptions;

/**
 * EventManager options
 *
 * @link    http://www.doctrine-project.org/
 */
class EventManager extends AbstractOptions
{
    /**
     * An array of subscribers. The array can contain the FQN of the
     * class to instantiate OR a string to be located with the
     * service locator.
     *
     * @var mixed[]
     */
    protected $subscribers = [];

    /**
     * @param mixed[] $subscribers
     */
    public function setSubscribers(array $subscribers): self
    {
        $this->subscribers = $subscribers;

        return $this;
    }

    /**
     * @return mixed[]
     */
    public function getSubscribers(): array
    {
        return $this->subscribers;
    }
}
