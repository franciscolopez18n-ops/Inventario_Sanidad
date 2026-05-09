<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Storage;

class LowStockAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $storage, $materialName;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Storage $storage, $materialName)
    {
        $this->storage = $storage;
        $this->materialName = $materialName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //return $this->view('view.name');
        return $this->subject('⚠️ Alerta: Stock Bajo de Material')->view('emails.low_stock_alert');
    }
}
