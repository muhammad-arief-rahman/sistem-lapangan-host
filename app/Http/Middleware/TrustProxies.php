<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Symfony\Component\HttpFoundation\Response;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array|string|null
     */
    protected $proxies; // We will set this dynamically or broadly

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers = Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO; // Ensure these are all detected

    // Add this constructor
    public function __construct()
    {
        // Option A: Trust all proxies (easiest for Ngrok Free Tier, but less secure)
        $this->proxies = '*';

        // Option B: Trust specific IPs (more secure, if Ngrok's IPs were static or known)
        // Since ngrok's free tier IPs are dynamic, '*' is often the pragmatic choice.
        // If ngrok were running on the same VPS, you could use:
        // $this->proxies = ['127.0.0.1', '::1'];
    }
}
