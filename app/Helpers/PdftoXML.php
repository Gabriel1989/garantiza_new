<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Gufy\PdfToHtml\Pdf;
use PHPHtmlParser\Dom;
use DateTime;
use Smalot\PdfParser\Parser;

class PdftoXML
{

    public function initSmalot($request){
        $parser = new Parser();    
        $pdf = $parser->parseFile($request->file('Factura_XML'));
        $texto = $pdf->getText();
        echo $texto;
    }

    public function init($request)
    {
        
        $pdf = new Pdf($request->file('Factura_XML'));
        $html = $pdf->html();
        $dom = new Dom;
        $total_pages = $pdf->getPages();

        $html->goToPage(1);
        $dom->load($html);

        $paragraphs = $dom->find('p');
        $paragraphs = collect($paragraphs);
        
        $text_anterior = '';
        
        foreach ($paragraphs as $p) {
            $datestring = str_replace("&#160;",'',preg_replace('/\xc2\xa0/', ' ', trim($p->text(true))));

            //SALTAR ESPACIOS EN BLANCO
            if($datestring == ''){
                continue;
            }

            switch($text_anterior){
                case "Señor(es)" : case "Sucursal": case "Giro": case "Vendedor": case  "Dirección": case "Comuna": case "Teléfono" : case "R.U.T.":
                    if($text_anterior != "R.U.T."){
                    echo $text_anterior.$datestring;
                    }else{
                    echo "RUT Receptor ".$datestring;    
                    }
                break;

                case "FACTURA ELECTRÓNICA":
                    echo "FACTURA ".$datestring;
                break;

                default:

                    echo $this->notEcho($datestring);
                break;
            }

            $text_anterior = $datestring;

            echo "<br>";
        }
        //die;
    }

    private function notEcho($filter){

        $array = ["señor(es)","sucursal","giro","vendedor","dirección","comuna","teléfono","r.u.t."];

        if(!in_array(mb_strtolower($filter,'UTF-8'),$array)){
            echo $filter;
        }
        

    }

    public static function substring($str, $from = 0, $to = FALSE)
    {
        if ($to !== FALSE) {
            if ($from == $to || ($from <= 0 && $to < 0)) {
                return '';
            }

            if ($from > $to) {
                $from_copy = $from;
                $from = $to;
                $to = $from_copy;
            }
        }

        if ($from < 0) {
            $from = 0;
        }

        $substring = $to === FALSE ? substr($str, $from) : substr($str, $from, $to - $from);
        return ($substring === FALSE) ? '' : $substring;
    }
}

?>