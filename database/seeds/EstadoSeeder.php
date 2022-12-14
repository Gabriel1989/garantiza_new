<?php

use App\Models\Estado;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class EstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('estados')->delete();

        $json = File::get("database/data/estados.json");
        $data = json_decode($json);
        if (is_array($data) || is_object($data)){
            
            foreach ($data as $obj) {
                Estado::create(array(
                    'name' => $obj->Nombre,
                ));
            }
        }else{
            $this->command->getOutput()->writeln("Problemas con el objeto");
        }

    }
}
