<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobSkill extends Model
{
    protected $fillable = ['job_listing_id', 'skill_id'];

    protected $table = 'job_skills';
}
