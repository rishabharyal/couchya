<?php

namespace App\Console\Commands;

use App\Models\Movie;
use App\Services\CacheService;
use App\Services\MovieService;
use Illuminate\Console\Command;
use Prophecy\Argument;

class CacheMoviesFromApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    private $service;
    protected $signature = 'movies:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pulls movies of a genre from the api & stores it to the database.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CacheService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->service->handle();
    }
}
