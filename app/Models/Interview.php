<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    protected $fillable = [
        'application_id', 'employer_id', 'candidate_id',
        'scheduled_date', 'scheduled_time', 'interview_type',
        'meeting_link', 'notes', 'status',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_date' => 'date',
            'scheduled_time' => 'datetime:H:i',
        ];
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function employer()
    {
        return $this->belongsTo(Employer::class);
    }

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}
