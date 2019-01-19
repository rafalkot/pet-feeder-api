<?php

declare(strict_types=1);

namespace App\Ui\Rest\Form;

use App\Application\Command\UpdatePetProfile;
use App\Domain\Gender;
use App\Domain\Pets;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final class PetProfileForm extends AbstractType implements DataMapperInterface
{
    /**
     * @var string
     */
    private $petId;
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
        $this->petId = $options['pet_id'];

        $builder->add(
            'gender',
            null,
            [
                'constraints' => [
                    new Type('string'),
                    new Callback(['callback' => [$this, 'validateGender'], 'groups' => ['Second']]),
                ],
            ]
        )
            ->add(
                'birth_year',
                IntegerType::class,
                [
                    'constraints' => [
                        new Type('integer'),
                        new Range(['min' => 1950, 'max' => (int) date('Y')]),
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
        $resolver->setRequired(['pet_id']);
    }

    public function mapDataToForms($data, $forms)
    {
    }

    public function mapFormsToData($forms, &$data)
    {
        $forms = iterator_to_array($forms);

        $data = UpdatePetProfile::withData(
            $this->petId,
            $forms['gender']->getData(),
            $forms['birth_year']->getData()
        );
    }

    public function validateGender($value, ExecutionContextInterface $context)
    {
        if (null === $value) {
            return;
        }

        try {
            new Gender($value);
        } catch (\UnexpectedValueException $ex) {
            $context->addViolation('Invalid gender');
        }
    }
}
