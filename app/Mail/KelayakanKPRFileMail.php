<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class KelayakanKPRFileMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $namaNasabah;
    public string $namaRumah;
    public $file; // UploadedFile instance

    public function __construct(string $namaNasabah, string $namaRumah, $file)
    {
        $this->namaNasabah = $namaNasabah;
        $this->namaRumah = $namaRumah;
        $this->file = $file;
    }

    public function build()
    {
        return $this->subject('Persetujuan Kredit Pemilikan Rumah (KPR)')
            ->view('emails.kelayakan-kpr')
            ->with([
                'nama' => $this->namaNasabah,
                'rumah' => $this->namaRumah,
            ])
            ->attach($this->file->getRealPath(), [
                'as' => 'Persetujuan_KPR.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
