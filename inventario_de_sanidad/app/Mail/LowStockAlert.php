<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
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
        return $this->subject('⚠️ Alerta: stock bajo de material')->view('emails.low-stock-alert');
    }
}
