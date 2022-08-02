<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Services\Auth\AuthService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendPasswordResetMailSync implements ShouldQueue
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
        $authService->sendPasswordRest($this->email, $this->verificationCode, $this->firstName);
    }
}
