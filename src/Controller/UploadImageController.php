<?php

namespace App\Controller;

use App\Dto\UserFileInput;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UploadImageController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    public function __invoke(UserFileInput $data, Request $request): JsonResponse
    {
        // Récupération des données depuis le DTO
        // $fullname = $data->fullname;
        // $email = $data->email;
        // $password = $data->password;
        
        $data = $request->request->all();
        $fullname = $data["fullname"];
        $email =$data["email"];
        $password = $data["password"];
        $file = $request->files->get('file');

        // Validation des données obligatoires
        if (!$fullname || !$email || !$password || !$file) {
            return new JsonResponse(['error' => 'All fields are required.'], Response::HTTP_BAD_REQUEST);
        }

        // Gestion du fichier uploadé
        $uploadDir = __DIR__ . '/../../public/uploads'; // Répertoire pour stocker les fichiers
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Crée le répertoire si nécessaire
        }

        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($uploadDir, $newFilename);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Failed to upload the file.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // Création de l'utilisateur
        $user = new User();
        $user->setFullname($fullname)
            ->setEmail($email)
            ->setPassword($this->passwordHasher->hashPassword($user, $password))
            ->setFilePath('/uploads/' . $newFilename) // Stocke le chemin relatif du fichier
            ->setOriginalName($file->getClientOriginalName());

        // Sauvegarde dans la base de données
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse([
            'message' => 'User created successfully.',
            'user' => [
                'id' => $user->getId(),
                'fullname' => $user->getFullname(),
                'email' => $user->getEmail(),
                'filePath' => $user->getFilePath(),
            ],
        ], Response::HTTP_CREATED);
    }
}
