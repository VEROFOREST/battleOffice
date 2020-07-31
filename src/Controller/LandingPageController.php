<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\Shipping;
use App\Form\ClientType;
use App\Form\OrderType;
use App\Form\ProductType;
use App\Form\ShippingType;
use App\Manager\OrderManager;
use App\Repository\ProductRepository;
use Container1xM9NcI\getProductTypeService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Compiler\CheckTypeDeclarationsPass;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpClient\HttpClient;

class LandingPageController extends AbstractController
{
    public function apiOrder(Order $order)
    {
  
       $orderArray =
    [
      "order"=>
        [
            "id" => $order->getId(),
            "product" => $order->getProduct()->getName(),
            "payment_method"=> "stripe",
            "status" => 'WAITING',
            "client" =>
                [
                "firstname" => $order->getClient()->getFirstName(),
                "lastname" => $order->getClient()->getLastName(),
                "email"=> $order->getClient()->getEmail()
                ],
                "addresses"=>
                [
                "billing" =>
                    [
                        "address_line1" => $order->getClient()->getAddress(),
                        "address_line2"=> $order->getClient()->getAddressBis(),
                        "city"=> $order->getClient()->getCity(),
                        "zipcode"=> $order->getClient()->getPostcode(),
                        "country"=> $order->getClient()->getCountry()->getName(),
                        "phone"=> $order->getClient()->getPhone()
                    ],
                    "shipping"=>
                    [
                        "address_line1" => $order->getClient()->getShipping()->getAddress(),
                        "address_line2"=> $order->getClient()->getShipping()->getAddressBis(),
                        "city"=> $order->getClient()->getShipping()->getCity(),
                        "zipcode"=> $order->getClient()->getShipping()->getPostcode(),
                        "country"=> $order->getClient()->getCountry()->getName(),
                        "phone"=> $order->getClient()->getShipping()->getPhone()
                        ]
                ]
            ]
        ];

 
        // dd($orderArray);
        $client = HttpClient::create();
        $response = $client->request('POST','https://api-commerce.simplon-roanne.com/order',
        [
            'headers' => [
                'Accept' => 'application/json', //format de ce qu'on envoit
                'Content-Type'=> 'application/json', //format retour de la reponse
                'Authorization' => 'Bearer mJxTXVXMfRzLg6ZdhUhM4F6Eutcm1ZiPk4fNmvBMxyNR4ciRsc8v0hOmlzA0vTaX'
            ],
            'body' => json_encode($orderArray)
        ]);
        $statusCode = $response->getStatusCode();
        // $statusCode = 200
        $contentType = $response->getHeaders()['content-type'][0];
        // $contentType = 'application/json'
        $content = $response->getContent();
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        $content = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]
        // $idapi=$content['order_id'];
        // $order->setIdApi($idapi);
        
        return $content;
        
        
        
    } 

    /**
     * @Route("/", name="landing_page")
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $products  = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findAll();
 

        $entityInstance=[
            'product' => new Product(),
            'client' => new Client(),
            'shipping' => new Shipping(),
            'order'=> new Order(),
            ];

        $entityInstance["order"]->setCreatedAt(new \DateTime());
       
        $formBuilder = $this->createFormBuilder($entityInstance,
         [
            'csrf_protection' => false,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'order_csrf'
            ])
            ->add('client',ClientType::class)
            ->add('product',ProductType::class)
            ->add('shipping',ShippingType::class)
            ->add('order',OrderType::class);

        $form=$formBuilder->getForm();
        $form->handleRequest($request);

        // dd($form);
        if ($form->isSubmitted() && $form->isValid()) {

            $productId = $request->get('order')["cart"]["cart_products"][0];
            $entityManager = $this->getDoctrine()->getManager();
            
            $entityManager->persist($entityInstance['client']);
            $entityManager->persist($entityInstance['shipping']);

            $entityInstance["client"]->setShipping($entityInstance['shipping']);
            $entityInstance["shipping"]->setClient($entityInstance['client']);
           
            
            $product = $entityManager->find(Product::class, $productId);
            $entityInstance["order"]->setProduct($product);
          
            $entityInstance["order"]->setClient($entityInstance['client']);
            $entityManager->persist($entityInstance['order']);

            $entityManager->flush();

            $reponseapi= $this->apiOrder($entityInstance["order"]);
       
            $idapi=$reponseapi['order_id'];
            
            $entityInstance["order"]->setIdApi($idapi);
            $entityManager->persist($entityInstance['order']);

            $entityManager->flush();
           
            

            return $this->redirectToRoute('payment',['id'=> $entityInstance['order']->getId()]);
        
        }

      

        return $this->render('landing_page/index_new.html.twig', [
            'entityInstance'=>[
                'product' => new Product(),
                'client' => new Client(),
                'shipping' => new Shipping(),
                'order'=> new Order(),
                ],
            'products'=>$products,
            'form' => $form->createView(),
            
            
        ]);
    
    }

    







    /**
     * @Route("/confirmation", name="confirmation")
     */
    public function confirmation()
    {
        return $this->render('landing_page/confirmation.html.twig', [

        ]);
    }
}
