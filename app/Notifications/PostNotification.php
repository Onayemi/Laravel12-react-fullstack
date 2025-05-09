<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class PostNotification extends Notification
{
    use Queueable;

    private Post $post;
    // private $pdf;
    // private $postCode;

    /**
     * Create a new notification instance.
     */
    public function __construct(Post $post) // $postCode
    {
        $this->post = $post;
        // $this->pdf = $pdf;
        // $this->postCode = $postCode;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // $filePath = public_path('invoice-yE7t7DDhkP.pdf');
        
        return (new MailMessage)
            ->subject('Your Invoice')
            ->greeting($this->post->title)
            ->line(['Post Title: ', $this->post->title])
            ->line(['Post Content: ', $this->post->content])
            ->line(['Post Category: ', $this->post->category])
            // ->line(['6 Digit Code: ', $this->postCode])
            // ->line('The introduction to the notification.')
            ->action('Verify you account', url('/'))
            ->line('Thank you!');
            // ->attach($filePath, [
            //     'as' => 'invoice_123.pdf',
            //     'mime' => 'application/pdf',
            // ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
