<?php

namespace App\Controller\Admin;

use App\Entity\PackageCategory;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

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


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextField::new('slug'),
            ImageField::new('thumbnail')->setBasePath('uploads/thumbnails/')->onlyOnIndex(),
            ImageField::new('thumbnailFile')->setUploadDir('public/uploads/thumbnails')->setUploadedFileNamePattern('[year]/[month]/[day]/[slug]-[contenthash].[extension]')->onlyOnForms()
        ];
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->attachFiles($entityInstance);
        parent::updateEntity($entityManager, $entityInstance);
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->attachFiles($entityInstance);
        parent::persistEntity($entityManager, $entityInstance);
    }

    private function attachFiles($object){
//        foreach($object->getPackageMedia() as $image) {
//            if($image->getImageFile() instanceof UploadedFile){
////                $image->setOriginalName($image->getImageFile()->getClientOriginalName());
////                $image->$imagesetEncodedName($image->getImageFile()->getClientOriginalName());
//            }
//        }
    }

}
