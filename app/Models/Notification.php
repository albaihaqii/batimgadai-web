<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'reference_type',
        'reference_id',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function markAsRead(): bool
    {
        if ($this->read_at) {
            return true;
        }

        return $this->update(['read_at' => now()]);
    }

    public static function kirimKeUser(int $userId, string $title, string $message, string $type = null, string $referenceType = null, int $referenceId = null)
    {
        if (!Schema::hasTable((new self())->getTable())) {
            logger()->warning('Notification table does not exist. Notification skipped.', compact('userId', 'title', 'message', 'type', 'referenceType', 'referenceId'));
            return false;
        }

        return self::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
        ]);
    }
}
