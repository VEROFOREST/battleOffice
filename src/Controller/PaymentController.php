<?php

namespace App\Controller;

use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;





class PaymentController extends AbstractController
{
    /**
     * @Route("/payment/{id}", name="payment")
     */
    public function index(Order $order): Response
    {
        $price = $order->getProduct()->getPrice();
        $email =  $order->getClient()->getEmail();
        
        return $this->render('payment/index.html.twig', [
            'controller_name' => 'PaymentController',
            'price'=>$price,
            'email'=>$email,
            'order'=>$order,


        ]);
    }
    /**
     * @Route("/payment/{id}/new", name="paymentNew")
     */
    public function paymentNew(Order $order)
    {
         // Set your secret key. Remember to switch to your live secret key in production!
            // See your keys here: https://dashboard.stripe.com/account/apikeys

            \Stripe\Stripe::setApiKey('sk_test_51H2XZoC9AgcSjzXzNZ87NB3SquAdSVnYZZRgxVDSJqAf2zzrdidoqSPFShFCGIdaQpG0IKJWAIt1aWwE8nwrTME800YZ9Xmhje');
             
            try{
            // Token is created using Stripe Checkout or Elements!
            // Get the payment token ID submitted by the form:
   
            
            $charge = \Stripe\PaymentIntent::create([
            'amount' =>intval( $order->getProduct()->getPrice())*100,
            'currency' => 'eur',
            'description' => 'Achat Nerfs',
 
            ]);
            
            } catch (\Exception $e) {
               dd($e);
            }

            return $this->render('landing_page/confirmation.html.twig',[

            ]);
            
    
    }
}