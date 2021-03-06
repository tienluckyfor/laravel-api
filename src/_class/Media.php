<?php

use Illuminate\Support\Arr;

class Media
{
    private $media;

    public function set($media)
    {
        $this->media = $media;
        return $this;
    }

    public function first()
    {
        return @Arr::first($this->media['media'])['file'];
    }

    public function files()
    {
        return collect($this->media['media'])
            ->map(function ($item) {
                return $item['file'];
            })
            ->toArray();
    }
}
