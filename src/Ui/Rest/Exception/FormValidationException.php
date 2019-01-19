<?php

declare(strict_types=1);

namespace App\Ui\Rest\Exception;

use App\Application\Exception\ProvidesHttpStatusCode;
use Symfony\Component\Form\FormInterface;

final class FormValidationException extends \Exception implements ProvidesHttpStatusCode
{
    /**
     * @var FormInterface
     */
    private $form;

    public static function fromForm(FormInterface $form): self
    {
        $exception = new self('Form validation problem', 0, null);
        $exception->form = $form;

        return $exception;
    }

    public function getHttpStatusCode(): int
    {
        return 400;
    }

    public function getForm(): FormInterface
    {
        return $this->form;
    }

    public function getErrors(): array
    {
        $errors = $this->getFormErrors($this->form);
        $tmp = [];

        foreach ($errors as $field => $fieldErrors) {
            foreach ($fieldErrors as $fieldError) {
                $tmp[] = [
                    'field' => $field,
                    'message' => $fieldError,
                ];
            }
        }

        return $tmp;
    }

    private function getFormErrors(FormInterface $form): array
    {
        $errors = [];

        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getFormErrors($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }

        return $errors;
    }
}
