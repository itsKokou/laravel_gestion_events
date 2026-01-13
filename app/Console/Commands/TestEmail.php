<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test {email : L\'adresse email de destination}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Teste l\'envoi d\'un email via SMTP';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        $this->info('Configuration SMTP actuelle :');
        $this->line('  Mailer: ' . config('mail.default'));
        $this->line('  Host: ' . config('mail.mailers.smtp.host'));
        $this->line('  Port: ' . config('mail.mailers.smtp.port'));
        $this->line('  Encryption: ' . config('mail.mailers.smtp.encryption'));
        $this->line('  Username: ' . config('mail.mailers.smtp.username'));
        $this->line('  Password: ' . (config('mail.mailers.smtp.password') ? str_repeat('*', min(16, strlen(config('mail.mailers.smtp.password')))) . ' (' . strlen(config('mail.mailers.smtp.password')) . ' caractères)' : 'NON DÉFINI'));
        $this->line('  From: ' . config('mail.from.address') . ' (' . config('mail.from.name') . ')');
        $this->newLine();

        // Vérifications préalables
        $password = config('mail.mailers.smtp.password');
        if (empty($password)) {
            $this->error('❌ MAIL_PASSWORD n\'est pas défini dans votre .env !');
            return Command::FAILURE;
        }

        if (strlen($password) !== 16 && !str_contains($password, ' ')) {
            $this->warn('⚠️  Le mot de passe fait ' . strlen($password) . ' caractères.');
            $this->warn('   Un mot de passe d\'application Gmail fait normalement 16 caractères.');
        }

        if (str_contains($password, ' ')) {
            $this->error('❌ Le mot de passe contient des espaces !');
            $this->error('   Les mots de passe d\'application Gmail ne doivent PAS contenir d\'espaces.');
            $this->line('   Supprimez les espaces dans votre fichier .env');
            return Command::FAILURE;
        }

        $this->info("Envoi d'un email de test à : {$email}");

        try {
            Mail::raw('Ceci est un email de test depuis Win\'s Events. Si vous recevez ce message, la configuration SMTP fonctionne correctement !', function ($message) use ($email) {
                $message->to($email)
                    ->subject('Test SMTP - Win\'s Events');
            });

            $this->info('✅ Email envoyé avec succès !');
            $this->line("Vérifiez votre boîte de réception (et les spams) : {$email}");

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors de l\'envoi de l\'email :');
            $this->error($e->getMessage());
            $this->newLine();

            // Détection spécifique des erreurs Gmail
            if (str_contains($e->getMessage(), 'BadCredentials') || str_contains($e->getMessage(), 'Username and Password not accepted')) {
                $this->warn('🔐 Problème d\'authentification Gmail détecté :');
                $this->newLine();
                $this->line('Pour résoudre ce problème :');
                $this->line('');
                $this->line('1. ✅ Activez la validation en 2 étapes sur votre compte Google :');
                $this->line('   https://myaccount.google.com/security');
                $this->line('');
                $this->line('2. 🔑 Créez un mot de passe d\'application :');
                $this->line('   https://myaccount.google.com/apppasswords');
                $this->line('   - Sélectionnez "Autre (nom personnalisé)"');
                $this->line('   - Entrez "Win\'s Events" comme nom');
                $this->line('   - Copiez le mot de passe généré (16 caractères)');
                $this->line('');
                $this->line('3. 📝 Utilisez ce mot de passe dans votre .env :');
                $this->line('   MAIL_PASSWORD=le_mot_de_passe_16_caracteres');
                $this->line('');
                $this->line('⚠️  Important : Utilisez le MOT DE PASSE D\'APPLICATION, pas votre mot de passe Gmail normal !');
            } else {
                $this->warn('Vérifiez votre configuration dans le fichier .env :');
                $this->line('  - MAIL_MAILER=smtp');
                $this->line('  - MAIL_HOST=smtp.gmail.com');
                $this->line('  - MAIL_PORT=587');
                $this->line('  - MAIL_USERNAME=votre_email@gmail.com');
                $this->line('  - MAIL_PASSWORD=votre_mot_de_passe_application');
                $this->line('  - MAIL_ENCRYPTION=tls');
            }

            return Command::FAILURE;
        }
    }
}
