<?php

namespace App\Controller\Admin;

use App\Entity\PackageCategory;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PackageCategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PackageCategory::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Package Category')
            ->setEntityLabelInPlural('Package Categories')
            ->setSearchFields(['name']);

    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
