<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            ImageField::new('profilePhoto')->setBasePath('uploads/avatars') ->setUploadDir('public/uploads/avatars'),
            TextField::new('email'),
            TextField::new('password')->setFormType(PasswordType::class)->onlyOnForms(),
            TextField::new('firstName'),
            TextField::new('lastName'),
            ChoiceField::new('roles')->setChoices([
                'B2C User' => 'ROLE_User',
                'Corporate Client' =>'ROLE_CLIENT'
            ])->allowMultipleChoices()->renderExpanded(),
            BooleanField::new('isActive')
        ];
    }

    public function createEntity($entityFqcn)
    {
        $user = new User();
        $user->setCreatedAt(new \DateTimeImmutable('now'));

        return $user;
    }
}
