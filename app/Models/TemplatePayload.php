<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplatePayload extends Model
{
    use HasFactory;

    protected $casts = [
        'payload' => 'json',
    ];

    function template_fields(){
        return $this->hasOne(TemplateField::class,"template_id","template_id");
    }
}
