<?php

namespace App\Controller\Admin;

use App\Entity\Package;
use App\Entity\PackageCategory;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PackageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Package::class;
    }

    public function createEntity(string $entityFqcn)
    {
        $package = new Package();
        $package->setCreatedBy($this->getUser());
        $package->setCreatedAt(date_create('now'));

        return $package;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            TextEditorField::new('description'),
            NumberField::new('amount'),
            AssociationField::new('category'),
            CollectionField::new('packageItinerary')->allowAdd(true)->allowDelete(true)
            ->setEntryType('App\Form\PackageItinerary')
        ];
    }

}
