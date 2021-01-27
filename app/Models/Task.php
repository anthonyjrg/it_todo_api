<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'task_description',
        'resolution_description',
        'due_date',
        'completed_date',
        'created_by',
        'assigned_to',
        'completed_by',
        'location'
    ];

    public function user(){
        return $this->belongsToMany(Task::class, 'tasks_users');
    }
}
