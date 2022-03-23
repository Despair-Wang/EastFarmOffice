<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodCategory extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getBelong()
    {
        if ($this->sub != 0) {
            $main = GoodCategory::Where('id', $this->sub)->first();
            return $main->name;
        } else {
            return '-';
        }
    }
}