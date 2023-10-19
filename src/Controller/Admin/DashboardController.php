<?php

namespace App\Controller\Admin;

use App\Entity\Admin;
use App\Entity\Destination;
use App\Entity\Package;
use App\Entity\PackageCategory;
use App\Entity\PackageItenary;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'admin_')]
class DashboardController extends AbstractDashboardController
{
    private $connection;
    private $em;

    public function __construct(Connection $connection, EntityManagerInterface $em)
    {
        $this->connection = $connection;
        $this->em = $em;
    }


    /**
     * @throws Exception
     */
    #[Route('/dashboard', name: 'dashboard')]
    public function index(): Response
    {
        $sql = "SELECT count(*) FROM package_category";
        $count['categories'] = $this->connection->fetchOne($sql);
        $sql = "SELECT count(*) FROM admin";
        $count['admin'] = $this->connection->fetchOne($sql);
        $sql = "SELECT count(*) FROM package";
        $count['package'] = $this->connection->fetchOne($sql);
        return $this->render('admin/dashboard.html.twig', [
            'count' => $count
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Goezze');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),
            MenuItem::subMenu('Users', 'fa fa-tags')->setSubItems([
                MenuItem::linkToCrud('Admin', 'fa fa-user', Admin::class),
            ]),

            MenuItem::subMenu('Packages', 'fa fa-tags')->setSubItems([
                MenuItem::linkToCrud('Categories', 'fa fa-table-cells', PackageCategory::class),
                MenuItem::linkToCrud('Package', 'fa fa-table-cells', Package::class),
                MenuItem::linkToCrud('Itinerary', 'fa fa-table-cells', PackageItenary::class),
                MenuItem::linkToCrud('Destination', 'fa fa-table-cells', Destination::class),
            ]),


            MenuItem::linkToLogout('Logout', 'fa fa-sign-out'),

        ];
    }

    public function configureAssets(): Assets
    {
        return Assets::new()
            ->addCssFile('css/admin.css')
            ->addJsFile('js/admin.js')
//            ->addJsFile('js/jquery-ui.js')
            ;
    }
}
