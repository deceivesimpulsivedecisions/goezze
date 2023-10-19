<?php

namespace App\Controller\Admin;

use App\Entity\Package;
use App\Entity\PackageCategory;
use App\Form\PackageImages;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('title')
            ->add('category')
            ->add('type')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            TextEditorField::new('description'),
            NumberField::new('amount'),
            AssociationField::new('category'),
            CollectionField::new('packageItinerary')->allowAdd(true)->allowDelete(true)
            ->setEntryType('App\Form\PackageItinerary')->hideOnIndex(),
            CollectionField::new('packageMedia')->setEntryType(PackageImages::class)
                ->setFormTypeOptions(['by_reference' => false])
                ->onlyOnForms(),
            CollectionField::new('packageMedia')->onlyOnDetail()->setTemplatePath('admin/preview_image.html.twig'),
            ChoiceField::new('type', 'Package Type')->setChoices([
                'Domestic' => 'domestic',
                'International' => 'international',
            ]),
            AssociationField::new('destination'),
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
        foreach($object->getPackageMedia() as $image) {
            if($image->getImageFile() instanceof UploadedFile){
                $image->setOriginalName($image->getImageFile()->getClientOriginalName());
                $image->setEncodedName($image->getImageFile()->getClientOriginalName());
            }
        }
    }

    public function configureActions(Actions $actions): Actions {

        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL )
            ;
    }

}
