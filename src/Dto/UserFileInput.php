<?php

namespace App\Dto;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class UserFileInput
{
    #[Assert\NotBlank]
    public string $fullname = '';

    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email = '';

    #[Assert\NotBlank]
    public string $password = '';

    #[Assert\NotNull]
    #[Assert\File(mimeTypes: ['image/jpeg', 'image/png'])]
    public ?UploadedFile $file = null;

    // Ajout des setters pour permettre le mapping par Symfony
    public function setFullname(string $fullname): void
    {
        $this->fullname = $fullname;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function setFile(?UploadedFile $file): void
    {
        $this->file = $file;
    }

    // (Optionnel) Ajout des getters pour les tests ou la vérification
    public function getFullname(): string
    {
        return $this->fullname;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }
}
