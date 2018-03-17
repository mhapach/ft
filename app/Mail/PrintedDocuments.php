<?php

namespace App\Mail;

use App\Models\Dogovor;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PrintedDocuments extends Mailable
{
    use Queueable, SerializesModels;

    /** @var Dogovor $oDogovor */
    protected $oDogovor;

    /**
     * Create a new message instance.
     * @param Dogovor $dogovor
     */
    public function __construct(Dogovor $dogovor)
    {
        $this->oDogovor = $dogovor;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->subject(trans('Document is printed'))
            ->view('emails.document_printed')->with([
                'oDogovor' => $this->oDogovor
            ]);
    }
}