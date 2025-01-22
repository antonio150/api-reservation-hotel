<?php 
// src/Controller/MailController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class MailController extends AbstractController
{
    #[Route('/send-email', name: 'send_email')]
    public function sendEmail(MailerInterface $mailer)
    {
        $email = (new Email())
            ->from('antoniorollande@gmail.com') // Remplace par ton adresse Gmail
            ->to('antoniorollande@gmail.com') // Remplace par l'adresse du destinataire
            ->subject('Salut depuis Symfony et Gmail 🚀')
            ->text('Ceci est un email envoyé avec Symfony Mailer.')
            ->html('<p>Ceci est un <strong>email HTML</strong> envoyé avec Symfony Mailer.</p>');

        $mailer->send($email);

        return $this->json(['message' => 'Email envoyé avec succès !']);
    }
}
