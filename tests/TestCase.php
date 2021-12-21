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
use EscolaLms\TemplatesEmail\Services\Contracts\MjmlServiceContract;
use EscolaLms\TemplatesEmail\Services\MjmlService;
use Laravel\Passport\Passport;
use Laravel\Passport\PassportServiceProvider;
use Mockery;
use Mockery\MockInterface;
use Spatie\Permission\PermissionServiceProvider;

class TestCase extends CoreTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Passport::useClientModel(Client::class);

        $this->instance(
            MjmlServiceContract::class,
            Mockery::mock(MjmlService::class, function (MockInterface $mock) {
                $mock->shouldReceive('render')->andReturnArg(0);
            })
        );
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
        // Add api keys to local phpunit.xml / testbench.yaml; use github repository secrets in github actions
    }
}
