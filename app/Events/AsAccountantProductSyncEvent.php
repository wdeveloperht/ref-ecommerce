<?php

namespace App\Events;

use Botble\Ecommerce\Models\Product;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;

class AsAccountantProductSyncEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $product;
    public $request;
    public $asAccountantId;

    /**
     * @param Product $product
     * @param array $request
     * @param int $asAccountantId
     */
    public function __construct(Product $product, array $request, int $asAccountantId)
    {
        $this->product = $product;
        $this->request = $request;
        $this->asAccountantId = $asAccountantId;
    }
}
