<?php

namespace App\Events;

use App\Models\Gadai;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GadaiCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Gadai $gadai;    

    public function __construct(Gadai $gadai)
    {
        $this->gadai = $gadai->load(['nasabah', 'branch']);
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('role.admin'),
            new PrivateChannel('role.superadmin'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'GadaiCreated';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->gadai->id,
            'no_sbg' => $this->gadai->no_sbg,
            'nasabah' => $this->gadai->nasabah?->nama,
            'cabang' => $this->gadai->branch?->nama,
            'status' => $this->gadai->status,
            'created_at' => $this->gadai->created_at?->toDateTimeString(),
        ];
    }
}
