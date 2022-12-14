<?php

use App\Models\Tipo_Carroceria;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class TipoCarroceriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipo_carrocerias')->delete();

        $json = File::get("database/data/tipo_carrocerias.json");
        $data = json_decode($json);
        if (is_array($data) || is_object($data)){
            
            foreach ($data as $obj) {
                Tipo_Carroceria::create(array(
                    'name' => $obj->name,
                ));
            }
        }else{
            $this->command->getOutput()->writeln("Problemas con el objeto");
        }
    }
}
