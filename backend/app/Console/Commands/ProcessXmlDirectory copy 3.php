<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Saloon\XmlWrangler\XmlReader;
use SimpleXMLElement;
use App\Models\ArrivalMovement;
use App\Models\DepartureMovement;
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
                    File::move($file, $this->processedDir . '/' . $file->getFilename());
                    //$this->info('Successfully processed file: ' . $file->getFilename());
                } catch (\Exception $e) {
                    // Di chuyển file vào thư mục ERROR nếu có lỗi
                    // File::move($file, $this->errorDir . '/' . $file->getFilename());
                    $this->error('Failed to process file: ' . $file->getFilename() . ' | Error: ' . $e->getMessage());
                }
            }
        }
    }



    private function processXml($filePath)
    {
        // Load XML file using SimpleXML
        $xml = simplexml_load_file($filePath);
        //Check FlightType
        $flightType = $xml->xpath('//FlightType')[0]; //get string from array
        //Check OperationType
        $operationType = $xml->xpath('//OperationType')[0]; //get string from array


        if ($flightType == 'A') {

            $ArrivalMovement = new ArrivalMovement();
            $columns = $ArrivalMovement->getFillable();
        } else {
            $DepartureMovement = new DepartureMovement();
            $columns = $DepartureMovement->getFillable();
        }

        // Find the ArrivalMovement nodes         
        $flightMovements = $xml->xpath('//FlightMovement');
        //var_dump($flightMovements);
        //dd($columns);
        //$this->info('Test: ' );
        //var_dump('t='.$flightMovements);
        foreach ($flightMovements as $movement) {
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
            try {
                if ($flightType == 'A' && $operationType == 'UPDATE') {
                    // Lưu dữ liệu vào database
                    // $ArrivalMovement = new ArrivalMovement();
                    $ArrivalMovement->updateArrivalMovement($dataToInsert, $movement->MovementId);
                    $this->info('Update Arrival Movement successfully =' . $movement->MovementId);
                } else {
                    if ($flightType == 'A' && $operationType == 'INSERT') {
                        // $ArrivalMovement = new ArrivalMovement();
                        $ArrivalMovement->insertArrivalMovement($dataToInsert);
                        $this->info('Insert Arrival Movement successfully=' . $movement->MovementId);
                    }
                }

                if ($flightType == 'D' && $operationType == 'UPDATE') {
                    // Lưu dữ liệu vào database
                    // $DepartureMovement = new DepartureMovement();
                    $DepartureMovement->updateDepartureMovement($dataToInsert, $movement->MovementId);
                    $this->info('Update Departure Movement successfully');
                } else {
                    if ($flightType == 'D' && $operationType == 'INSERT') {
                        // $DepartureMovement = new DepartureMovement();
                        $DepartureMovement->insertDepartureMovement($dataToInsert);
                        $this->info('Insert Departure Movement successfully=' . $movement->MovementId);
                    }
                }

            } catch (Exception $e) {
                $this->error('Error processing Movement: ' . $movement->MovementId . ' | Error: ' . $e->getMessage());
                // Log error here if needed
            }
        }
    }
}
