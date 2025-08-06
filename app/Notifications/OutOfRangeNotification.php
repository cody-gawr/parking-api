<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class OutOfRangeNotification extends Notification
{
    use Queueable;

    protected float $latitude;
    protected float $longitude;

    /**
     * Create a new notification instance.
     */
    public function __construct(float $latitude, float $longitude)
    {
        $this->latitude  = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable  // instance of \App\Models\User
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation for database storage.
     *
     * @param  mixed  $notifiable
     * @return array<string, mixed>
     */
    public function toDatabase($notifiable): array
    {
        return [
            'user_id'     => $notifiable->id,
            'user_name'   => $notifiable->name,
            'latitude'    => $this->latitude,
            'longitude'   => $this->longitude,
            'occurred_at' => now()->toDateTimeString(),
            'message'     => "No parking found within 500 meters of ({$this->latitude}, {$this->longitude})",
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed  $notifiable  // instance of \App\Models\User
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'notification' => [
                'id'           => $this->id,
                'user_id'      => $notifiable->id,
                'user_name'    => $notifiable->name,
                'latitude'     => $this->latitude,
                'longitude'    => $this->longitude,
                'occurred_at'  => now()->toDateTimeString(),
                'message'      => "No parking found within 500 meters of ({$this->latitude}, {$this->longitude})",
            ],
        ]);
    }

    /**
     * Get the array representation for mail channel (not used).
     *
     */
    public function toMail($notifiable)
    {
        //
    }
}
