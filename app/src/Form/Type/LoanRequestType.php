<?php

namespace App\Form\Type;

use App\Entity\Car;
use App\Entity\Loan;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Positive;

class LoanRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $constr = [
            'required' => true,
            'constraints' => [new Positive()],
            'attr' => [
                'min' => 1
            ]
        ];
        $builder
            ->add('carId', EntityType::class, ['class' => Car::class, 'required' => true, 'property_path' => 'car'])
            ->add('programId', EntityType::class, ['class' => Loan::class, 'required' => true, 'property_path' => 'loan'])
            ->add('initialPayment', IntegerType::class, $constr)
            ->add('loanTerm', IntegerType::class, $constr)
            // ->add('save', SubmitType::class)
        ;
    }

    public function getBlockPrefix() { return ''; }
}