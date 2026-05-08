<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Contracts\StockStorage;

class LowStockAlert extends Mailable {
    use Queueable, SerializesModels;

    public $storage;

    public function __construct(StockStorage $storage) {
        $this->storage = $storage;
    }

    public function build() {
        return $this->subject('⚠️ Alerta: Stock Bajo de Material')->view('emails.low_stock_alert');
    }
}
