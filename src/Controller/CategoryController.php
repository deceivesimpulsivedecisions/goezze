<?php

namespace App\Controller;

use App\Repository\PackageCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/category', name: 'app_category_')]
class CategoryController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function index(EntityManagerInterface $em, PackageCategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/{slug}/packages', name: 'categoryPackages')]
    public function categoryPackages(EntityManagerInterface $em, PackageCategoryRepository $categoryRepository, $slug = null): Response
    {
        $category = $categoryRepository->findOneBy(['slug' => $slug]);


        return $this->render('category/packages.html.twig', [
            'category' => $category,
            'packages' => $category->getPackages()
        ]);
    }
}
