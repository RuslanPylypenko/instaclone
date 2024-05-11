<?php

namespace App\Jobs\User;

use App\Models\User\UserEntity;
use App\Models\User\UserStatus;
use App\Repositories\UsersRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckInactiveJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(UsersRepository $repository): void
    {
        $repository->findUncheckedInactive()->each(function(UserEntity $entity) {
            $entity->setStatus(UserStatus::LONG_TIME_INACTIVE);
            $entity->save();
        });
    }
}
