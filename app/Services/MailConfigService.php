<?php

namespace App\Services;

class MailConfigService
{
    /**
     * Apply settings from database to Laravel's configuration at runtime.
     */
    public static function applySettings(): bool
    {
        if (!class_exists('\App\Models\Setting')) return false;

        try {
            $mailer     = setting('mail_mailer', config('mail.default', 'smtp'));
            $host       = setting('mail_host', config('mail.mailers.smtp.host'));
            $port       = setting('mail_port', config('mail.mailers.smtp.port'));
            $username   = setting('mail_username', config('mail.mailers.smtp.username'));
            $password   = setting('mail_password', config('mail.mailers.smtp.password'));
            $encryption = setting('mail_encryption', config('mail.mailers.smtp.encryption'));
            $fromAddr   = $username; // Use username directly as from address for Gmail compatibility
            $fromName   = setting('mail_from_name', 'VietTinMart');
            
            // Gmail requires From address to match account
            if (empty($fromAddr) || $fromAddr === 'hello@example.com') $fromAddr = $username;

            // If SMTP is used but host is missing, we return false to indicate we are not using dynamic SMTP
            if ($mailer === 'smtp' && empty($host)) {
                return false;
            }

            // FORCE PORT 587 and TLS for maximum compatibility on local
            if ($mailer === 'smtp' && $host === 'smtp.gmail.com') {
                $port = 587;
                $encryption = 'tls';
            }

            config([
                'mail.default'                 => $mailer,
                'mail.mailers.smtp.host'       => $host,
                'mail.mailers.smtp.port'       => $port,
                'mail.mailers.smtp.username'   => $username,
                'mail.mailers.smtp.password'   => $password,
                'mail.mailers.smtp.encryption' => $encryption,
                'mail.from.address'            => $fromAddr,
                'mail.from.name'               => $fromName,
            ]);

            // Cấu hình bổ sung để tránh lỗi SSL/TLS trên Localhost/MAMP
            if ($mailer === 'smtp') {
                config([
                    'mail.mailers.smtp.verify_peer' => false,
                    'mail.mailers.smtp.verify_peer_name' => false,
                    'mail.mailers.smtp.timeout' => 45, 
                ]);
            }

            // Force Laravel to re-resolve the mailer with new config
            if (class_exists('\Illuminate\Support\Facades\Mail')) {
                try {
                    $mailManager = app('mail.manager');
                    // Clear both resolved mailers and the default mail driver
                    $mailManager->forgetMailers();
                } catch (\Exception $e) {
                    \Log::warning('MailManager forgetMailers failed: ' . $e->getMessage());
                }
            }

            return true;
        } catch (\Exception $e) {
            \Log::error('Error applying mail settings: ' . $e->getMessage());
            return false;
        }
    }
}
