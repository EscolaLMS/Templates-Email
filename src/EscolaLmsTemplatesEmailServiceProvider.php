<?php

namespace EscolaLms\TemplatesEmail;

use EscolaLms\Templates\Services\Contracts\VariablesServiceContract;
use EscolaLms\Templates\Services\VariablesService;
use EscolaLms\TemplatesEmail\Enums\Email\ResetPasswordVariables;
use EscolaLms\TemplatesEmail\Enums\Email\VerifyEmailVariables;
use EscolaLms\TemplatesEmail\Providers\EventServiceProvider;
use EscolaLms\TemplatesEmail\Repositories\Contracts\EmailTemplateRepositoryContract;
use EscolaLms\TemplatesEmail\Repositories\EmailTemplateRepository;
use Illuminate\Support\ServiceProvider;

/**
 * SWAGGER_VERSION
 */
class EscolaLmsTemplatesEmailServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        EmailTemplateRepositoryContract::class => EmailTemplateRepository::class,
    ];

    public function register()
    {
        $this->app->register(EventServiceProvider::class);
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }

        /** @var VariablesService $variablesService */
        $variablesService = resolve(VariablesServiceContract::class);

        $variablesService::addToken(ResetPasswordVariables::class, ResetPasswordVariables::getType(), ResetPasswordVariables::getSubtype());
        $variablesService::addToken(VerifyEmailVariables::class, VerifyEmailVariables::getType(), VerifyEmailVariables::getSubtype());
    }

    public function bootForConsole()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
