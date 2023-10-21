<?php

namespace App\Controller\Admin;

use App\Entity\Admin;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class AdminCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Admin::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('email'),
            TextField::new('password')->setFormType(PasswordType::class),
            TextField::new('firstName'),
            TextField::new('lastName'),
            ChoiceField::new('roles')->setChoices([
                'Admin' => 'ROLE_ADMIN',
                'Editor' =>'ROLE_Editor'
            ])->allowMultipleChoices()->renderExpanded(),
            BooleanField::new('isActive')
        ];
    }

    public function createEntity($entityFqcn)
    {
        $admin = new Admin();
        $admin->setCreatedAt(new \DateTime('now'));

        return $admin;
    }

    public function updateEntity(EntityManagerInterface $entityManager, $admin):void
    {
        $admin->setUpdatedAt(new \DateTime('now'));

        $entityManager->persist($admin);
        $entityManager->flush();
    }
}
