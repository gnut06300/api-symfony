<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Product;
use App\Form\Type\ProductType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends AbstractApiController
{
    public function indexAction(Request $request, ManagerRegistry $managerRegistry): Response
    {
        $products = $managerRegistry->getRepository(Product::class)->findAll();

        return $this->respond($products);
    }

    public function createAction(Request $request, ManagerRegistry $managerRegistry): Response
    {
        $form = $this->buildForm(ProductType::class);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->respond($form, Response::HTTP_BAD_REQUEST);
        }

        /** @var Product $product */
        $product = $form->getData();

        $managerRegistry->getManager()->persist($product);
        $managerRegistry->getManager()->flush();

        return $this->respond($product);
    }
}