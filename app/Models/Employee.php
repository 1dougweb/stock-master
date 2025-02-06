<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'position',
        'department',
        'hire_date',
        'document_id',
        'phone',
        'address',
        'emergency_contact',
        'notes',
    ];

    protected $casts = [
        'hire_date' => 'date',
    ];

    protected $appends = ['name'];
    protected $with = ['user'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function materialRequests()
    {
        return $this->hasMany(MaterialRequest::class, 'user_id', 'user_id');
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class, 'user_id', 'user_id');
    }

    public function getNameAttribute()
    {
        return $this->user->name ?? '';
    }

    public function scopeOrderByName($query)
    {
        return $query->join('users', 'employees.user_id', '=', 'users.id')
                    ->orderBy('users.name')
                    ->select('employees.*');
    }
}
