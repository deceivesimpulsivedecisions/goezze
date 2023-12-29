<?php

namespace App\Controller;

use App\Entity\PackageEnquiry;
use App\Form\PackageEnquiryType;
use App\Repository\DestinationRepository;
use App\Repository\PackageCategoryRepository;
use App\Repository\PackageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PackagesController extends AbstractController
{
    #[Route('/packages', name: 'app_packages')]
    public function index(PackageRepository $packageRepository, DestinationRepository $destinationRepository, PackageCategoryRepository $categoryRepository): Response
    {
        $destinations = $destinationRepository->findAll();
        $categories = $categoryRepository->findAll();
        $packages = $packageRepository->findBy(['isActive' => true]);
        return $this->render('packages/index.html.twig', [
            'packages' => $packages,
            'destinations' => $destinations,
            'categories' => $categories
        ]);

    }

    #[Route('/packages/fetch', name: 'app_packages_fetch')]
    public function fetchPackages(Request $request, PackageRepository $packageRepository){
        $filters = $request->query->all();

        $packages = $packageRepository->search($filters);
        return $this->render('packages/list_packages.html.twig', [
            'packages' => $packages,
        ]);
    }

    #[Route('/packages/destination/{slug}', name: 'app_destination')]
    public function packagesFromDestination(DestinationRepository $destinationRepository, $slug = null): Response
    {

            $destination = $destinationRepository->findBy(['name' => $slug]);
            return $this->render('packages/package_category.html.twig', [
                'slug' => $slug,
                'destination' => $destination
            ]);

    }

    #[Route('/packages/detail/{slug}', name: 'app_package_details')]
    public function packageDetails(Request $request, PackageRepository $packageRepository, $slug = null){
        $package = $packageRepository->findOneBy(['slug' => $slug]);
        $packageEnquiry = new PackageEnquiry();
        $form = $this->createForm(PackageEnquiryType::class, $packageEnquiry);

        $form->handleRequest($request);

        return $this->render('packages/package_details.html.twig', [
            'package' => $package,
            'form' => $form->createView(),
        ]);
    }
}
