<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaymentController extends AbstractController
{
    #[Route('/payment', name: 'app_payment')]
    public function index(): Response
    {
        // Initialisation de la clé API de Stripe        
        Stripe::setApiKey($_ENV["STRIPE_SECRET"]);

        // On créer une session de paiement
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => 1000,
                    'product_data' => [
                        'name' => 'Abonnement Premium',
                        'images' => ["https://raw.githubusercontent.com/Jensone/codexpress/master/public/images/logo-codexpress.png"],
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('app_payment_success', [], 0),
            'cancel_url' => $this->generateUrl('app_payment_cancel', [], 0),
        ]);

        return $this->redirect($session->url, 303);
    }

    #[Route('/payment/success', name: 'app_payment_success')]
    public function success(EntityManagerInterface $em): Response
    {
        // On récupère l'utilisateur connecté
        $user = $this->getUser();

        // On lui donne le rôle ROLE_PREMIUM
        $user->setRoles(["ROLE_PREMIUM"]);
        $user->setIsPremium(true);

        // On enregistre l'utilisateur en base de données
        $em->persist($user);
        $em->flush();

        // On envoye un message flash
        $this->addFlash('message', 'Le paiement a bien été effectué. Vous êtes désormais Premium !');

        // On redirige l'utilisateur vers la page d'accueil
        return $this->redirectToRoute('app_page');
    }

    #[Route('/payment/cancel', name: 'app_payment_cancel')]
    public function cancel(): Response
    {
        // On envoye un message flash
        $this->addFlash('message', 'Le paiement a n\'a pas abouti. Réessayez.');

        // On redirige l'utilisateur vers la page d'accueil
        return $this->redirectToRoute('app_page');
    }
}
