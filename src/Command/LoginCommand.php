<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;


#[AsCommand(
    name: 'app:login',
    description: 'Add a short description for your command',
)]
class LoginCommand extends Command
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        parent::__construct();
        $this->httpClient = $httpClient;
    }

    protected static $defaultName = 'app:login';

    protected function configure()
    {
        $this->setDescription('Authenticates and retrieves a JWT token.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Demander l'email et le mot de passe à l'utilisateur
        $email = $io->ask('Enter your email');
        $password = $io->askHidden('Enter your password');

        // URL de l'endpoint de connexion
        $url = 'http://127.0.0.1:8000/api/login';

        // Exécuter la requête POST pour récupérer le token JWT
        try {
            $response = $this->httpClient->request('POST', $url, [
                'json' => [
                    'email' => $email,
                    'password' => $password,
                ],
            ]);

            // Extraire le token JWT
            $data = $response->toArray();
            if (isset($data['token'])) {
                $token = $data['token'];
                $io->success("Authentication successful! Token: " . $token);
                return Command::SUCCESS;
            }

            $io->error('Invalid response, token not found.');
            return Command::FAILURE;
        } catch (\Exception $e) {
            $io->error('Authentication failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}