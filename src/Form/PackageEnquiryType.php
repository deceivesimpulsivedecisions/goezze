<?php

namespace App\Form;

use App\Entity\Package;
use App\Entity\PackageEnquiry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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
            ->add('adults', IntegerType::class, [
                'attr' => ['min' => 0, 'max' => 9],
            ])
            ->add('childrens', IntegerType::class, [
                'attr' => ['min' => 0, 'max' => 9],
            ])
            ->add('infants', IntegerType::class, [
                'attr' => ['min' => 0, 'max' => 9],
            ])
            ->add('package', CollectionType::class, [
                'entry_type' => Package::class, // Assuming you have a PackageType
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('amount', NumberType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PackageEnquiry::class,
        ]);
    }
}
