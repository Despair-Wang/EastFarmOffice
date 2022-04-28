<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RestockNoticeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $good, $cover;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($good, $cover)
    {
        $this->good = $good;
        $this->cover = $cover;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('您關注的商品進貨了！')
            ->view('mail.restockNotice')
            ->with([
                'good' => $this->good,
                'cover' => $this->cover,
            ]);
    }
}