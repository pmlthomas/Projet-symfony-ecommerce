<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAddressController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/compte/addresses", name="account_address")
     */
    public function index(): Response
    {
        return $this->render('account/address.html.twig');
    }

    /**
     * @Route("/compte/ajouter-une-addresse", name="add_address")
     */
    public function add(Cart $cart ,Request $request): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $address->setUser($this->getUser());
            $this->entityManager->persist($address);
            $this->entityManager->flush();
            if($cart->get()){
                return $this->redirectToRoute('order');
            }
            return $this->redirectToRoute('account_address');
        }
        return $this->render('account/address_add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/compte/remove/{id}", name="remove_address")
     */
    public function remove($id)
    {
        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);
        $this->entityManager->remove($address);
        $this->entityManager->flush();
        return $this->redirectToRoute('account_address');
    }

     /**
     * @Route("/compte/edit/{id}", name="edit_address")
     */
    public function edit(Request $request, $id) : Response
    {
        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);

        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->flush();
            return $this->redirectToRoute('account_address');
        }
        return $this->render('account/edit_address.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
