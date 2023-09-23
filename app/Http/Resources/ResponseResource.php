<?php

namespace App\Http\Resources;

use DateTime;
use Illuminate\Http\Resources\Json\JsonResource;

class ResponseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "data" => parent::toArray($request),
            "date" => new DateTime()
        ];
    }

    public static function sendMsg($text)
    {
        return [
            "msg" => $text,
            "date" => new DateTime()
        ];
    }
}
