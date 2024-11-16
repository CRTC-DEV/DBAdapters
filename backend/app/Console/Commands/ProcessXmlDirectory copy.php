<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Saloon\XmlWrangler\XmlReader;
use App\Models\Flight;
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

        $this->directory = storage_path('public\XML\IN');
        $this->processedDir = storage_path('public\XML\PROCESSED');
        $this->errorDir = storage_path('public\XML\ERROR');

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
                $reader = XmlReader::fromFile($file);
                //$xmlContent = file_get_contents($file);
                //dd($xmlContent);

                try {
                    // Gọi hàm xử lý XML
                    $this->processXml($reader);
                    // Di chuyển file vào thư mục PROCESSED
                    // File::move($file, $this->processedDir . '/' . $file->getFilename());
                    $this->info('Successfully processed file: ' . $file->getFilename());
                } catch (\Exception $e) {
                    // Di chuyển file vào thư mục ERROR nếu có lỗi
                    // File::move($file, $this->errorDir . '/' . $file->getFilename());
                    $this->error('Failed to process file: ' . $file->getFilename() . ' | Error: ' . $e->getMessage());
                }
            }
        }
    }

    private function processXml($reader)
    {
        // Sử dụng XmlWrangler để phân tích cú pháp XML
        //$xml = XmlReader::fromString($xmlContent);
        //$reader = XmlReader::fromFile('path/to/file.xml');
        //$elements = $xml->elements();
        // Truy cập vào các phần tử cần thiết trong XML
        // $arrivalMovements = $xml->find('ArrivalMovements.ArrivalMovement');
        // $elements = $reader->elements(); // Array of `Element::class` DTOs
        $values = $reader->values(); // Array of values.
        return response()->json($values);
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
}
