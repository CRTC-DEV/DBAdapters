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


                try {
                    // Gọi hàm xử lý XML
                    $this->processXml($file);
                    // Di chuyển file vào thư mục PROCESSED
                    //File::move($file, $this->processedDir . '/' . $file->getFilename());
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

        // Extract FlightType and OperationType
        $flightType = (string) $xml->xpath('//FlightType')[0];
        $operationType = (string) $xml->xpath('//OperationType')[0];

        // Determine the appropriate model and columns based on FlightType
        $model = ($flightType == 'A') ? new ArrivalMovement() : new DepartureMovement();
        $columns = $model->getFillable();

        // Find FlightMovement nodes
        $flightMovements = $xml->xpath('//FlightMovement');

        foreach ($flightMovements as $movement) {
            // Extract data for defined columns
            $dataToInsert = [];
            foreach ($columns as $column) {
                if (isset($movement->{$column})) {
                    $dataToInsert[$column] = (string) $movement->{$column};
                }
            }

            // Determine the operation method and execute
            try {
                if ($operationType == 'UPDATE') {
                    $model->updateMovement($dataToInsert, $movement->MovementId);
                    $info = 'Update FlightMovement successfully';
                } elseif ($operationType == 'INSERT') {
                    $info =  $model->insertMovement($dataToInsert);
                }
                $this->info($info . '=' . $movement->MovementId.$flightType);
            } catch (Exception $e) {
                $this->error("Error processing Movement: " . $movement->MovementId . " | Error: " . $e->getMessage());
            }
        }
    }
}
