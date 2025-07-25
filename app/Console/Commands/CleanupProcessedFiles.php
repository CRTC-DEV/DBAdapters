<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class CleanupProcessedFiles extends Command
{
    protected $signature = 'xml:cleanup-processed';
    protected $description = 'Delete files in the PROCESSED folder older than 30 days';

    private $processedDir;

    public function __construct()
    {
        parent::__construct();       
        $this->processedDir = storage_path('public/XML/PROCESSED');

    }

    public function handle()
    {
        // Set timezone to GMT+7
        $timezone = new \DateTimeZone('Asia/Bangkok');
        // Get current time in GMT+7
        $now = Carbon::now($timezone);
        // Lấy danh sách các file trong thư mục PROCESSED
        $files = File::files($this->processedDir);

        foreach ($files as $file) {
            $lastModified = Carbon::createFromTimestamp($file->getMTime(), $timezone);

            // Kiểm tra nếu file đã tồn tại hơn 30 ngày
            //if ($lastModified->lt($now->subDays(1))) {
                File::delete($file);
                $this->info("Deleted file: " . $file->getFilename());
            //}
        }
        $this->info("Cleanup process completed.");
    }
}
