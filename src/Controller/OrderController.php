<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Address;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Stripe;

class OrderController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/commande", name="order")
     */
    public function index(Cart $cart, Request $request): Response
    {
        if(!$this->getUser()->getAddresses()->getValues()){
            return $this->redirectToRoute('add_address');
        }
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);
        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart->getFull()
        ]);
    }

     /**
     * @Route("/commande/recapitulatif", name="order_recap")
     */
    public function add(Cart $cart, Request $request): Response
    {
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $date = new DateTimeImmutable();
            $carrier = $form->get('carrier')->getData();
            $delivery = $form->get('addresses')->getData();
            $delivery_content = $delivery->getName();
            $delivery_content .=$delivery->getFirstname();
            $delivery_content .=$delivery->getLastname();
            $delivery_content .=$delivery->getPhone();
            if($delivery->getCompany()){
                $delivery_content .=$delivery->getCompany();
            }
            $delivery_content .=$delivery->getAddress();
            $delivery_content .=$delivery->getPostal();
            $delivery_content .=$delivery->getCity();
            $delivery_content .=$delivery->getCountry();

            $order = new Order();
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setCarrierName($carrier->getName());
            $order->setCarrierPrice($carrier->getPrice());
            $order->setDelivery($delivery_content);
            $order->setIsPaid(0);

            $this->entityManager->persist($order);

            $totalPrice = null;

            foreach($cart->getFull() as $inCartProduct){
                $orderDetails = new OrderDetails();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($inCartProduct['product']->getName());
                $orderDetails->setQuantity($inCartProduct['quantity']);
                $orderDetails->setPrice($inCartProduct['product']->getPrice());
                $orderDetails->setTotal($inCartProduct['product']->getPrice() * $inCartProduct['quantity']);
                $totalPrice = $totalPrice + ($orderDetails->getPrice() * $orderDetails->getQuantity());
            }
            $this->entityManager->persist($orderDetails);
            $this->entityManager->flush();

            return $this->render('order/add.html.twig', [
                'form' => $form->createView(),
                'carrier' => $carrier,
                'cart' => $cart->getFull(),
                'delivery' => $delivery,
                'totalPrice' => $totalPrice
            ]);
        }
        return $this->redirectToRoute('cart');
    }
}
