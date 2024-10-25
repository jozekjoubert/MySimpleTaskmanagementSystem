<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    // use HasFactory;

    protected $fillable = ['user_id', 'title', 'description', 'due_date', 'status'];

    // Relationship to activity logs
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }
    // protected $fillable = ['title', 'description', 'due_date', 'status', 'user_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    // public function activityLogs() {
    //     return $this->hasMany(ActivityLog::class);
    // }
    
}
