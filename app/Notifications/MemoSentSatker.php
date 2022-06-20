<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MemoSentSatker extends Notification implements ShouldQueue
{
    use Queueable;

    private $datas;
    private $asal;
    private $dataMemo;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($datas)
    {
        $this->dataMemo = $datas;
        $this->asal = $datas->satuanKerjaAsal['satuan_kerja'];
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
            ->subject('Memo Masuk')
            ->line('Anda mendapatkan memo masuk')
            ->line('Asal: ' . $this->asal)
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
