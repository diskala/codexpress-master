<?php

namespace App\Service;

use Stripe\Charge;
use Stripe\Stripe;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class StripeService
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function payment()
    {
        $token = $this->request->get('stripeToken');
        
        Stripe::setApiKey($_ENV["STRIPE_SECRET"]);
        Charge::create ([
                "amount" => 10 * 100,
                "currency" => "eur",
                "source" => $token,
                "description" => "Abonnement CodeXpress"
        ]);
        $this->addFlash(
            'success',
            'Paiement validé'
        );
        return $this->redirectToRoute('app_page', [], Response::HTTP_SEE_OTHER);
    }
}

