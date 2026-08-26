<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DailyActivityEntry extends Model
{
    protected $fillable = ['activity_id', 'date', 'status', 'expected_value', 'actual_value', 'variance', 'assigned_to'];
    protected $casts = ['date' => 'date'];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function logs()
    {
        return $this->hasMany(ActivityUpdateLog::class, 'daily_activity_entry_id')->latest('created_at');
    }
}