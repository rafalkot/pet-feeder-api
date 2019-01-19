<?php

declare(strict_types=1);

namespace App\Ui\Rest\Form;

use App\Application\Command\ScheduleTask;
use App\Domain\PersonId;
use App\Domain\PetId;
use App\Domain\Pets;
use App\Domain\TaskId;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Time;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final class TaskForm extends AbstractType implements DataMapperInterface
{
    /**
     * @var PersonId
     */
    private $ownerId;
    /**
     * @var Pets
     */
    private $pets;

    public function __construct(Pets $pets)
    {
        $this->pets = $pets;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->ownerId = $options['owner_id'];

        $builder->add(
                'pet_id',
                null,
                [
                    'constraints' => [
                        new NotBlank(),
                        new Type('string'),
                        new Length(['min' => 2, 'max' => 100]),
                        new Callback(['callback' => [$this, 'validatePet'], 'groups' => ['Second']]),
                    ],
                ]
            )
            ->add(
                'name',
                null,
                [
                    'constraints' => [
                        new NotBlank(),
                        new Type('string'),
                        new Length(['min' => 2, 'max' => 100]),
                    ],
                ]
            )
            ->add(
                'hour',
                null,
                [
                    'constraints' => [
                        new NotBlank(),
                        new Time(),
                    ],
                ]
            )
            ->add(
                'time_zone',
                null,
                [
                    'constraints' => [
                        new NotBlank(),
                        new Type('string'),
                        new Choice(\DateTimeZone::listIdentifiers()),
                    ],
                ]
            )
            ->add(
                'recurrence',
                null,
                [
                    'constraints' => [
                        new NotBlank(),
                        new Type('string'),
                    ],
                ]
            )
            ->setDataMapper($this);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => new GroupSequence(['Default', 'Second']),
        ]);
        $resolver->setRequired(['owner_id']);
    }

    public function mapDataToForms($data, $forms)
    {
    }

    public function mapFormsToData($forms, &$data)
    {
        $forms = iterator_to_array($forms);

        $data = new ScheduleTask(
            [
                'task_id' => TaskId::generate()->id(),
                'pet_id' => $forms['pet_id']->getData(),
                'name' => $forms['name']->getData(),
                'hour' => $forms['hour']->getData(),
                'time_zone' => $forms['time_zone']->getData(),
                'recurrence' => $forms['recurrence']->getData(),
            ]
        );
    }

    public function validatePet($value, ExecutionContextInterface $context)
    {
        $value = new PetId($value);
        $pet = $this->pets->findById($value);

        if (null === $pet || !$pet->ownerId()->equals($this->ownerId)) {
            $context->addViolation('Pet with such id does not exist');

            return;
        }
    }
}
