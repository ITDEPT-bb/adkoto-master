<?php

namespace App\Jobs;

use App\Events\CallTimeEnded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ScheduleCallEnd implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $channel;

    public function __construct($channel)
    {
        $this->channel = $channel;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        broadcast(new CallTimeEnded($this->channel));
    }
}
