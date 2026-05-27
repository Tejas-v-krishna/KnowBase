<?php
namespace App\Listeners;

use App\Events\ReputationChanged;
use App\Services\ReputationService;

class AwardBadgesOnReputationChange
{
    public function handle(ReputationChanged $event): void
    {
        ReputationService::checkAndAwardBadges($event->user);
    }
}
