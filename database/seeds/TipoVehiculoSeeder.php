<?php

use App\Models\Tipo_Vehiculo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class TipoVehiculoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipo_vehiculos')->delete();

        $json = File::get("database/data/tipo_vehiculos.json");
        $data = json_decode($json);
        if (is_array($data) || is_object($data)){
            
            foreach ($data as $obj) {
                Tipo_Vehiculo::create(array(
                    'name' => $obj->name,
                    'tipo' => $obj->tipo,
                ));
            }
        }else{
            $this->command->getOutput()->writeln("Problemas con el objeto");
        }
        
    }
}
