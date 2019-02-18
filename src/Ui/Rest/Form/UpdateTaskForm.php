<?php

declare(strict_types=1);

namespace App\Ui\Rest\Form;

use App\Application\Command\UpdateTask;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Time;
use Symfony\Component\Validator\Constraints\Type;

final class UpdateTaskForm extends AbstractType implements DataMapperInterface
{
    /**
     * @var string
     */
    private $taskId;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->taskId = $options['task_id'];

        $builder->add(
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
        $resolver->setRequired(['task_id']);
    }

    public function mapDataToForms($data, $forms)
    {
    }

    public function mapFormsToData($forms, &$data)
    {
        $forms = iterator_to_array($forms);

        $data = new UpdateTask(
            [
                'task_id' => $this->taskId,
                'name' => $forms['name']->getData(),
                'hour' => $forms['hour']->getData(),
                'time_zone' => $forms['time_zone']->getData(),
                'recurrence' => $forms['recurrence']->getData(),
            ]
        );
    }
}
