<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = [
        'application_id',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    public function candidateUser()
    {
        return $this->application->candidate->user;
    }

    public function employerUser()
    {
        return $this->application->jobListing->employer->user;
    }

    public function jobTitle()
    {
        return $this->application->jobListing->title;
    }

    public function isParticipant(User $user): bool
    {
        $application = $this->application()->with(['candidate', 'jobListing.employer'])->first();

        return $user->isEmployer()
            && $application->jobListing->employer_id === $user->employer->id
            || $user->isCandidate()
            && $application->candidate_id === $user->candidate->id;
    }

    public function otherUserFor(User $user): ?User
    {
        $application = $this->application()->with(['candidate.user', 'jobListing.employer.user'])->first();

        if ($user->isEmployer()) {
            return $application->candidate->user;
        }

        if ($user->isCandidate()) {
            return $application->jobListing->employer->user;
        }

        return null;
    }
}
