<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Mint\Service\Repositories\InitRepository;
use App\Repositories\Configuration\ConfigurationRepository;

class Administrator
{
    protected $config;
    protected $repo;

    public function __construct(
        ConfigurationRepository $config,
        InitRepository $repo
    ) {
        $this->config = $config;
        $this->repo = $repo;
    }

    /**
     * Used to set default configuration
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $this->repo->init();

        $this->config->setDefault();

        return $next($request);
    }
}
