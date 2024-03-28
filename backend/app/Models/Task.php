<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'title',
        'description',
        'status',
        'assigned_to',
        'task_id'
    ];

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function comments()
    {
        return $this->hasMany(TaskComment::class);
    }

    public function attachments()
    {
        return $this->hasMany(TaskAttachment::class);
    }

    public function canAttachFileToTask($task)
    {
        $user = Auth::user();

        return $user->id === $task->assigned_to || 
               $user->id === $task->attached_by ||
               $user->isSuperAdmin();
    }
}
