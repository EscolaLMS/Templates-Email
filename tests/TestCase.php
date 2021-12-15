<?php

namespace EscolaLms\TemplatesEmail\Tests;

use EscolaLms\Auth\EscolaLmsAuthServiceProvider;
use EscolaLms\Auth\Models\User;
use EscolaLms\Auth\Tests\Models\Client;
use EscolaLms\Core\Tests\TestCase as CoreTestCase;
use EscolaLms\Courses\EscolaLmsCourseServiceProvider;
use EscolaLms\Scorm\EscolaLmsScormServiceProvider;
use EscolaLms\Settings\EscolaLmsSettingsServiceProvider;
use EscolaLms\Templates\EscolaLmsTemplatesServiceProvider;
use EscolaLms\TemplatesEmail\Database\Seeders\TemplatesEmailSeeder;
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
        $this->seed(TemplatesEmailSeeder::class);
    }

    protected function getPackageProviders($app)
    {
        $providers = [
            ...parent::getPackageProviders($app),
            PermissionServiceProvider::class,
            PassportServiceProvider::class,
            EscolaLmsTemplatesServiceProvider::class,
            EscolaLmsTemplatesEmailServiceProvider::class,
        ];
        if (class_exists(\EscolaLms\Auth\EscolaLmsAuthServiceProvider::class)) {
            $providers[] = EscolaLmsAuthServiceProvider::class;
        }
        if (class_exists(\EscolaLms\Courses\EscolaLmsCourseServiceProvider::class)) {
            $providers[] = EscolaLmsCourseServiceProvider::class;
        }
        if (class_exists(\EscolaLms\Scorm\EscolaLmsScormServiceProvider::class)) {
            $providers[] = EscolaLmsScormServiceProvider::class;
        }
        if (class_exists(\EscolaLms\Settings\EscolaLmsSettingsServiceProvider::class)) {
            $providers[] = EscolaLmsSettingsServiceProvider::class;
        }
        return $providers;
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('auth.providers.users.model', User::class);
        $app['config']->set('passport.client_uuids', true);
        $app['config']->set(EscolaLmsTemplatesEmailServiceProvider::CONFIG_KEY . '.mjml.use_api', true);
        $app['config']->set(EscolaLmsTemplatesEmailServiceProvider::CONFIG_KEY . '.mjml.api_id', '4dd2c787-28c3-4738-a97f-7e386e298c58');
        $app['config']->set(EscolaLmsTemplatesEmailServiceProvider::CONFIG_KEY . '.mjml.api_secret', '93f68efd-4d4f-42d5-ad42-5bb8349a0db2');
    }
}
