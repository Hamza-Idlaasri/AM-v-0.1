<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BoxMail extends Mailable
{
    use Queueable, SerializesModels;

    public $boxs;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(object $boxs_notified)
    {
        $this->boxs = $boxs_notified;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('alarmmanger@gmail.com', 'AM')->markdown('email.box')->with(['boxs' => $this->boxs]);
    }
}
