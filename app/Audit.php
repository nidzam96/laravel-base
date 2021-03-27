<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Filterable;

class Audit extends Model
{
    use Filterable;

    protected $appends = ['event_detail'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function audit_for()
    {
        return $this->belongsTo(User::class,'auditable_id')->withTrashed();
    }

    public function getEventDetailAttribute() {
        
        switch ($this->event) {
            case "created":
                return ucfirst(trans($this->user->name)).' has created new user '. ucfirst(trans($this->audit_for->name));
                break;
            case "updated":
                return ucfirst(trans($this->user->name)).' has updated user '. ucfirst(trans($this->audit_for->name));
                break;
            case "deleted":
                return ucfirst(trans($this->user->name)).' has deleted user '. ucfirst(trans($this->audit_for->name));
                break;
            default:
                return '';
        }
    }
}
