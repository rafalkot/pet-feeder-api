<?php

declare(strict_types=1);

namespace App\Ui\Rest\Form;

use App\Application\Command\InvitePersonToHousehold;
use App\Domain\HouseholdId;
use App\Domain\Households;
use App\Domain\PersonId;
use App\Domain\Persons;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final class InvitationForm extends AbstractType implements DataMapperInterface
{
    /**
     * @var Persons
     */
    private $persons;

    /**
     * @var Households
     */
    private $households;

    /**
     * @var HouseholdId
     */
    private $householdId;

    /**
     * RegistrationForm constructor.
     */
    public function __construct(Persons $persons, Households $households)
    {
        $this->persons = $persons;
        $this->households = $households;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->householdId = HouseholdId::fromString($options['household_id']);

        $builder->add(
                'person_id',
                null,
                [
                    'constraints' => [
                        new NotBlank(),
                        new Uuid(),
                        new Callback(['callback' => [$this, 'validatePerson'], 'groups' => ['Second']]),
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
        $resolver->setRequired(['household_id']);
    }

    public function validatePerson($value, ExecutionContextInterface $context)
    {
        $household = $this->households->findById($this->householdId);
        $personId = PersonId::fromString($value);

        if (!$this->persons->findById($personId)) {
            $context->addViolation('Person with such id does not exist');
        }

        if ($household->getInvitationByPerson($personId)) {
            $context->addViolation('Person is already invited');
        }

        if ($household->getOccupant($personId)) {
            $context->addViolation('Person is already an occupant');
        }
    }

    public function mapDataToForms($data, $forms)
    {
    }

    public function mapFormsToData($forms, &$data)
    {
        $forms = iterator_to_array($forms);

        $data = new InvitePersonToHousehold(
            [
                'household_id' => $this->householdId->id(),
                'person_id' => $forms['person_id']->getData(),
            ]
        );
    }
}
