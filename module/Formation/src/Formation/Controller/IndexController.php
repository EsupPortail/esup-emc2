<?php

namespace Formation\Controller;

use Laminas\View\Model\ViewModel;

class IndexController extends AbstractController {

    public function indexAction() : ViewModel
    {
        return new ViewModel([]);
    }
}