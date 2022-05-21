<?php

namespace Gdinko\Econt\Events;

use Gdinko\Econt\Models\CarrierEcontPayment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CarrierEcontPaymentEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $payment;

    public $account;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(CarrierEcontPayment $payment, string $account)
    {
        $this->payment = $payment;

        $this->account = $account;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
