<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeSwaggerController extends Command
{
    // Định nghĩa tên và mô tả cho command
    protected $signature = 'make:swagger-controller {name}';
    protected $description = 'Create a new controller with Swagger annotations';

    public function handle()
    {
        $name = $this->argument('name'); // Lấy tên controller từ input
        $namespace = 'App\\Http\\Controllers\API'; // Namespace mặc định
        $path = app_path("Http/Controllers/API/{$name}.php"); // Đường dẫn file controller
        $stubPath = base_path('stubs/swagger-controller.stub'); // Đường dẫn file stub

        // Kiểm tra nếu controller đã tồn tại
        if (File::exists($path)) {
            $this->error("Controller {$name} already exists!");
            return;
        }

        // Kiểm tra nếu stub không tồn tại
        if (!File::exists($stubPath)) {
            $this->error("Stub file not found at {$stubPath}!");
            return;
        }

        // Đọc nội dung của stub
        $stub = File::get($stubPath);

        // Thay thế placeholder trong stub
        $content = str_replace(
            ['DummyNamespace', 'DummyClass', 'dummy-class'],
            [$namespace, $name, strtolower(str_replace('Controller', '', $name))],
            $stub
        );

        // Ghi file controller
        File::put($path, $content);

        $this->info("Controller {$name} created successfully with Swagger annotations!");
    }
}
