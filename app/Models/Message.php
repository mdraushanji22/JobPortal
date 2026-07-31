<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'conversation_id', 'sender_id', 'receiver_id', 'message',
        'message_type', 'file_path', 'file_type',
        'original_file_name', 'file_size', 'mime_type',
        'is_read', 'read_at',
    ];

    protected $touches = ['conversation'];

    protected $appends = ['attachment_url', 'download_url', 'formatted_file_size', 'is_image'];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'read_at' => 'datetime',
        ];
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function getAttachmentUrlAttribute()
    {
        if (!$this->file_path) {
            return null;
        }

        return route('messages.attachment', $this);
    }

    public function getDownloadUrlAttribute()
    {
        if (!$this->file_path) {
            return null;
        }

        return route('messages.download', $this);
    }

    public function getIsImageAttribute(): bool
    {
        if ($this->message_type === 'image') {
            return true;
        }

        return $this->mime_type && str_starts_with($this->mime_type, 'image/');
    }

    public function getFormattedFileSizeAttribute(): ?string
    {
        $size = $this->file_size;
        if (!$size) {
            return null;
        }

        if ($size >= 1048576) {
            return number_format($size / 1048576, 1) . ' MB';
        }

        if ($size >= 1024) {
            return number_format($size / 1024, 1) . ' KB';
        }

        return $size . ' B';
    }
}
