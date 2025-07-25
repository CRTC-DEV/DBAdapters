<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class CleanupAllFiles extends Command
{
    protected $signature = 'xml:cleanup-all';
    protected $description = 'Delete files in the PROCESSED folder older than 30 days';

    private $processedDir;

    public function __construct()
    {
        parent::__construct();       
        $this->processedDir = storage_path('public/XML/PROCESSED');

    }

    public function handle()
    {
       
        // Lấy danh sách các file trong thư mục PROCESSED
        $files = File::files($this->processedDir);

        foreach ($files as $file) {          
           
                File::delete($file);
                $this->info("Deleted file: " . $file->getFilename());
            
        }
        $this->info("Cleanup process completed.");
    }
}
