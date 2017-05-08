<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;

class SalesDailyReport extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $sales = $this->user->sales;
        $total = number_format($this->user->sales->sum('amount'), 2, ',', '.');
        $date = \Carbon\Carbon::now()->format('d/m/Y');
        return $this->view('mails.daily-sales', compact(['total', 'date', 'sales']));
    }
}
