<?php

namespace App\Policies;

use App\Models\CartItem;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CartItemPolicy
{
    use HandlesAuthorization;

    public function update(User $user, CartItem $cartItem)
    {
        $ownerId = $cartItem->user_id ?? optional($cartItem->cart)->user_id;
        return $ownerId && ($user->id === $ownerId);
    }

    public function delete(User $user, CartItem $cartItem)
    {
        $ownerId = $cartItem->user_id ?? optional($cartItem->cart)->user_id;
        return $ownerId && ($user->id === $ownerId);
    }
}