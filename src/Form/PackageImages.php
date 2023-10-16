<?php

namespace App\Form;

use App\Entity\PackageMedia;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PackageImages extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imageFile', null, ['required' => true, 'help' => ''])
        ;
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        if($view->vars['value']) {
            $view->vars['help'] = "<span class='attach-img-preview' data-src='{$view->vars['value']->getWebPath()}'></span>";
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => PackageMedia::class]);
    }
}