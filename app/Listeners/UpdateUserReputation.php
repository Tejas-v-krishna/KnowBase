<?php
namespace App\Listeners;

use App\Events\ReputationChanged;

class UpdateUserReputation
{
    public function handle(ReputationChanged $event): void
    {
        // Handled directly inside ReputationService and controllers.
    }
}
