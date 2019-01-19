<?php

declare(strict_types=1);

namespace App\Ui\Rest\Form;

use App\Application\Command\RegisterPet;
use App\Domain\PersonId;
use App\Domain\PetId;
use App\Domain\Pets;
use App\Domain\PetType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final class PetForm extends AbstractType implements DataMapperInterface
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
            'name',
            null,
            [
                'constraints' => [
                    new NotBlank(),
                    new Type('string'),
                    new Length(['min' => 2, 'max' => 50]),
                    new Callback(['callback' => [$this, 'validateName'], 'groups' => ['Second']]),
                ],
            ]
        )
            ->add(
                'type',
                null,
                [
                    'constraints' => [
                        new NotBlank(),
                        new Type('string'),
                        new Callback(['callback' => [$this, 'validateType'], 'groups' => ['Second']]),
                    ],
                ]
            )
            ->setDataMapper($this);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'validation_groups' => new GroupSequence(['Default', 'Second']),
            ]
        );
        $resolver->setRequired(['owner_id']);
    }

    public function mapDataToForms($data, $forms)
    {
    }

    public function mapFormsToData($forms, &$data)
    {
        $forms = iterator_to_array($forms);

        $type = $forms['type']->getData();
        $name = $forms['name']->getData();

        if (null === $type || null === $name) {
            return;
        }

        $data = RegisterPet::withData(
            PetId::generate()->id(),
            $this->ownerId->id(),
            $type,
            $name
        );
    }

    public function validateName($value, ExecutionContextInterface $context)
    {
        $ownersPets = $this->pets->findAllByOwnerId($this->ownerId);

        foreach ($ownersPets as $pet) {
            if ($pet->name() === $value) {
                $context->addViolation('Pet with such name already exists');
            }
        }
    }

    public function validateType($value, ExecutionContextInterface $context)
    {
        try {
            new PetType($value);
        } catch (\Exception $ex) {
            $context->addViolation('Invalid pet type');
        }
    }
}
