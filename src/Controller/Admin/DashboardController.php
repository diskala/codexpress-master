<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Snippet;
use Symfony\UX\Chartjs\Model\Chart;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    // On importe la classe ChartBuilderInterface dans une propriété
    private ChartBuilderInterface $chartBuilder;

    public function __construct(ChartBuilderInterface $chartBuilder)
    {
        $this->chartBuilder = $chartBuilder;
    }

    // On crée une méthode pour créer le graphique
    private function createChart(): Chart
    {
        $chart = $this->chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => [0, 10, 5, 2, 20, 30, 45],
                ],
            ],
        ]);
        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 100,
                ],
            ],
        ]);
        return $chart;
    }

    // On charge les assets de Webpack Encore dans le dashboard
    public function configureAssets(): Assets
    {
        $assets = parent::configureAssets();

        $assets->addWebpackEncoreEntry('app');

        return $assets;
    }

    // Route pour le dashboard et on y passe le graphique $chart
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //

        return $this->render('admin/dashboard.html.twig', [
            'chart' => $this->createChart(),
        ]);
    }

    // On configure le dashboard
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('<img src="/images/logo-codexpress.svg" width="300" alt="CodeXpress Partage de code">');
    }

    // On configure le menu
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Gestion');
        yield MenuItem::linkToCrud('Snippets', 'fas fa-list', Snippet::class);
        yield MenuItem::linkToCrud('Users', 'fas fa-users', User::class);
        yield MenuItem::section('Autres');
        yield MenuItem::linkToRoute('Retour à l\'accueil', 'fas fa-arrow-left', 'app_page');
        yield MenuItem::linkToLogout('Déconnexion', 'fas fa-arrow-right-from-bracket');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
