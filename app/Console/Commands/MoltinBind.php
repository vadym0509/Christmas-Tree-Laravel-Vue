<?php

namespace App\Console\Commands;

use App\Clients\Moltin;
use Illuminate\Console\Command;

class MoltinBind extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moltin:bind';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configures Moltin to be compatible with this application';

    /**
     * The Moltin client instance.
     *
     * @var \App\Clients\Moltin
     */
    protected $moltin;

    /**
     * Called when the class should load.
     *
     * @param \App\Clients\Moltin $moltin
     */
    public function __construct(Moltin $moltin)
    {
        parent::__construct();

        $this->moltin = $moltin;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->seedFlows();

        // TODO: Other seeders...
    }

    /**
     * Seeds the flows.
     *
     * @return void
     */
    protected function seedFlows()
    {
        // Fail if any flows exist. We'd prefer to start from scratch.
        if ( ! empty( $this->moltin->get('v2/flows'))) {
            return $this->error('There are already flows configured on this Moltin instance. Please start from a fresh Moltin instance.');
        }

        // The order extension flow 
        $flow = $this->moltin->post('v2/flows', [
            'json' => [
                'data' => [
                    'type' => 'flow',
                    'name' => 'Order Extensions',
                    'slug' => 'orders',
                    'description' => 'Extends the default orders object',
                    'enabled' => true,
                ]
            ]
        ]); 

        // Add fields to flow...
    }
}
