<?php

namespace App\Controller\Admin;

use App\Entity\PackageCategory;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Stof\DoctrineExtensionsBundle\Uploadable\UploadableManager;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PackageCategoryCrudController extends AbstractCrudController
{
    private $uploadableManager;

    public function __construct(UploadableManager $uploadableManager)
    {
        $this->uploadableManager = $uploadableManager;
    }
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


    public function configureFields(string $pageName): iterable
    {
        return [
            ImageField::new('image','Category Image')->setBasePath('uploads/category')->onlyOnIndex()->setCssClass('image-small-preview'),
            TextField::new('name'),
            TextField::new('slug'),
            ImageField::new('image','Category Image')
                ->setBasePath('uploads/category')
                ->setUploadDir('public/uploads/category')
                ->setUploadedFileNamePattern('[randomhash].[extension]')->onlyOnForms()
        ];
    }
}
