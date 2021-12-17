<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Customer;
use App\Form\Type\CustomerType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends AbstractApiController
{
    public function indexAction(Request $request, ManagerRegistry $managerRegistry): Response
    {
        $customers = $managerRegistry->getRepository(Customer::class)->findAll();

        return $this->respond($customers);
    }

    public function createAction(Request $request, ManagerRegistry $managerRegistry): Response
    {
        $form = $this->buildForm(CustomerType::class);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->respond($form, Response::HTTP_BAD_REQUEST);
        }

        /** @var Customer $customer */
        $customer = $form->getData();

        $managerRegistry->getManager()->persist($customer);
        $managerRegistry->getManager()->flush();


        return $this->respond($customer);
    }
}