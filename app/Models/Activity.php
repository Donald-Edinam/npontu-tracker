<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = ['name', 'description', 'type', 'category', 'is_active', 'created_by'];
    protected $casts = ['is_active' => 'boolean'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function dailyEntries()
    {
        return $this->hasMany(DailyActivityEntry::class);
    }
}