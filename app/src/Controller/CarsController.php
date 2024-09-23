<?php

namespace App\Controller;

use App\Entity\Car;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class CarsController extends AbstractController
{
    #[Route('/api/v1/cars', name: 'app_cars_list', methods: ["GET"])]
    public function getCarsList(EntityManagerInterface $em): Response
    {
        $cars = $em->getRepository(Car::class)->findAll();

        foreach ($cars as $car){
            $res[] = $car->serialize();
        }

        return $this->json($res);
    }

    #[Route('/api/v1/cars/{id}', name: 'app_car', methods: ["GET"])]
    public function getCarById(EntityManagerInterface $em, int $id): Response
    {
        $car = $em->getRepository(Car::class)->find($id);

        
        if (!$car) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        return $this->json($car);
    }
}
