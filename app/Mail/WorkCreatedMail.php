<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WorkCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $work;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($work)
    {
        $this->work = $work;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $name = trim($this->work->first_name.' '.$this->work->last_name);

        return $this->subject('TRABAJO #'.$this->work->id.' - Registrado ('.$name.')')
            ->view('emails.work_created');
    }
}
