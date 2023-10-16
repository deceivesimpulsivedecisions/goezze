<?php

namespace App\Controller\Admin;

use App\Entity\PackageMedia;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PackageMediaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PackageMedia::class;
    }

    public function createEntity(string $entityFqcn)
    {
        $packageMedia = new PackageMedia();

        $packageMedia->setCreatedAt(date_create('now'));

        return $packageMedia;
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
