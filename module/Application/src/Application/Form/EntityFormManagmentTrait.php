<?php

namespace Application\Form;

use Laminas\Form\Form;
use Laminas\Http\Request;

trait EntityFormManagmentTrait {

    /**
     * @param Request $request
     * @param Form $form
     * @param $service
     */
    public function createFromForm(Request $request, Form $form, $service)
    {
        $data = $request->getPost();
        $form->setData($data);
        if ($form->isValid()) {
            $service->create($form->getObject());
        }
    }

    /**
     * @param Request $request
     * @param Form $form
     * @param $service
     */
    public function updateFromForm(Request $request, Form $form, $service)
    {
        $data = $request->getPost();
        $form->setData($data);
        if ($form->isValid()) {
            $service->update($form->getObject());
        }
    }
}