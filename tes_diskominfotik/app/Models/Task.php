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
        'deadline',
        'status',
        'labels',
    ];
    protected $casts = [
        'label' => 'array',
    ];

    public function setLabelAttribute($value)
    {
        $this->attributes['label'] = json_encode($value);
    }

    public function getLabelAttribute($value)
    {
        return json_decode($value, true);
    }
    // Relasi untuk mendapatkan pengguna yang memiliki akses ke task
    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('permission')->withTimestamps();
    }
}
