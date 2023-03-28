<?php

namespace App\Form;

use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Positive;

class IngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(null, 'This ingredient need a name'),
                    new Length(['min' => 2, 'max' => 50])
                ],
                'attr' => [
                    'class' => 'form-contol w-100',
                    'minlength' => '2',
                    'maxlength' => '50',
                ],
                'label' => 'Name',
                'label_attr' => [
                    'class' => 'form-label',
                ],
            ])
            ->add('price', MoneyType::class, [
                'required' => true,
                'constraints' => [
                    new NotNull(null, 'This ingredient need a price'),
                    new Positive(),
                    new LessThan(200)
                ],
                'attr' => [
                    'class' => 'form-contol w-100'
                ],
                'label' => 'Price',
                'label_attr' => [
                    'class' => 'form-label'
                ],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success w-100 mt-4'
                ],
                'label' => 'send',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ingredient::class,
        ]);
    }
}
