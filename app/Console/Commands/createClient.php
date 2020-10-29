<?php

namespace App\Console\Commands;

use Hyn\Tenancy\Contracts\Repositories\HostnameRepository;
use Hyn\Tenancy\Models\Hostname;
use Illuminate\Console\Command;
use Hyn\Tenancy\Models\Website;
use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
use function GuzzleHttp\uri_template;

class createClient extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'client:create {subdomain} {name} {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new tenant client';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Base variables
        $url = config('app.url_base');
        $subdomain = $this->argument('subdomain');
        $name = $this->argument('name');
        $email = $this->argument('email');
        $fqdn = "{$subdomain}.{$url}";
        // Create a hostname
        $hostname = new Hostname();
        $hostname->fqdn = 'demo.demo.gr';
        $hostname = app(HostnameRepository::class)->create($hostname);

        // Create the website (create database and website)
        $website = new Website();
        app(WebsiteRepository::class)->create($website);

        // Attach Website to Hostname.
        app(HostnameRepository::class)->attach($hostname, $website);

    }

    private function tenantExists( $fqdn )
    {
        return Hostname::where('fqdn', $fqdn)->exists();
    }
}
