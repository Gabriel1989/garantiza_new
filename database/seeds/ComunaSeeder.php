<?php

use App\Models\Comuna;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ComunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('comunas')->delete();

        $json = File::get("database/data/comunas.json");
        $data = json_decode($json);
        if (is_array($data) || is_object($data)){
            
            foreach ($data as $obj) {
                Comuna::create(array(
                    'Codigo' => $obj->Codigo,
                    'Nombre' => $obj->Nombre,
                ));
            }
        }else{
            $this->command->getOutput()->writeln("Problemas con el objeto");
        }
    }
}
