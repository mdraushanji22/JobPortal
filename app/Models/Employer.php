<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employer extends Model
{
    protected $fillable = [
        'user_id', 'company_name', 'industry', 'website',
        'phone', 'address', 'description', 'logo',
        'is_verified', 'is_suspended',
    ];

    protected function casts(): array
    {
        return [
            'is_verified' => 'boolean',
            'is_suspended' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobListings()
    {
        return $this->hasMany(JobListing::class);
    }

    public function interviews()
    {
        return $this->hasMany(Interview::class);
    }

    public function letters()
    {
        return $this->hasMany(Letter::class);
    }
}
