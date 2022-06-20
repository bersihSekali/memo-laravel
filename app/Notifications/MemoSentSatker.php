<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MemoSentSatker extends Notification
{
    use Queueable;

    private $datas;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($datas)
    {
        $this->dataMemo = $datas;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('Anda mendapatkan memo masuk')
            ->line('Asal: ' . $this->dataMemo->satuanKerjaAsal['satuan_kerja'])
            ->line('Perihal: ' . $this->dataMemo->perihal)
            ->action('Cek Surat Masuk', url('/suratMasuk'))
            ->line('Terima kasih!');
    }

    /**->
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
