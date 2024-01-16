<?php

namespace App\Form;

use App\Repository\CityRepository;
use App\Service\HotelService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HotelType extends AbstractType
{
    private $cityRepo;
    private $hotelService;

    public function __construct(CityRepository $cityRepo, HotelService $hotelService)
    {
        $this->cityRepo = $cityRepo;
        $this->hotelService = $hotelService;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $countries = $this->cityRepo->findAllCountries();

        $builder
            ->add('CheckInDate', DateType::class, [
                'html5' => true,
                'widget' => 'single_text',
                'attr' => [
                    'min' => (new \DateTime('now'))->format('Y-m-d')
                ]
            ])
            ->add('NoOfNights', IntegerType::class, [
                'attr' => [
                    'min' => 1,
                    'max' => 100
                ]
            ])
            ->add('CountryCode', ChoiceType::class,[
                'choices' => $countries
            ])
            ->add('CityId', ChoiceType::class,[
                'choices' => $this->cityRepo->findAllCities()
            ])
            ->add('GuestNationality', ChoiceType::class,[
                'choices' => $countries
            ])
            ->add('NoOfRooms')
            ->add('RoomGuests', CollectionType::class, [
                'entry_type' => RoomType::class,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
