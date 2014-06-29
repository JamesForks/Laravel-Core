<?php

/**
 * This file is part of Laravel Core by Graham Campbell.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace GrahamCampbell\Core\Subscribers;

use Illuminate\Config\Repository;
use Illuminate\Console\Command;
use Illuminate\Encryption\Encrypter;
use Illuminate\Events\Dispatcher;

/**
 * This is the command subscriber class.
 *
 * @package    Laravel-Core
 * @author     Graham Campbell
 * @copyright  Copyright 2013-2014 Graham Campbell
 * @license    https://github.com/GrahamCampbell/Laravel-Core/blob/master/LICENSE.md
 * @link       https://github.com/GrahamCampbell/Laravel-Core
 */
class CommandSubscriber
{
    /**
     * The events instance.
     *
     * @var \Illuminate\Events\Dispatcher
     */
    protected $config;

    /**
     * The events instance.
     *
     * @var \Illuminate\Events\Dispatcher
     */
    protected $crypt;

    /**
     * The forced flag.
     *
     * @var bool
     */
    protected $force;

    /**
     * Create a new instance.
     *
     * @param  \Illuminate\Config\Repository  $config
     * @param  \Illuminate\Encryption\Encrypter  $crypt
     * @param  bool  $force
     * @return void
     */
    public function __construct(Repository $config, Encrypter $crypt, $force)
    {
        $this->config = $config;
        $this->crypt = $crypt;
        $this->force = $force;
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     * @return array
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            'command.genappkey',
            'GrahamCampbell\Core\Subscribers\CommandSubscriber@onGenAppKey',
            5
        );
        $events->listen(
            'command.resetmigrations',
            'GrahamCampbell\Core\Subscribers\CommandSubscriber@onResetMigrations',
            5
        );
        $events->listen(
            'command.runmigrations',
            'GrahamCampbell\Core\Subscribers\CommandSubscriber@onRunMigrations',
            5
        );
        $events->listen(
            'command.runseeding',
            'GrahamCampbell\Core\Subscribers\CommandSubscriber@onRunSeeding',
            5
        );
        $events->listen(
            'command.updatecache',
            'GrahamCampbell\Core\Subscribers\CommandSubscriber@onUpdateCache',
            5
        );
        $events->listen(
            'command.genassets',
            'GrahamCampbell\Core\Subscribers\CommandSubscriber@onGenAssets',
            5
        );
    }

    /**
     * Handle a command.genappkey event.
     *
     * @param  \Illuminate\Console\Command  $command
     * @return void
     */
    public function onGenAppKey(Command $command)
    {
        $command->call('key:generate');
        $this->crypt->setKey($this->config->get('app.key'));
    }

    /**
     * Handle a command.resetmigrations event.
     *
     * @param  \Illuminate\Console\Command  $command
     * @return void
     */
    public function onResetMigrations(Command $command)
    {
        if ($this->force) {
            $command->call('migrate:reset', array('--force' => true));
        } else {
            $command->call('migrate:reset');
        }
    }

    /**
     * Handle a command.runmigrations event.
     *
     * @param  \Illuminate\Console\Command  $command
     * @return void
     */
    public function onRunMigrations(Command $command)
    {
        if ($this->force) {
            $command->call('migrate', array('--force' => true));
        } else {
            $command->call('migrate');
        }
    }

    /**
     * Handle a command.runseeding event.
     *
     * @param  \Illuminate\Console\Command  $command
     * @return void
     */
    public function onRunSeeding(Command $command)
    {
        if ($this->force) {
            $command->call('db:seed', array('--force' => true));
        } else {
            $command->call('db:seed');
        }
    }

    /**
     * Handle a command.updatecache event.
     *
     * @param  \Illuminate\Console\Command  $command
     * @return void
     */
    public function onUpdateCache(Command $command)
    {
        $command->line('Clearing cache...');
        $command->call('cache:clear');
        $command->info('Cache cleared!');
    }

    /**
     * Handle a command.genassets event.
     *
     * @param  \Illuminate\Console\Command  $command
     * @return void
     */
    public function onGenAssets(Command $command)
    {
        if (class_exists('Barryvdh\Debugbar\Console\PublishCommand')) {
            $command->line('Publishing debugbar assets...');
            $command->call('debugbar:publish');
            $command->info('Debugbar assets published!');
        }

        if (class_exists('Lightgear\Asset\Commands\Generate')) {
            $command->line('Building assets...');
            $command->call('asset:generate');
            $command->info('Assets built!');
        }
    }

    /**
     * Get the config instance.
     *
     * @return \Illuminate\Config\Repository
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Get the crypt instance.
     *
     * @return \Illuminate\Encryption\Encrypter
     */
    public function getCrypt()
    {
        return $this->crypt;
    }
}
