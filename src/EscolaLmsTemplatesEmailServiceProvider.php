<?php

namespace EscolaLms\TemplatesEmail;

use EscolaLms\Auth\EscolaLmsAuthServiceProvider;
use EscolaLms\Auth\Events\EscolaLmsForgotPasswordTemplateEvent;
use EscolaLms\Auth\Listeners\CreatePasswordResetToken;
use EscolaLms\Settings\Facades\AdministrableConfig;
use EscolaLms\Templates\Repository\Contracts\TemplateRepositoryContract;
use EscolaLms\TemplatesEmail\Core\EmailChannel;
use EscolaLms\TemplatesEmail\Providers\AuthTemplatesEventServiceProvider;
use EscolaLms\TemplatesEmail\Providers\AuthTemplatesServiceProvider;
use EscolaLms\TemplatesEmail\Providers\CourseTemplatesServiceProvider;
use EscolaLms\TemplatesEmail\Providers\CsvUsersTemplatesServiceProvider;
use EscolaLms\TemplatesEmail\Rules\MjmlRule;
use EscolaLms\TemplatesEmail\Services\Contracts\MjmlServiceContract;
use EscolaLms\TemplatesEmail\Services\MjmlService;
use Illuminate\Support\ServiceProvider;

/**
 * SWAGGER_VERSION
 */
class EscolaLmsTemplatesEmailServiceProvider extends ServiceProvider
{
    const CONFIG_KEY = 'escola_templates_email';

    public $singletons = [
        MjmlServiceContract::class => MjmlService::class,
    ];

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config.php', self::CONFIG_KEY);

        if (class_exists(\EscolaLms\Auth\EscolaLmsAuthServiceProvider::class)) {
            if (!$this->app->getProviders(EscolaLmsAuthServiceProvider::class)) {
                $this->app->register(EscolaLmsAuthServiceProvider::class);
            }
            $this->app->register(AuthTemplatesEventServiceProvider::class);
            $this->app->register(AuthTemplatesServiceProvider::class);
        }
        if (class_exists(\EscolaLms\Courses\EscolaLmsCourseServiceProvider::class)) {
            $this->app->register(CourseTemplatesServiceProvider::class);
        }
        if (class_exists(\EscolaLms\CsvUsers\EscolaLmsCsvUsersServiceProvider::class)) {
            $this->app->register(CsvUsersTemplatesServiceProvider::class);
        }

        CreatePasswordResetToken::setRunEventForgotPassword(
            function () {
                $templateRepository = app(TemplateRepositoryContract::class);
                return !empty($templateRepository->findTemplateDefault(
                    EscolaLmsForgotPasswordTemplateEvent::class,
                    EmailChannel::class
                ));
            }
        );
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }

        if (class_exists(\EscolaLms\Settings\Facades\AdministrableConfig::class)) {
            AdministrableConfig::registerConfig(self::CONFIG_KEY . '.mjml.use_api', ['required', 'bool'], false);
            AdministrableConfig::registerConfig(self::CONFIG_KEY . '.mjml.api_id', ['required', 'string'], false);
            AdministrableConfig::registerConfig(self::CONFIG_KEY . '.mjml.api_secret', ['required', 'string'], false);
            AdministrableConfig::registerConfig(self::CONFIG_KEY . '.mjml.default_template', ['required', 'string', new MjmlRule()], false);
        }
    }

    public function bootForConsole()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->publishes([
            __DIR__ . '/config.php' => config_path(self::CONFIG_KEY . '.php'),
        ], self::CONFIG_KEY . '.config');
    }
}
