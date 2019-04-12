<?php

namespace Application\Form\AssocierAgent;

trait AssocierAgentFormAwareTrait {

    /** @var AssocierAgentForm $associerAgentForm */
    private $associerAgentForm;

    /**
     * @return AssocierAgentForm
     */
    public function getAssocierAgentForm()
    {
        return $this->associerAgentForm;
    }

    /**
     * @param AssocierAgentForm $associerAgentForm
     * @return AssocierAgentForm
     */
    public function setAssocierAgentForm($associerAgentForm)
    {
        $this->associerAgentForm = $associerAgentForm;
        return $this->associerAgentForm;
    }
}