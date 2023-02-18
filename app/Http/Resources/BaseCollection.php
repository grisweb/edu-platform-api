<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @method total()
 * @method count()
 * @method perPage()
 * @method currentPage()
 * @method lastPage()
 */
class BaseCollection extends ResourceCollection
{
    protected function getPagination(): array
    {
        return [
            'total' => $this->total(),
            'count' => $this->count(),
            'perPage' => $this->perPage(),
            'currentPage' => $this->currentPage(),
            'totalPages' => $this->lastPage()
        ];
    }
}
