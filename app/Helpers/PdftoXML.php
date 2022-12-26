<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Gufy\PdfToHtml\Pdf;
use PHPHtmlParser\Dom;
use DateTime;

class PdftoXML
{

    public function init($request)
    {

        $pdf = new Pdf($request->file('Factura_XML'));
        $html = $pdf->html();
        $dom = new Dom;
        $total_pages = $pdf->getPages();

        if ($total_pages == 1) {
            $html->goToPage(1);
            $dom->load($html);
            $paragraphs = $dom->find('p,b');
            $paragraphs = collect($paragraphs);
            
            foreach ($paragraphs as $p) {
                $datestring = preg_replace('/\xc2\xa0/', ' ', trim($p->text));
                echo $datestring;
            }
        }
        else{
            for ($i = 1; $i <= $total_pages; $i++){
                $html->goToPage($i);
                $dom->load($html);
                $paragraphs = $dom->find('p,b');
                $paragraphs = collect($paragraphs);
                
                foreach ($paragraphs as $p) {
                    $datestring = preg_replace('/\xc2\xa0/', ' ', trim($p->text));
                    echo $datestring;
                    echo "<br>";
                }
            }
        }
    }
    /*
    public function init($filename)
    {
        // Cloud API asynchronous "PDF To XML" job example.
        // Allows to avoid timeout errors when processing huge or scanned PDF documents.

        // The authentication key (API Key).
        // Get your own by registering at https://app.pdf.co
        $apiKey = "gabrielsegura974@gmail.com_a7e0a8bc140076acbafcaa4201f6654db91be41f60d2c44d6c76bca93fa5374170c6a9d5";

        //dd($_SERVER);
        // Direct URL of source PDF file. Check another example if you need to upload a local file to the cloud.
        // You can also upload your own file into PDF.co and use it as url. Check "Upload File" samples for code snippets: https://github.com/bytescout/pdf-co-api-samples/tree/master/File%20Upload/    
        $sourceFileUrl = $_SERVER["HTTP_ORIGIN"]."/storage/".str_replace('public/','',$filename);
        // Comma-separated list of page indices (or ranges) to process. Leave empty for all pages. Example: '0,2-5,7-'.
        $pages = "";
        // PDF document password. Leave empty for unprotected documents.
        $password = "";

        //dd($sourceFileUrl);

        // Prepare URL for `PDF To XML` API call
        $url = "http://api.pdf.co/v1/pdf/convert/to/xml";

        // Prepare requests params
        $parameters = array();
        $parameters["url"] = $sourceFileUrl;
        $parameters["password"] = $password;
        $parameters["pages"] = $pages;
        $parameters["async"] = true; // (!) Make asynchronous job

        // Create Json payload
        $data = json_encode($parameters);
        

        // Create request
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("x-api-key: " . $apiKey, "Content-type: application/json"));
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        // Execute request
        $result = curl_exec($curl);

        if (curl_errno($curl) == 0) {
            $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            //dd($result);
            if ($status_code == 200) {
                $json = json_decode($result, true);

                if (!isset($json["error"]) || $json["error"] == false) {
                    // URL of generated XML file that will available after the job completion
                    $resultFileUrl = $json["url"];
                    // Asynchronous job ID
                    $jobId = $json["jobId"];

                    // Check the job status in a loop
                    do {
                        $status = $this->CheckJobStatus($jobId, $apiKey); // Possible statuses: "working", "failed", "aborted", "success".

                        // Display timestamp and status (for demo purposes)
                        echo "<p>" . date(DATE_RFC2822) . ": " . $status . "</p>";

                        if ($status == "success") {
                            // Display link to the file with conversion results
                            echo "<div><h2>Conversion Result:</h2><a href='" . $resultFileUrl . "' target='_blank'>" . $resultFileUrl . "</a></div>";
                            break;
                        } else if ($status == "working") {
                            // Pause for a few seconds
                            sleep(3);
                        } else {
                            echo $status . "<br/>";
                            break;
                        }
                    }
                    while (true);
                } else {
                    // Display service reported error
                    echo "<p>Error: " . $json["message"] . "</p>";
                }
            } else {
                // Display request error
                echo "<p>Status code 1: " . $status_code . "</p>";
                echo "<p>" . $result . "</p>";
            }
        } else {
            // Display CURL error
            echo "Error: " . curl_error($curl);
        }

        // Cleanup
        curl_close($curl);
    }

    private function CheckJobStatus($jobId, $apiKey)
    {
        $status = null;
        
        // Create URL
        $url = "http://api.pdf.co/v1/job/check";
        
        // Prepare requests params
        $parameters = array();
        $parameters["jobid"] = $jobId;
    
        // Create Json payload
        $data = json_encode($parameters);
    
        // Create request
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("x-api-key: " . $apiKey, "Content-type: application/json"));
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        
        // Execute request
        $result = curl_exec($curl);
        
        if (curl_errno($curl) == 0)
        {
            $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            
            if ($status_code == 200)
            {
                $json = json_decode($result, true);
            
                if (!isset($json["error"]) || $json["error"] == false)
                {
                    $status = $json["status"];
                }
                else
                {
                    // Display service reported error
                    echo "<p>Error: " . $json["message"] . "</p>"; 
                }
            }
            else
            {
                // Display request error
                echo "<p>Status code 2: " . $status_code . "</p>"; 
                echo "<p>" . $result . "</p>"; 
            }
        }
        else
        {
            // Display CURL error
            echo "Error: " . curl_error($curl);
        }
        
        // Cleanup
        curl_close($curl);
        
        return $status;
    }


    */

