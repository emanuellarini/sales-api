<?php

namespace Tests\Feature\Commands;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Mail\SalesDailyReport;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use App\Models\Sale;

class SendDailySalesEmailTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
        factory(User::class)->states('salesman')->create();
        factory(Sale::class)->create();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSendEmailToSalesmen()
    {
        Mail::fake();
        Artisan::call('email:daily-sales');

        User::with('sales')
            ->get()
            ->each(function ($user) {
                Mail::assertSent(SalesDailyReport::class, function ($mail) use ($user) {
                    return $mail->hasTo($user->email);
                });
            });

    }
}
