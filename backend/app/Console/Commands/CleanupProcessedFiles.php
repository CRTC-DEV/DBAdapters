<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class CleanupProcessedFiles extends Command
{
    protected $signature = 'files:cleanup-processed';
    protected $description = 'Delete files in the PROCESSED folder older than 30 days';

    private $processedDir;

    public function __construct()
    {
        parent::__construct();
        $this->processedDir = storage_path('public\DBAdapters\PROCESSED');
    }

    public function handle()
    {
        // Lấy danh sách các file trong thư mục PROCESSED
        $files = File::files($this->processedDir);

        foreach ($files as $file) {
            $lastModified = Carbon::createFromTimestamp($file->getMTime());

            // Kiểm tra nếu file đã tồn tại hơn 30 ngày
            if ($lastModified->lt(now()->subDays(1))) {
                File::delete($file);
                $this->info("Deleted file: " . $file->getFilename());
            }
        }
    }
}
