<?php

declare(strict_types=1);

namespace App\Ui\Rest\Form;

use App\Application\Command\RegisterPerson;
use App\Domain\PersonId;
use App\Domain\Persons;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final class RegistrationForm extends AbstractType implements DataMapperInterface
{
    /**
     * @var Persons
     */
    private $persons;

    /**
     * RegistrationForm constructor.
     */
    public function __construct(Persons $persons)
    {
        $this->persons = $persons;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'email',
            EmailType::class,
            [
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                    new Callback(['callback' => [$this, 'validateEmail']]),
                ],
            ]
        )
            ->add(
                'username',
                null,
                [
                    'constraints' => [
                        new NotBlank(),
                        new Length(['min' => 5, 'max' => 20]),
                        new Type('alnum'),
                        new Callback(['callback' => [$this, 'validateUsername']]),
                    ],
                ]
            )
            ->add(
                'password',
                null,
                [
                    'constraints' => [
                        new NotBlank(),
                        new Length(['min' => 5, 'max' => 20]),
                    ],
                ]
            )
            ->setDataMapper($this);
    }

    public function validateEmail($value, ExecutionContextInterface $context)
    {
        if ($this->persons->findByEmail($value)) {
            $context->addViolation('Email already exists');
        }
    }

    public function validateUsername($value, ExecutionContextInterface $context)
    {
        if ($this->persons->findByUsername($value)) {
            $context->addViolation('User with such username already exists');
        }
    }

    public function mapDataToForms($data, $forms)
    {
    }

    public function mapFormsToData($forms, &$data)
    {
        $forms = iterator_to_array($forms);

        $data = RegisterPerson::withData(
            PersonId::generate()->id(),
            $forms['username']->getData(),
            $forms['email']->getData(),
            $forms['password']->getData()
        );
    }
}
