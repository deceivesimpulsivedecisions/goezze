<?php

namespace App\Form;

use App\Entity\Package;
use App\Entity\PackageEnquiry;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PackageEnquiryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fromDate', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('adults', ChoiceType::class, [
                'choices' => $this->generateChoices(1, 9),
            ])
            ->add('childrens', ChoiceType::class, [
                'choices' => $this->generateChoices(0, 9),
            ])
            ->add('infants', ChoiceType::class, [
                'choices' => $this->generateChoices(0, 9),
            ])
            ->add('email', EmailType::class)
            ->add('phoneNo', NumberType::class)
            ->add('amount', NumberType::class);
    }

    private function generateChoices(int $min, int $max): array
    {
        $choices = [];

        for ($i = $min; $i <= $max; $i++) {
            $choices[$i] = $i;
        }

        return $choices;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PackageEnquiry::class,
        ]);
    }
}
