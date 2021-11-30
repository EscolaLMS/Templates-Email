<?php

namespace EscolaLms\TemplatesEmail\Tests;

use EscolaLms\Auth\EscolaLmsAuthServiceProvider;
use EscolaLms\Auth\Models\User;
use EscolaLms\Auth\Tests\Models\Client;
use EscolaLms\Core\Tests\TestCase as CoreTestCase;
use EscolaLms\Notifications\EscolaLmsNotificationsServiceProvider;
use EscolaLms\Notifications\Facades\EscolaLmsNotifications;
use EscolaLms\Templates\EscolaLmsTemplatesServiceProvider;
use EscolaLms\TemplatesEmail\Database\Seeders\NotificationsSeeder;
use EscolaLms\TemplatesEmail\EscolaLmsTemplatesEmailServiceProvider;
use Laravel\Passport\Passport;
use Laravel\Passport\PassportServiceProvider;
use Spatie\Permission\PermissionServiceProvider;

class TestCase extends CoreTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Passport::useClientModel(Client::class);
        $this->seed(NotificationsSeeder::class);
    }

    protected function getPackageProviders($app)
    {
        return [
            ...parent::getPackageProviders($app),
            PermissionServiceProvider::class,
            PassportServiceProvider::class,
            EscolaLmsAuthServiceProvider::class,
            EscolaLmsNotificationsServiceProvider::class,
            EscolaLmsTemplatesServiceProvider::class,
            EscolaLmsTemplatesEmailServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('auth.providers.users.model', User::class);
        $app['config']->set('passport.client_uuids', true);
    }
}
