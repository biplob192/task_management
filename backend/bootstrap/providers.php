<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\RepositoryServiceProvider::class, // This will add auto, if created by: php artisann make:provider ProviderName
    Spatie\Permission\PermissionServiceProvider::class, // This added manually
];
