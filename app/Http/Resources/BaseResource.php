<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $shouldAddContext = $request->get('context');
        $context = $shouldAddContext ? $this->context($request) : [];
        return [
            'data' => parent::toArray($request),
            'meta' => [
                // Add resource context when required.
                $this->mergeWhen($shouldAddContext, $context)
            ]
        ];
    }

    /**
     * Here a resource can add additional context information
     * to the response meta attribute.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function context($request) {
        return [
            //
        ];
    }
}
