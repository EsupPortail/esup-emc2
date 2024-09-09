<?php

namespace Formation\Event\Convocation;

trait ConvocationEventAwareTrait
{
    private ConvocationEvent $convocationEvent;

    public function getConvocationEvent(): ConvocationEvent
    {
        return $this->convocationEvent;
    }

    public function setConvocationEvent(ConvocationEvent $convocationEvent): void
    {
        $this->convocationEvent = $convocationEvent;
    }

}