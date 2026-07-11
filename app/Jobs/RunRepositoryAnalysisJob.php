<?php

namespace App\Jobs;

use App\Models\Analysis;
use App\Models\User;
use App\Services\Analysis\RepositoryAnalysisService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RunRepositoryAnalysisJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Comfortably exceeds the OpenAI HTTP client's own 180s timeout so a
     * slow-but-successful call isn't killed by the queue worker first.
     */
    public int $timeout = 240;

    public int $tries = 1;

    public function __construct(
        public readonly Analysis $analysis,
        public readonly User $triggeringUser,
    ) {}

    public function handle(RepositoryAnalysisService $analysisService): void
    {
        $analysisService->process($this->analysis, $this->triggeringUser);
    }

    /**
     * Safety net for hard failures process() can't catch itself, such as
     * the worker killing the job after $timeout — otherwise the Analysis
     * row would stay stuck on "running" forever.
     */
    public function failed(\Throwable $e): void
    {
        $this->analysis->update([
            'status' => 'failed',
            'failure_reason' => $e->getMessage(),
            'completed_at' => now(),
        ]);
    }
}
