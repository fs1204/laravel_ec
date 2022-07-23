<?php

namespace App\Jobs;

use App\Mail\TestMail;
use App\Mail\ThanksMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendThankMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public $products, public $user)
    {
        $this->products = $products;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Mail::to('test@example.com')->send(new TestMail());
        Mail::to($this->user) // userの中でemailのプロパティを自動で探してくれる
            ->send(new ThanksMail($this->products, $this->user)); // Mailableクラスのインスタンスに渡す。
    }
}
