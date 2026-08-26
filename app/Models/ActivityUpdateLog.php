<?php

class ActivityUpdateLog extends Model
{
    const UPDATED_AT = null; // tells Eloquent this table has no updated_at column

    protected $fillable = ['daily_activity_entry_id', 'updated_by', 'old_status', 'new_status', 'remark', 'actual_value'];

    public function entry()
    {
        return $this->belongsTo(DailyActivityEntry::class, 'daily_activity_entry_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}