<?php

namespace App\Jobs;

use App\Services\Auth\AuthService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class sendVerificationMailSync implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $email;
    public $verificationCode;
    public $firstName;

    public function __construct($email, $verificationCode, $firstName)
    {
        $this->email = $email;
        $this->verificationCode = $verificationCode;
        $this->firstName = $firstName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $authService = new AuthService();
        $authService->sendVerificationCode($this->email, $this->verificationCode, $this->firstName);
    }
}
