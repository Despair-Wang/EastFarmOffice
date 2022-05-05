<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RestockNoticeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $id, $good, $cover, $user, $type;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $id, $good, $cover, $type)
    {
        $this->user = $user;
        $this->id = $id;
        $this->good = $good;
        $this->cover = $cover;
        $this->type = $type;
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
                'user' => $this->user,
                'id' => $this->id,
                'good' => $this->good,
                'cover' => $this->cover,
                'type' => $this->type,
            ]);
    }
}