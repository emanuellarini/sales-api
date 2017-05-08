<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\SalesDailyReport;

class SendDailySalesEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:daily-sales';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a daily sales report email to each salesman';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        User::with('sales')
            ->get()
            ->each(function ($user) {
                $mailContent = new SalesDailyReport($user);
                Mail::to($user->email)->queue($mailContent);
            });
    }
}
