<?php

namespace App\Classe;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

Class Cart
{
    private $session;
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session){
        $this->entityManager = $entityManager;
        $this->session = $session;
    }
    public function add($id){
        $cart = $this->get('cart', []);

        if(!empty($cart[$id])){
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        return $this->session->set('cart', $cart);
    }
    public function decrease($id){
        $cart = $this->get('cart', []);

        if($cart[$id] > 1){
            $cart[$id]--;
        } else {
            unset($cart[$id]);
        }
        return $this->session->set('cart', $cart);
    }
    public function get(){
        return $this->session->get('cart');
    }
    public function delete($id){
        $cart = $this->session->get('cart', []);
        unset($cart[$id]);
        return $this->session->set('cart', $cart);
    }
    public function getFull(){
        $cartComplete = [];
        if($this->get()){
            foreach($this->get() as $id => $quantity){
                $productInfos = $this->entityManager->getRepository(Product::class)->findOneById($id);
                if(!$productInfos){
                    $this->delete($id);
                    continue;
                } else {
                    $cartComplete[] = [
                        'product' => $productInfos,
                        'quantity' => $quantity
                    ];
                }
            }
        }
        return $cartComplete;
    }
}