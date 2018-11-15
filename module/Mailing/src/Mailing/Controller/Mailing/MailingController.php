<?php

namespace Mailing\Controller\Mailing;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class MailingController extends AbstractActionController {

    public function indexAction()
    {
        return new ViewModel(
        );
    }
}