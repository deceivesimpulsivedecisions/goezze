<?php

namespace App\Controller\Admin;

use App\Entity\PackageType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PackageTypeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PackageType::class;
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
