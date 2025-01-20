<?php
namespace App\Controller;

use App\Dto\HotelRoom;
use App\Entity\Room;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class HotelUserController extends AbstractController
{
    public function __invoke(EntityManagerInterface $entityManager): JsonResponse
    {
        $rooms = $entityManager->getRepository(Room::class)
            ->createQueryBuilder('a')
            ->leftJoin('a.hotel', 'b')
            ->select('a.roomNumber AS room, b.name AS nameHotel')
            ->getQuery()
            ->getArrayResult();

        $result = [];

        
        // Si la requête retourne des résultats
        if (!empty($rooms)) {
            foreach ($rooms as $room) {
                $result[] = new HotelRoom(
                    $room['room'],
                    $room['nameHotel'] ? [$room['nameHotel']] : []
                );
            }
        } else {
            // Si aucun résultat, retourner un tableau vide ou un tableau contenant un DTO par défaut
            $result = []; // Tableau vide
        }

        return new JsonResponse($result);
 // Toujours retourner un tableau
    }
}
