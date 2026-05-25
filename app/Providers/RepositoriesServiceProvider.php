<?php

namespace App\Providers;

use App\Repositories\Contracts\Clients\ClientRepositoryInterface;
use App\Repositories\Clients\ClientRepository;
use App\Repositories\Contracts\Contacts\ContactRepositoryInterface;
use App\Repositories\Contacts\ContactRepository;
use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{
    protected array $classes = [
        ClientRepositoryInterface::class => ClientRepository::class,
        ContactRepositoryInterface::class => ContactRepository::class,
    ];

    public function register(): void
    {
        foreach ($this->classes as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
    }

    public function boot(): void
    {
        //
    }
}
