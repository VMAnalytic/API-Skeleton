<?php

namespace App\Http\Transformer\User;

use App\Domain\Entity\User\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        return [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'registered_at' => $user->getRegisteredAt(),
        ];
    }
}
