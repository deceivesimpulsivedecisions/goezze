<?php

namespace App\Controller;

use App\Constants\EntityType;
use App\Constants\PaymentStatus;
use App\Entity\Booking;
use App\Entity\PackageEnquiry;
use App\Form\PackageEnquiryType;
use App\Repository\BookingRepository;
use App\Repository\DestinationRepository;
use App\Repository\PackageCategoryRepository;
use App\Repository\PackageEnquiryRepository;
use App\Repository\PackageRepository;
use App\Service\GenerateUniqueIdService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use PhonePe\Env;
use PhonePe\payments\v1\models\request\builders\InstrumentBuilder;
use PhonePe\payments\v1\models\request\builders\PgPayRequestBuilder;
use PhonePe\payments\v1\PhonePePaymentClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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

    #[Route('/redirect/{id}', name: 'phonepe_redirect')]
    public function phonePeRedirect(BookingRepository $bookingRepository, PackageEnquiryRepository $enquiryRepository, PackageRepository $packageRepository, EntityManagerInterface $em, $id = null): Response
    {

        $merchantId = "GOEZEEUAT";
        $saltKey = "f634ebc7-6c4d-4380-b59e-2380d1a1fc49";
        $saltIndex = "1";
        $env = Env::UAT;
        $shouldPublishEvents = true;
        $phonePePaymentsClient = new PhonePePaymentClient($merchantId, $saltKey, $saltIndex, $env, $shouldPublishEvents);

        $checkStatus = $phonePePaymentsClient->statusCheck($id);
        $status = $checkStatus->getResponseCode();

//        dd($checkStatus);
        $booking = $bookingRepository->findOneBy(['merchantTransactionId' => $id]);
        $packageEnquiry = $enquiryRepository->findOneBy(['id' => $booking->getEntityId()]);

        $booking->setTransactionId($checkStatus->getTransactionId());
        $booking->setPaymentInstrument($checkStatus->getPaymentInstrument());
        $booking->setPaymentState($checkStatus->getState());
        $booking->setStatus($checkStatus->getResponseCode());

        $em->persist($booking);
        $em->flush();

        return $this->render('packages/package_redirect.html.twig', [
            'status' => $status,
            'packageEnquiry' => $packageEnquiry,
            'booking' => $booking
        ]);

    }

    #[Route('/packages/detail/{slug}', name: 'app_package_details')]
    public function packageDetails(Request $request, GenerateUniqueIdService $generateUniqueIdService, EntityManagerInterface $em, PackageRepository $packageRepository, UrlGeneratorInterface $urlGenerator, $slug = null){
        $package = $packageRepository->findOneBy(['slug' => $slug]);
        $packageEnquiry = new PackageEnquiry();
        $packageEnquiry->setPackage($package);
        $form = $this->createForm(PackageEnquiryType::class, $packageEnquiry);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            // Handle form submission, e.g., persist to the database
            $formData = $form->getData();
            $em->persist($packageEnquiry);
            $em->flush();

            $merchantId = "GOEZEEUAT";
            $saltKey = "f634ebc7-6c4d-4380-b59e-2380d1a1fc49";
            $saltIndex = "1";
            $env = Env::UAT;
            $shouldPublishEvents = true;
            $phonePePaymentsClient = new PhonePePaymentClient($merchantId, $saltKey, $saltIndex, $env, $shouldPublishEvents);

            $totalAmount = $packageEnquiry->getAmount();
            $merchantTransactionId = $generateUniqueIdService->generateUniqueId();
            $request = PgPayRequestBuilder::builder()
                ->mobileNumber($formData->getPhoneNo())
                ->callbackUrl("https://webhook.in/test/status")
                ->merchantId($merchantId)
                ->merchantUserId("256")
                ->amount($totalAmount * 100)
                ->merchantTransactionId($merchantTransactionId)
                ->redirectUrl($urlGenerator->generate('phonepe_redirect', ['id' => $merchantTransactionId], UrlGenerator::ABSOLUTE_URL))
                ->redirectMode("REDIRECT")
                ->paymentInstrument(InstrumentBuilder::buildPayPageInstrument())
                ->build();

            $booking = new Booking();

            $booking->setEntityId($packageEnquiry->getId());
            $booking->setEntityType(EntityType::PACKAGE);
            $booking->setAmount($packageEnquiry->getAmount()*100);
            $booking->setStatus(PaymentStatus::PENDING);
            $booking->setPaymentState(PaymentStatus::PENDING);
            $booking->setMerchantTransactionId($merchantTransactionId);

            $em->persist($booking);
            $em->flush();

            $response = $phonePePaymentsClient->pay($request);
            $payPageUrl = $response->getInstrumentResponse()->getRedirectInfo()->getUrl();

            // Redirect to a specific route after successful form submission
            return $this->redirect($payPageUrl); // Replace 'success_route' with your desired route name
        }

        return $this->render('packages/package_details.html.twig', [
            'package' => $package,
            'form' => $form->createView(),
        ]);
    }
}
