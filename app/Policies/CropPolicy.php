<?php

namespace App\Policies;

use App\Models\Crop;
use App\Models\User;

class CropPolicy
{
    public function update(User $user, Crop $crop)
    {
        return $user->id === $crop->user_id;
    }

    public function delete(User $user, Crop $crop)
    {
        return $user->id === $crop->user_id;
    }
}
