<?php
// src/EventSubscriber/ReservationSubscriber.php
namespace App\EventSubscriber;

use App\Entity\Reservation;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs as EventLifecycleEventArgs;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ReservationSubscriber implements EventSubscriber
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
        
    }

    public function getSubscribedEvents(): array
    {
        dump('postPersist triggered');
        return [
            'postPersist', // Cet événement est déclenché après l'insertion en base de données
        ];
    }

    public function postPersist(EventLifecycleEventArgs $args): void
    {
        
        $entity = $args->getObject();

        // Vérifie si l'entité est une réservation
        if (!$entity instanceof Reservation) {
            return;
        }
       
        // Crée l'email
        $email = (new Email())
            ->from('antoniorollande@gmail.com')
            ->to('antoniorollande@gmail.com')
            ->subject('Nouvelle réservation ajoutée')
            ->html(sprintf(
                '<p>Une nouvelle réservation a été ajoutée :</p>
                 <p><strong>Nom du client :</strong> %s</p>
                 <p><strong>Date de réservation :</strong> %s</p>',
              
            ));

        // Envoie l'email
        $this->mailer->send($email);
    }
}
