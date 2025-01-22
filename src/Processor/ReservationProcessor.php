<?php

namespace App\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ReservationProcessor implements ProcessorInterface
{
    private $entityManager;
    private $mailer;

    public function __construct(EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if (!$data instanceof Reservation) {
            return;
        }

        // Persist la réservation dans la base de données
        $this->entityManager->persist($data);
        $this->entityManager->flush();

        // Envoie un email après la persistance
        $email = (new Email())
            ->from('ton_email@gmail.com')
            ->to('antoniorollande@gmail.com')
            ->subject('Nouvelle réservation ajoutée')
            ->html(sprintf(
                '<p>Une nouvelle réservation a été ajoutée :</p>
                 <p><strong>Nom du client :</strong> %s</p>
                 <p><strong>Date de réservation :</strong> %s</p>',
                $data->getUser(),
                $data->getCheckInDate()->format('Y-m-d H:i:s')
            ));

        $this->mailer->send($email);
    }
}
