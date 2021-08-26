<?php

namespace App\Console\Commands;

use App\Models\Provider;
use Illuminate\Console\Command;

class createRSNProvider extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:rsn_provider';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generación de RSN como Provider';

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
     * @return mixed
     */
    public function handle(): void
    {
        Provider::firstOrCreate(
            ['marketplace_id' => env('RSN_ID_MERCADOPAGO')],
            [
                'provider_email' => 'rsn@mail.com',
                'identification_type' => 'DNI',
                'identification_number' => '12345678',
                'first_name' => 'rsn',
                'street_name' => 'rsn',
                'street_number' => 'rsn',
                'zip_code' => 'rsn',
            ]
        );
    }
}
