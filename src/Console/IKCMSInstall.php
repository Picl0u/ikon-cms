<?php

namespace Piclou\Ikcms\Console;

use Illuminate\Console\Command;
use Piclou\Ikcms\Entities\User;

class IKCMSInstall extends Command{

    protected $signature = 'ikcms:install';
    protected $description = 'Installation du CMS : IKCMS';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->line("Bienvenue pour l'installation du CMS : IKCMS.");
        $this->line("Version  : ".config("ikcms.apiVersion"));

        $this->info('Ajouts des administrateurs : ');

        $firstname = $this->ask("Prénom de l'administrateur ?");
        $lastname = $this->ask("Nom de l'administrateur ?");

        $email = $this->ask("Ravi de vous rencontrer {$firstname} {$lastname}, quel est votre email?");
        $tries = 0;
        while (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if ($tries < 3) {
                $message = 'Oops! Cet email semble invalide, pouvons-nous réessayer?';
            } elseif ($tries >= 3 && $tries <= 6) {
                $message = 'Non vraiment, donnez-moi un email correct ...';
            } elseif ($tries >= 6 && $tries <= 9) {
                $message = 'Sérieusement maintenant, ne faisons pas ça toute la journée ... qu\'est-ce que c\'est?';
            } elseif ($tries >= 10) {
                $this->error('J\'abandonne');
                exit();
            }
            $email = $this->ask($message);
            $tries++;
        }
        $password = $this->secret('Choisissez un mot de passe (caché)');
        $passwordConfirm = $this->secret('Confirmez votre mot de passe (caché)');
        while ($password != $passwordConfirm) {
            $password = $this->secret('Oops! Vos mots de passe sont différents');
            $passwordConfirm = $this->secret('Confirmez le');
        }
        $this->info('Création du compte en cours');
        $insert = [
            'online' => 1,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'name' => $firstname . " " . $lastname,
            'email' => $email,
            'password' => bcrypt($password),
            'role' => 'superAdmin',
        ];
        $user = User::create($insert);
        $this->user = $user;

        if ($this->confirm('Voulez vous un second administrateurr?')) {
            $firstnameSecond = $this->ask('Quel est son prénom ?');
            $lastnameSecond = $this->ask('Quel est son nom ?');
            $emailSecond = $this->ask("Quel est l'email de {$firstnameSecond} {$lastnameSecond} ?");
            $tries = 0;
            while (!filter_var($emailSecond, FILTER_VALIDATE_EMAIL)) {
                if ($tries < 3) {
                    $message = 'Oops! Cet email semble invalide, pouvons-nous réessayer?';
                } elseif ($tries >= 3 && $tries <= 6) {
                    $message = 'Non vraiment, donnez-moi un email correct ...';
                } elseif ($tries >= 6 && $tries <= 9) {
                    $message = 'Sérieusement maintenant, ne faisons pas ça toute la journée ... qu\'est-ce que c\'est?';
                } elseif ($tries >= 10) {
                    $this->error('Pas possible...');
                }
                $emailSecond = $this->ask($message);
                $tries++;
            }
            $password = $this->secret('Choisissez un mot de passe (caché)');
            $passwordConfirm = $this->secret('Confirmez votre mot de passe (caché)');
            while ($password != $passwordConfirm) {
                $password = $this->secret('Oops! Vos mots de passe sont différents');
                $passwordConfirm = $this->secret('Confirmez le');
            }
            $this->info('Création du compte en cours');
            $insert = [
                'online' => 1,
                'firstname' => $firstnameSecond,
                'lastname' => $lastnameSecond,
                'name' => $firstnameSecond . " " . $lastnameSecond,
                'email' => $emailSecond,
                'password' => bcrypt($password),
                'role' => 'superAdmin',
            ];
            $userSecond = User::create($insert);

            $this->line('--------------------------------------------------------');
            $this->info("Tout c'est correctement déroulé. L'installation est complète.");
        }
    }
}
