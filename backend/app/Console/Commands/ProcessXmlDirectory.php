<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Saloon\XmlWrangler\XmlReader;
use SimpleXMLElement;
use App\Models\ArrivalMovement;
use Carbon\Carbon;
use Exception;

class ProcessXmlDirectory extends Command
{
    protected $signature = 'xml:process-directory';
    protected $description = 'Process all XML files in a directory every 5 seconds';

    private $directory;
    private $processedDir;
    private $errorDir;

    public function __construct()
    {
        parent::__construct();

        $this->directory = storage_path('public/XML/IN');
        $this->processedDir = storage_path('public/XML/PROCESSED');
        $this->errorDir = storage_path('public/XML/ERROR');

        $this->ensureDirectoriesExist();
    }

    // Đảm bảo thư mục tồn tại
    private function ensureDirectoriesExist()
    {
        foreach ([$this->processedDir, $this->errorDir] as $dir) {
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }
        }
    }

    public function handle()
    {
        //while (true) {
            $this->processFiles();
            //dd('ok');
            //sleep(5); // Chờ 5 giây trước khi kiểm tra lại
        //}
    }

    private function processFiles()
    {
        // Lấy tất cả các file XML trong thư mục
        $xmlFiles = File::files($this->directory);

        foreach ($xmlFiles as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) == 'xml') {
                //$reader = XmlReader::fromFile($file);
                //$xmlContent = file_get_contents($file);
                //$reader = XmlReader::fromString($xmlContent);
                //dd($xmlContent);

                try {
                    // Gọi hàm xử lý XML
                    $this->processXml($file);
                    // Di chuyển file vào thư mục PROCESSED
                    //File::move($file, $this->processedDir . '/' . $file->getFilename());
                    $this->info('Successfully processed file: ' . $file->getFilename());
                } catch (\Exception $e) {
                    // Di chuyển file vào thư mục ERROR nếu có lỗi
                    // File::move($file, $this->errorDir . '/' . $file->getFilename());
                    $this->error('Failed to process file: ' . $file->getFilename() . ' | Error: ' . $e->getMessage());
                }
            }
        }
    }

    private function processXml2($file)
    {
        // Sử dụng XmlWrangler để phân tích cú pháp XML
        $xml = XmlReader::fromFile($file);
        //$reader = XmlReader::fromFile('path/to/file.xml');
        //$elements = $xml->elements();
        // Truy cập vào các phần tử cần thiết trong XML
        // $arrivalMovements = $xml->find('ArrivalMovements.ArrivalMovement');
        $elements = $xml->elements(); // Array of `Element::class` DTOs
        
        $xmldata = $xml->values(); // Array of values.
        //$xmldata = json_e$reader->xpathValue
        //$names = $xml->element('ArrivalMovement')->lazy();
        //$names = $xml->value('ArrivalMovement')->collect();
        $names = $xml->value('ArrivalMovement')->collectLazy();
        //var_dump($names);
 
    foreach ($xmldata as $name) {
        var_dump( $name);
    }
        //$arrivalMovements =$xml->xpathValue('//ArrivalMovements/ArrivalMovement');
        //dd($arrivalMovements);
        //$json= response()->json($xmldata);
        //var_dump($xmldata); 
        // Lấy danh sách các cột trong model ArrivalMovement
        $columns = (new ArrivalMovement())->getFillable();
        //dd($columns);
        // Lấy các giá trị từ XML tương ứng với các cột trong model
        $dataToInsert = collect($xmldata)->only($columns)->toArray();
        //dd($dataToInsert);

        //dd($values);
        return response()->json($xmldata);
        // foreach ($arrivalMovements as $movement) {
            // $flightId = $movement->get('FlightId');
            // $statusArr = $movement->get('StatusArr');

            // Lưu dữ liệu vào database
            // Flight::create([
                // 'flight_id' => $flightId,
                // 'status_arr' => $statusArr,
            // ]);
        //}
    }

    private function processXml($filePath)
    {
        // Load XML file using SimpleXML
        $xml = simplexml_load_file($filePath);
        //Check FlightType
        $flightType = $xml->xpath('//FlightType')[0];//get string from array
        //Check OperationType
        $operationType = $xml->xpath('//OperationType')[0];//get string from array
        
        

           // Find the ArrivalMovement nodes
            $arrivalMovements = $xml->xpath('//ArrivalMovement');
            $columns = (new ArrivalMovement())->getFillable();
            foreach ($arrivalMovements as $movement) {
            // Lấy tất cả các cột trong model trừ các cột cần bỏ qua        
            $fillableColumns = $columns;
            // Chỉ lấy dữ liệu từ XML tương ứng với các cột được định nghĩa trong bảng
            $dataToInsert = [];
                foreach ($fillableColumns as $column) {
                    if (isset($movement->{$column})) {
                    $dataToInsert[$column] = (string) $movement->{$column};
                }
                }
           //dd($movement->MovementId);
            //check flightType and operationType
            if($flightType == 'A' && $operationType == 'UPDATE'){
                // Lưu dữ liệu vào database
                $arrivalMovement = new ArrivalMovement();
                $arrivalMovement->updateArrivalMovement($dataToInsert, $movement->MovementId);
            }
            else{
                ArrivalMovement::create($dataToInsert);
            }
        }
    }

}
