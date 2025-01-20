<?php

namespace App\Dto;

use Symfony\Component\Serializer\Annotation\Groups;

class HotelRoom
{
    #[Groups(['hotelRoom:read'])]
    public string  $roomNumber;

    #[Groups(['hotelRoom:read'])]
    public array $hotel;

    public function __construct(string $roomNumber, array $hotel)
    {
        $this->roomNumber = $roomNumber;
        $this->hotel = $hotel;
    }
}