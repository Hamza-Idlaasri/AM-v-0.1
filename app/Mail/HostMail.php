<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HostMail extends Mailable
{
    use Queueable, SerializesModels;

    public $hosts;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(object $hosts_notified)
    {
        $this->hosts = $hosts_notified;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('alarmmanger@gmail.com', 'AM')->markdown('email.host')->with(['hosts' => $this->hosts]);
    }
}
