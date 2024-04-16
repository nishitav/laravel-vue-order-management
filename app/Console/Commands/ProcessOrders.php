<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Address;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProcessOrders extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will read JSON file and import orders in to DB.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {

            $filePath = 'order.json';
            $data = Storage::json($filePath);

            $totalOrders = 0;

            if (!empty($data)) {
                foreach ($data as $orderData) {
                    // Check if customer with email exists
                    $customer = Customer::firstOrNew(['email' => $orderData['email']]);
                    $customer->name = $orderData['customer_name'];
                    $customer->phone = $orderData['phone'];
                    $customer->save();

                    // Check if address with customer_id and type exists
                    $address = Address::firstOrNew(['customer_id' => $customer->id, 'type' => 'shipping']);
                    $address->street = $orderData['shipping_address']['street'];
                    $address->city = $orderData['shipping_address']['city'];
                    $address->state = $orderData['shipping_address']['state'];
                    $address->zip = $orderData['shipping_address']['zip'];
                    $address->country = $orderData['shipping_address']['country'];
                    $address->save();

                    // Check if order with order_id exists
                    $order = Order::firstOrNew(['order_number' => $orderData['order_id']]);
                    $order->customer_id = $customer->id;
                    $order->total = $orderData['total'];
                    $order->payment_method = $orderData['payment_method'];
                    $order->status = $orderData['status'];
                    $order->save();

                    foreach ($orderData['items'] as $itemData) {
                        // Check if product with product_id exists
                        $product = Product::firstOrNew(['product_number' => $itemData['product_id']]);
                        $product->name = $itemData['name'];
                        $product->save();

                        // Check if order item with product_id and order_id exists
                        $orderItem = OrderItem::firstOrNew(['order_id' => $order->id, 'product_id' => $product->id]);
                        $orderItem->name = $itemData['name'];
                        $orderItem->quantity = $itemData['quantity'];
                        $orderItem->price = $itemData['price'];
                        $orderItem->subtotal = $itemData['subtotal'];
                        $orderItem->save();
                    }

                    $totalOrders++;
                }

                $this->info("$totalOrders orders processed successfully.");
            } else {
                $this->info("No orders found for process.");
            }
        } catch (\Exception $e) {
            $this->error("An error occurred: " . $e->getMessage());
        }
    }
}
