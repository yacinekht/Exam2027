<?php

namespace App\Form\Animals;




use App\Entity\Animal;
use App\Enum\AnimalStatus;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class EditAnimalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Prenom',
                'constraints' => [
                    new NotBlank(),
                    new Length(max: 255),
                ],
            ])
            ->add('photoFilename', FileType::class, [
                'label' => 'Photo de l\'animal',
                'required' => false,
                'mapped' => false,
            ])
            ->add('espece', TextType::class, [
                'label' => 'Espece',
                'constraints' => [
                    new NotBlank(),
                    new Length(max: 255),
                ],
            ])
            ->add('race', TextType::class, [
                'label' => 'Race',
                'constraints' => [
                    new NotBlank(),
                    new Length(max: 255),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['rows' => 6],
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('age', IntegerType::class, [
                'label' => 'Age',
                'attr' => ['min' => 1],
                'constraints' => [
                    new NotBlank(),
                    new Positive(),
                ],
            ])
//
//
            ->add('status', EnumType::class, [
                'label' => 'Statut',
                'class' => AnimalStatus::class,
                'choice_label' => fn (AnimalStatus $status) => $status->value,
                'constraints' => [
                    new NotBlank(),
                ],
            ])

            ->add('arrivedAt', DateTimeType::class, [
                'label' => 'Date d\'arriver',
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'constraints' => [
                    new NotBlank(),
                ],
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Animal::class,
        ]);
    }
}

