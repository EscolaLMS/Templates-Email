<?php

namespace EscolaLms\TemplatesEmail\Database\Seeders;

use EscolaLms\Notifications\Facades\EscolaLmsNotifications;
use EscolaLms\TemplatesEmail\Notifications\ResetPassword;
use EscolaLms\TemplatesEmail\Notifications\VerifyEmail;
use Illuminate\Database\Seeder;

class NotificationsSeeder extends Seeder
{
    public function run()
    {
        EscolaLmsNotifications::createDefaultTemplates(ResetPassword::class);
        EscolaLmsNotifications::createDefaultTemplates(VerifyEmail::class);
    }
}
