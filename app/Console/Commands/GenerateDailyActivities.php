<?php

namespace App\Console\Commands;

use App\Models\Activity;
use App\Models\DailyActivityEntry;
use Illuminate\Console\Command;

class GenerateDailyActivities extends Command
{
    protected $signature = 'activities:generate-daily';
    protected $description = "Create today's daily_activity_entries for every active activity (safe to re-run).";

    public function handle(): void
    {
        $today = now()->toDateString();

        Activity::where('is_active', true)->each(function (Activity $activity) use ($today) {
            DailyActivityEntry::firstOrCreate(
                ['activity_id' => $activity->id, 'date' => $today],
                ['status' => 'pending']
            );
        });

        $this->info("Daily activities ensured for {$today}.");
    }
}