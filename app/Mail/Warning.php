<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Warning extends Mailable
{
    use Queueable, SerializesModels;

    public $params;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    //讓其他class可以把參數傳進來
    public function __construct($paramsx)
    {
        $this->params = $paramsx;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //透過with把參數指定給view
        return $this->subject("警告訊息")
            ->view('email.warning')
            ->with([
                'params' => $this->params,
            ]);
    }
}
