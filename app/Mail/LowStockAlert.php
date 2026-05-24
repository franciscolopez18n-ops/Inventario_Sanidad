<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\StorageAssignment;

class LowStockAlert extends Mailable {
    use Queueable, SerializesModels;

    public $assignment;

    public function __construct(StorageAssignment $assignment) {
        $this->assignment = $assignment;
    }

    public function build() {
        return $this->subject('⚠️ Alerta: Stock Bajo de Material')->view('emails.low_stock_alert');
    }
}
