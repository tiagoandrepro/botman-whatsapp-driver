<?php

namespace BotMan\Drivers\WhatsApp\Providers;

use BotMan\BotMan\Drivers\DriverManager;
use \Illuminate\Support\ServiceProvider;

class WhatsAppServiceProvider extends ServiceProvider

{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        if (! $this->isRunningInBotManStudio()) {
            $this->loadDrivers();

            $this->publishes([
                __DIR__.'/../../stubs/whatsapp.php' => config_path('botman/whatsapp.php'),
            ], 'whatsapp-config');
        }
    }

    /**
     * Load BotMan drivers.
     */
    protected function loadDrivers()
    {
        DriverManager::loadDriver(\Botman\Drivers\WhatsApp\WhatsAppDriver::class);

    }

    /**
     * @return bool
     */
    protected function isRunningInBotManStudio()
    {
        return class_exists(StudioServiceProvider::class);
    }
}