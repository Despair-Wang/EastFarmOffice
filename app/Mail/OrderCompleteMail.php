<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderCompleteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user, $serial, $details, $freight, $total, $pay;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $serial, $details, $freight, $total, $payment)
    {
        $this->user = $user;
        $this->serial = $serial;
        $this->details = $details;
        $this->freight = $freight;
        $this->total = $total;
        $this->payment = $payment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('東鄉事業-訂單編號' . $this->serial)
            ->view('mail.orderComplete')
            ->with([
                'user' => $this->user,
                'serial' => $this->serial,
                'details' => $this->details,
                'freight' => $this->freight,
                'total' => $this->total,
                'payment' => $this->payment,
            ]);
    }
}