<?php

namespace App\Filters;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Role;

class UserFilters extends QueryFilters
{
    /**
     * Filter by latest.
     *
     * @param  string $order
     * @return Builder
     */
    public function requested_by($id)
    {
        return $this->builder->where('id', $id);
    }

    public function user_id($user_id)
    {
        if (!is_null($user_id)) {
            return $this->builder->whereIn('id', $user_id);
        }
    }

    public function role_by($role)
    {
        if ($role != '' && $role > 0) {
            $name = Role::find($role)->name;

            return $this->builder->whereHas("roles", function($q) use ($name){ $q->where("name", $name); });
        }
    }

    public function name($name) {
        if (!is_null($name)) {
            if ($name != '') {
                return $this->builder->where('name', 'like', '%'. $name .'%');
            }
        }
    }

    public function email($email) {
        if (!is_null($email)) {
            if ($email != '') {
                return $this->builder->where('email', 'like', '%'. $email .'%');
            }
        }
    }

}