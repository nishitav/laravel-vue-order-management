<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class OrderController extends Controller
{
    /**
     * API end point to get Orders
     */
    public function index(Request $request)
    {
        return Inertia::render('Order/Index', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }
    /**
     * API end point for order list
     */
    public function list(Request $request): JsonResponse
    {
        $perPage = $request->input('limit', 10); // Default limit is 10
        $orders = Order::with('customer')->paginate($perPage);

        // Return JSON response
        return new JsonResponse($orders);
    }

}
