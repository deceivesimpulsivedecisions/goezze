<?php

namespace App\Controller;

use App\Entity\ContactUs;
use App\Form\ContactUsFormType;
use App\Repository\DestinationRepository;
use App\Repository\PackageCategoryRepository;
use App\Repository\PackageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(DestinationRepository $destinationRepository, PackageCategoryRepository $categoryRepository, PackageRepository $packageRepository): Response
    {
        $packages = $packageRepository->findBy(['trending' => true], null, 5);
        $categories = $categoryRepository->findBy(['trending' => true], null, 5);
        $destinations = $destinationRepository->findBy(['trending' => true], null, 5);
        return $this->render('default/index.html.twig', [
            'destinations' => $destinations,
            'categories' => $categories,
            'packages' => $packages
        ]);
    }

    #[Route('/contact-us', name: 'contactUs')]
    public function contactUs(Request $request, EntityManagerInterface $entityManager): Response
    {
        $enquiry = new ContactUs();
        $form = $this->createForm(ContactUsFormType::class, $enquiry);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Save the enquiry to the database or perform any action
            // For example:
            $entityManager->persist($enquiry);
            $entityManager->flush();

            // Add a flash message or redirect to a 'thank you' page
            $this->addFlash('success', 'Enquiry submitted successfully!');

            return $this->redirectToRoute('contactUs');
        }
        return $this->render('default/contactUs.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
