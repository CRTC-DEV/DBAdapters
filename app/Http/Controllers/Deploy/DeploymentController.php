<?php

// app/Http/Controllers/DeployController.php

namespace App\Http\Controllers\Deploy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class DeploymentController extends Controller
{
    public function __invoke(Request $request)
    {
        $secret = env('DEPLOY_WEBHOOK_SECRET');
        $signature = $request->header('X-Hub-Signature-256');

        if (!$signature) {
            Log::warning('Deploy failed: No signature');
            return response('Forbidden', 403);
        }

        $payload = $request->getContent();
        $hash = 'sha256=' . hash_hmac('sha256', $payload, $secret);

        if (!hash_equals($hash, $signature)) {
            Log::warning('Deploy failed: Invalid signature');
            return response('Invalid signature', 403);
        }

        // ✅ Gọi lệnh shell để pull code và migrate
        $commands = [
            'git pull origin deploy',
            // 'composer install --no-dev --optimize-autoloader',
            // 'php artisan migrate --force',
            // 'php artisan config:clear',
            // 'php artisan config:cache',
        ];

        $output = [];

        foreach ($commands as $cmd) {
            $process = Process::fromShellCommandline($cmd, base_path());
            $process->run();
            $output[] = "> $cmd";
            $output[] = $process->getOutput();
        }

        Log::info("✅ Deploy triggered:\n" . implode("\n", $output));
        return response("✅ Deploy success", 200);
    }
}