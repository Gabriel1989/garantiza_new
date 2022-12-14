<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(TipoDocumentoSeeder::class);
        $this->call(EstadoSeeder::class);
        $this->call(TipoVehiculoSeeder::class);
        $this->call(TipoTramiteSeeder::class);
        $this->call(ComunaSeeder::class);
        $this->call(TipoCarroceriaSeeder::class);
    }
}
