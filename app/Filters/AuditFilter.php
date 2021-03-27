<?php

namespace App\Filters;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Role;

class AuditFilters extends QueryFilters
{
    /**
     * Filter by latest.
     *
     * @param  string $order
     * @return Builder
     */
    public function requested_by($id)
    {
        return $this->builder->whereHas("user", function($q) use ($id){
            $q->where('id', $id)->orWhere('admin_id', $id);
        });
    }
    
    public function role_by($role)
    {
        if ($role != '' && $role > 0) {
            $name = Role::find($role)->name;

            return $this->builder->whereHas("user", function($q) use ($name){ 
                $q->whereHas("roles", function($x) use ($name){
                    $x->where("name", $name);
                })
                ->where('user_type', 'App\User');
            });
        }
    }

    public function event($event)
    {
        if (!is_null($event)) {
            if ($event != '') {
                return $this->builder->where('event', 'like', '%'. $event .'%');
            }
        }   
    }

    public function detail($detail)
    {
        if (!is_null($detail)) {
            if ($detail != '') {
                return $this->builder->where('new_values', 'like', '%'. $detail .'%');
            }
        }
    }

}