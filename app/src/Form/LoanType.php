<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Positive;

class LoanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $constraints = [
            'required' => true,
            'constraints' => [new Positive()],
            'attr' => [
                'min' => 1
            ]
        ];
        $builder
            ->add('price', IntegerType::class, $constraints)
            ->add('initialPayment', NumberType::class, $constraints + ['scale' => 2])
            ->add('loanTerm', IntegerType::class, $constraints)
            ->getForm()
        ;
        return $builder;
    }
}