    /*
    public function init($filename){

        // Note: If you have input files large than 200kb we highly recommend to check "async" mode example.

        // Get submitted form data
        $apiKey = "gabrielsegura974@gmail.com_c9ce375e28a7945631420a1dcbc2e00c43f3c9d5869b285a3580cbc470f3edaa25f93027"; // The authentication key (API Key). Get your own by registering at https://app.pdf.co
        $pages = '';


        // 1. RETRIEVE THE PRESIGNED URL TO UPLOAD THE FILE.
        // * If you already have the direct PDF file link, go to the step 3.

        // Create URL
        $url = "http://api.pdf.co/v1/file/upload/get-presigned-url" . 
            "?name=" . urlencode($_FILES["Factura_XML"]["name"]) .
            "&contenttype=application/octet-stream";
            
        // Create request
        //dd($url);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("x-api-key: " . $apiKey,"Connection: keep-alive"));
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // Execute request
        $result = curl_exec($curl);

        if (curl_errno($curl) == 0)
        {
            $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            
            if ($status_code == 200)
            {
                $json = json_decode($result, true);
                
                // Get URL to use for the file upload
                $uploadFileUrl = $json["presignedUrl"];
                // Get URL of uploaded file to use with later API calls
                $uploadedFileUrl = $json["url"];
                
                // 2. UPLOAD THE FILE TO CLOUD.
                
                $localFile = $_FILES["Factura_XML"]["tmp_name"];
                $fileHandle = fopen($localFile, "r");
                
                curl_setopt($curl, CURLOPT_URL, $uploadFileUrl);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array("content-type: application/octet-stream"));
                curl_setopt($curl, CURLOPT_PUT, true);
                curl_setopt($curl, CURLOPT_INFILE, $fileHandle);
                curl_setopt($curl, CURLOPT_INFILESIZE, filesize($localFile));

                // Execute request
                curl_exec($curl);
                
                fclose($fileHandle);
                
                if (curl_errno($curl) == 0)
                {
                    $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                    
                    if ($status_code == 200)
                    {
                        // 3. CONVERT UPLOADED PDF FILE TO XML
                        
                        $this->ExtractXML($apiKey, $uploadedFileUrl, $pages);
                    }
                    else
                    {
                        // Display request error
                        echo "<p>Status code: " . $status_code . "</p>"; 
                        echo "<p>" . $result . "</p>"; 
                    }
                }
                else
                {
                    // Display CURL error
                    echo "Error: " . curl_error($curl);
                }
            }
            else
            {
                // Display service reported error
                echo "<p>Status code: " . $status_code . "</p>"; 
                echo "<p>" . $result . "</p>"; 
            }
            
            curl_close($curl);
        }
        else
        {
            // Display CURL error
            echo "Error: " . curl_error($curl);
        }
    }

    private function ExtractXML($apiKey, $uploadedFileUrl, $pages) 
    {
        // Create URL
        $url = "http://api.pdf.co/v1/pdf/convert/to/xml";
        
        // Prepare requests params
        $parameters = array();
        $parameters["url"] = $uploadedFileUrl;
        $parameters["pages"] = $pages;

        // Create Json payload
        $data = json_encode($parameters);

        // Create request
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("x-api-key: " . $apiKey, "Content-type: application/json"));
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        // Execute request
        $result = curl_exec($curl);
        
        if (curl_errno($curl) == 0)
        {
            $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            
            if ($status_code == 200)
            {
                $json = json_decode($result, true);
                
                if (!isset($json["error"]) || $json["error"] == false)
                {
                    $resultFileUrl = $json["url"];
                    
                    // Display link to the file with conversion results
                    echo "<div><h2>Conversion Result:</h2><a href='" . $resultFileUrl . "' target='_blank'>" . $resultFileUrl . "</a></div>";
                }
                else
                {
                    // Display service reported error
                    echo "<p>Error: " . $json["message"] . "</p>"; 
                }
            }
            else
            {
                // Display request error
                echo "<p>Status code: " . $status_code . "</p>"; 
                echo "<p>" . $result . "</p>"; 
            }
        }
        else
        {
            // Display CURL error
            echo "Error: " . curl_error($curl);
        }
        
        // Cleanup
        curl_close($curl);
    }*/
}

?>