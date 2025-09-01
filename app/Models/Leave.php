<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Str;

class Leave extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

     public function generateApprovalToken()
    {
        return hash('sha256', $this->id . $this->user_id . $this->created_at . config('app.key'));
    }

    public function verifyApprovalToken($token)
    {
        return hash_equals($this->generateApprovalToken(), $token);
    }
}
