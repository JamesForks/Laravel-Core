<?php

/*
 * This file is part of Laravel Core.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Core\Subscribers;

use Illuminate\Console\Command;
use Illuminate\Contracts\Events\Dispatcher;

/**
 * This is the command subscriber class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class CommandSubscriber
{
    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Contracts\Events\Dispatcher $events
     *
     * @return void
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen('command.generatekey', __CLASS__.'@onGenerateKey');
        $events->listen('command.cacheconfig', __CLASS__.'@onCacheConfig');
        $events->listen('command.cacheroutes', __CLASS__.'@onCacheRoutes');
        $events->listen('command.publishvendors', __CLASS__.'@onPublishVendors');
        $events->listen('command.resetmigrations', __CLASS__.'@onResetMigrations');
        $events->listen('command.runmigrations', __CLASS__.'@onRunMigrations');
        $events->listen('command.runseeding', __CLASS__.'@onRunSeeding');
        $events->listen('command.linkstorage', __CLASS__.'@onLinkStorage');
        $events->listen('command.updatecache', __CLASS__.'@onUpdateCache');
    }

    /**
     * Handle a command.generatekey event.
     *
     * @param \Illuminate\Console\Command $command
     *
     * @return void
     */
    public function onGenerateKey(Command $command)
    {
        $command->call('key:generate');
    }

    /**
     * Handle a command.cacheconfig event.
     *
     * @param \Illuminate\Console\Command $command
     *
     * @return void
     */
    public function onCacheConfig(Command $command)
    {
        $command->call('config:cache');
    }

    /**
     * Handle a command.cacheroutes event.
     *
     * @param \Illuminate\Console\Command $command
     *
     * @return void
     */
    public function onCacheRoutes(Command $command)
    {
        $command->call('route:cache');
    }

    /**
     * Handle a command.publishvendors event.
     *
     * @param \Illuminate\Console\Command $command
     *
     * @return void
     */
    public function onPublishVendors(Command $command)
    {
        $command->call('vendor:publish');
    }

    /**
     * Handle a command.resetmigrations event.
     *
     * @param \Illuminate\Console\Command $command
     *
     * @return void
     */
    public function onResetMigrations(Command $command)
    {
        $command->call('migrate:reset', ['--force' => true]);
    }

    /**
     * Handle a command.runmigrations event.
     *
     * @param \Illuminate\Console\Command $command
     *
     * @return void
     */
    public function onRunMigrations(Command $command)
    {
        $command->call('migrate', ['--force' => true]);
    }

    /**
     * Handle a command.runseeding event.
     *
     * @param \Illuminate\Console\Command $command
     *
     * @return void
     */
    public function onRunSeeding(Command $command)
    {
        $command->call('db:seed', ['--force' => true]);
    }

    /**
     * Handle a command.linkstorage event.
     *
     * @param \Illuminate\Console\Command $command
     *
     * @return void
     */
    public function onLinkStorage(Command $command)
    {
        if ($command->has('storage:link')) {
            $command->call('storage:link');
        }
    }

    /**
     * Handle a command.updatecache event.
     *
     * @param \Illuminate\Console\Command $command
     *
     * @return void
     */
    public function onUpdateCache(Command $command)
    {
        $command->line('Clearing cache...');
        $command->call('cache:clear');
        $command->info('Cache cleared!');
    }
}
