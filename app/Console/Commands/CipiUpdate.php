<?php

namespace App\Console\Commands;

use App\Models\Server;
use phpseclib3\Net\SSH2;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CipiUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cipi:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Cipi';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $server = Server::where('default', 1)->first();
        
        $ssh = new SSH2($server->ip, 22);
        $ssh->login('cipi', $server->password);
        $ssh->setTimeout(360);
        $ssh->exec('echo '.$server->password.' | sudo -s cd /var/www/html/utility/cipi-update && sh run.sh');
        $ssh->exec('exit');

        return 0;
    }
}
