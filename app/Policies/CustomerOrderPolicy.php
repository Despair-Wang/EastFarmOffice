<?php

namespace App\Policies;

use App\Models\GoodOrder;
use App\Models\Order;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerOrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\GoodOrder  $goodOrder
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(GoodOrder $goodOrder)
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\GoodOrder  $goodOrder
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(GoodOrder $customer, GoodOrder $order)
    {
        return $customer->is($order);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\GoodOrder  $goodOrder
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(GoodOrder $goodOrder)
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\GoodOrder  $goodOrder
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(GoodOrder $customer, GoodOrder $order)
    {
        return $customer->is($order);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\GoodOrder  $goodOrder
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(GoodOrder $goodOrder, GoodOrder $order)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\GoodOrder  $goodOrder
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(GoodOrder $goodOrder, GoodOrder $order)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\GoodOrder  $goodOrder
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(GoodOrder $goodOrder, GoodOrder $order)
    {
        return false;
    }
}