<?php

declare(strict_types=1);

namespace App\Ui\Rest\Form;

use App\Application\Command\CreateHousehold;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

final class HouseholdForm extends AbstractType implements DataMapperInterface
{
    /**
     * @var string
     */
    private $householdId;

    /**
     * @var string
     */
    private $ownerId;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->householdId = $options['household_id'];
        $this->ownerId = $options['owner_id'];

        $builder->add(
                'name',
                null,
                [
                    'constraints' => [
                        new NotBlank(),
                        new Length(['min' => 2, 'max' => 20]),
                    ],
                ]
            )
            ->setDataMapper($this);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['household_id', 'owner_id']);
    }

    public function mapDataToForms($data, $forms)
    {
    }

    public function mapFormsToData($forms, &$data)
    {
        $forms = iterator_to_array($forms);

        $data = new CreateHousehold(
            [
                'household_id' => $this->householdId,
                'owner_id' => $this->ownerId,
                'name' => $forms['name']->getData(),
            ]
        );
    }
}
