<?php

declare(strict_types=1);

namespace App\Ui\Rest\Controller;

use App\Ui\Rest\Exception\FormValidationException;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class RestController extends FOSRestController implements ClassResourceInterface
{
    protected function processForm(FormInterface $form, Request $request)
    {
        $form->submit($request->request->all());

        if (!$form->isValid()) {
            throw FormValidationException::fromForm($form);
        }

        return $form->getData();
    }
}
