<?php

namespace App\Console\Commands;

use App\Models\User\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AdminTestRegister extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:makeAdmin {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creación de Admin Test';

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
    public function handle()
    {
        try {
            User::create([
                'name' => 'Admin Prueba',
                'email' => $this->argument('email'),
                'userType' => 'Administrador',
                'active' => 1
            ]);
            $this->info("El usuario con el mail {$this->argument('email')} se ha creado con éxito!");
        } catch (\Exception $exception) {
            Log::error('[' . __class__ . '] ' . $exception->getMessage());
            $this->error("Error al crear el usuario {$this->argument('email')}");
        }

    }
}
