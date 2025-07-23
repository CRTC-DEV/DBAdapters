<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DeploymentController extends Controller
{
    public function __invoke(Request $request)
    {
        $secret = env('DEPLOY_WEBHOOK_SECRET');
        $signature = $request->header('X-Hub-Signature-256');

        if (!$signature) {
            Log::warning('🚫 Deploy failed: No signature');
            return response('Forbidden', 403);
        }

        $payload = $request->getContent();
        $hash = 'sha256=' . hash_hmac('sha256', $payload, $secret);

        if (!hash_equals($hash, $signature)) {
            Log::warning('🚫 Deploy failed: Invalid signature');
            return response('Invalid signature', 403);
        }

        $commands = [
            'git pull origin dbadapters_deploy',
            // 'composer install --no-dev --optimize-autoloader',
            // 'php artisan migrate --force',
            // 'php artisan config:clear',
            // 'php artisan config:cache',
        ];

        $logOutput = [];

        // 👤 Check current user
        exec('whoami', $user);
        $logOutput[] = '👤 Running as user: ' . implode(', ', $user);

        foreach ($commands as $cmd) {
            $logOutput[] = "▶ Running: $cmd";

            try {
                $process = Process::fromShellCommandline($cmd, base_path());
                $process->run();

                if (!$process->isSuccessful()) {
                    $logOutput[] = "❌ Command failed: $cmd";
                    $logOutput[] = "⚠️ STDERR: " . $process->getErrorOutput();
                    $logOutput[] = "ℹ️ STDOUT: " . $process->getOutput();
                } else {
                    $logOutput[] = "✅ Success: $cmd";
                    $logOutput[] = "📦 Output:\n" . $process->getOutput();
                }
            } catch (ProcessFailedException $e) {
                $logOutput[] = "🔥 Exception when running: $cmd";
                $logOutput[] = $e->getMessage();
            }
        }

        Log::info("🚀 Deploy triggered:\n" . implode("\n", $logOutput));

        return response("✅ Deploy success", 200);
    }
}
