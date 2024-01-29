<?php

namespace App\Listeners;

use App\Events\PaypalWebhookEvent;
use App\Models\Subscriptions as SubscriptionsModel;
use App\Models\WebhookHistory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

use Throwable;

class PaypalWebhookListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    use InteractsWithQueue;
 
    public $afterCommit = true;

    // /**
    //  * The name of the connection the job should be sent to.
    //  *
    //  * @var string|null
    //  */
    // public $connection = 'sqs';
 
    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'paypallisteners';
 
    /**
     * The time (seconds) before the job should be processed.
     *
     * @var int
     */
    public $delay = 5; //60



    /**
     * Handle the event.
     */
    public function handle(PaypalWebhookEvent $event): void
    {
        try{
            Log::info(json_encode($event->payload));

            $incomingJson = json_decode($event->payload);

            // Incoming data is verified at PaypalController handleWebhook function, which fires this event.

            $event_type = $incomingJson->event_type;
            $resource_id = $incomingJson->resource->id;
            
            // save incoming data

            $newData = new WebhookHistory();
            $newData->gatewaycode = 'paypal';
            $newData->webhook_id = $incomingJson->id;
            $newData->create_time = $incomingJson->create_time;
            $newData->resource_type = $incomingJson->resource_type;
            $newData->event_type = $event_type;
            $newData->summary = $incomingJson->summary;
            $newData->resource_id = $resource_id;
            $newData->resource_state = $incomingJson->resource->state;
            if($event_type == 'PAYMENT.SALE.COMPLETED'){
                $newData->parent_payment = $incomingJson->resource->parent_payment;
                $newData->amount_total = $incomingJson->resource->amount->total;
                $newData->amount_currency = $incomingJson->resource->amount->currency;
            }
            $newData->incoming_json = json_encode($incomingJson);
            $newData->status = 'check';
            $newData->save();

            // switch/check event type

            if($event_type == 'BILLING.SUBSCRIPTION.CANCELLED'){

                $currentSubscription = SubscriptionsModel::where('stripe_id', $resource_id)->first();

                $currentSubscription->stripe_status = "cancelled";
                $currentSubscription->ends_at = \Carbon\Carbon::now();
                $currentSubscription->save();

            }else if($event_type == 'PAYMENT.SALE.COMPLETED'){


                
            }











            // save new order if required
            // on cancel we do not delete anything. just check if subs cancelled



        }catch(\Exception $ex){
            Log::error("PaypalWebhookListener::handle()\n".$ex->getMessage());
            error_log("PaypalWebhookListener::handle()\n".$ex->getMessage());
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(PaypalWebhookEvent $event, Throwable $exception): void
    {
        // $space = "*************************************************************************************************************";
        $space = "*****";
        $msg = '\n'.$space.'\n'.$space;
        $msg = $msg.json_encode($event->payload);
        $msg = $msg.'\n'.$space.'\n';
        $msg = $msg.'\n'.$exception.'\n';
        $msg = $msg.'\n'.$space.'\n'.$space;

        Log::error($msg);
    }
}
