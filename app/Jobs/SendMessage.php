<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	private $notice;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(\App\Model\Notice $notice)
    {
        //
		$this->notice = $notice;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
		// 通知每个用户系统消息
		$users = \App\User::all();
		foreach ($users as $user) {
			$user->addNotice($this->notice);
		}
    }
}
