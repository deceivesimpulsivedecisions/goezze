<?php

namespace App\Controller\Admin;

use App\Entity\PackageEnquiry;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PackageEnquiryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PackageEnquiry::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('package'),
            DateField::new('fromDate'),
            NumberField::new('adults'),
            NumberField::new('childrens'),
            NumberField::new('infants'),
            NumberField::new('amount'),
            NumberField::new('phoneNo'),
            EmailField::new('email'),
        ];
    }

    // Override configureActions method to remove add, edit, and delete actions
    public function configureActions(Actions $actions): Actions {

        return $actions
            ->remove(Crud::PAGE_INDEX, Action::EDIT )
            ->remove(Crud::PAGE_INDEX, Action::DELETE )
            ->remove(Crud::PAGE_INDEX, Action::NEW )
            ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('package')
            ;
    }
}
