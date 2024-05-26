<?php

namespace App\Controller\Admin;

use App\Entity\Pages;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Doctrine\ORM\EntityManagerInterface;

class PagesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Pages::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [];

        if (Crud::PAGE_INDEX === $pageName) {
            $fields = [
                IdField::new('id'),
                TextField::new('name'),
                TextField::new('slug'),
            ];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            $fields = [
                IdField::new('id'),
                TextField::new('name'),
                TextField::new('slug'),
                TextEditorField::new('details'),
            ];
        } elseif (Crud::PAGE_NEW === $pageName) {
            $fields = [
                TextField::new('name'),
                TextEditorField::new('details'),
            ];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            $fields = [
                TextField::new('name'),
                TextField::new('slug')->setFormTypeOption('disabled', 'disabled'),
                TextEditorField::new('details'),
            ];
        }

        return $fields;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Pages) {
            $entityInstance->setSlug($this->generateSlug($entityInstance->getName()));
        }

        parent::persistEntity($entityManager, $entityInstance);
    }

    private function generateSlug(string $name): string
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
    }
}

