<?php

namespace EscolaLms\TemplatesEmail\Events;

use EscolaLms\Core\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;

class Registered
{
    use SerializesModels;

    public Authenticatable $user;

    public function __construct(Authenticatable $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        $user = $this->user;
        if ($user instanceof Model) {
            return User::find($user->getKey());
        }
        return $user;
    }
}
