<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function deliver($id)
{
    $order = Order::findOrFail($id);
    $order->is_delivered = true;
    $order->save();
    return response()->json($order);
}

public function undeliver($id)
{
    $order = Order::findOrFail($id);
    $order->is_delivered = false;
    $order->save();
    return response()->json($order);
}
    public function index()
    {
        return Order::latest()->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string',
            'customer_phone' => 'required|string',
            'customer_address' => 'nullable|string',
            'items' => 'required|array',
            'total' => 'required|numeric',
        ]);

        $order = Order::create($validated);

        $this->sendNotification($order);

        return response()->json($order, 201);
    }

    private function sendNotification(Order $order)
{
    try {
        $topic = 'mblifestyle-ikram-2026-xyz';

        $items = collect($order->items)->map(function ($item) {
            return "- {$item['product_name']} x{$item['quantity']} ({$item['price']} DH)";
        })->implode("\n");

        $message = "Nouvelle commande #{$order->id}\n"
            . "Client : {$order->customer_name}\n"
            . "Tél : {$order->customer_phone}\n"
            . "Total : {$order->total} DH\n"
            . "Articles :\n" . $items;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://ntfy.sh/{$topic}");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $message);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Title: Nouvelle commande',
            'Priority: high',
            'Tags: shopping',
            'Content-Type: text/plain',
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $result = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            \Log::error('Ntfy curl error: ' . $error);
        } else {
            \Log::info('Ntfy response: ' . $result);
        }

    } catch (\Exception $e) {
        \Log::error('Notification failed: ' . $e->getMessage());
    }
}

    public function confirm(Order $order)
    {
        if ($order->status !== 'pending') {
            return response()->json(['message' => 'Commande déjà traitée'], 400);
        }

        foreach ($order->items as $item) {
            $product = Product::find($item['product_id']);
            if (!$product) continue;

            $qty = intval($item['quantity']);
            $itemColor = $item['color'] ?? null;
            $itemSize = $item['size'] ?? null;

            $colors = $product->colors ?? [];
            $sizes = $product->sizes ?? [];

            // Cas 1 : couleur sélectionnée
            if ($itemColor && !empty($colors)) {
                $colors = array_map(function ($c) use ($itemColor, $itemSize, $qty) {
                    if ($c['hex'] !== $itemColor) return $c;

                    if ($itemSize && !empty($c['sizes'])) {
                        $c['sizes'] = array_map(function ($s) use ($itemSize, $qty) {
                            if ($s['size'] === $itemSize) {
                                $s['stock'] = max(0, intval($s['stock'] ?? 0) - $qty);
                            }
                            return $s;
                        }, $c['sizes']);
                    }

                    if (!empty($c['sizes'])) {
                        $c['stock'] = array_sum(array_column($c['sizes'], 'stock'));
                    } else {
                        $c['stock'] = max(0, intval($c['stock'] ?? 0) - $qty);
                    }

                    return $c;
                }, $colors);
            }

            // Cas 2 : pas de couleur — déduire de toutes les couleurs qui ont cette taille
            if (!$itemColor && !empty($colors) && $itemSize) {
                $colors = array_map(function ($c) use ($itemSize, $qty) {
                    if (!empty($c['sizes'])) {
                        $c['sizes'] = array_map(function ($s) use ($itemSize, $qty) {
                            if ($s['size'] === $itemSize) {
                                $s['stock'] = max(0, intval($s['stock'] ?? 0) - $qty);
                            }
                            return $s;
                        }, $c['sizes']);
                        $c['stock'] = array_sum(array_column($c['sizes'], 'stock'));
                    }
                    return $c;
                }, $colors);
            }

            // Mettre à jour les tailles globales
            if ($itemSize && !empty($sizes)) {
                $sizes = array_map(function ($s) use ($itemSize, $qty) {
                    if ($s['size'] === $itemSize) {
                        $s['stock'] = max(0, intval($s['stock'] ?? 0) - $qty);
                    }
                    return $s;
                }, $sizes);
            }

            // Recalculer stock global
            $totalStock = 0;
            if (!empty($colors)) {
                foreach ($colors as $c) {
                    $totalStock += intval($c['stock'] ?? 0);
                }
            } elseif (!empty($sizes)) {
                foreach ($sizes as $s) {
                    $totalStock += intval($s['stock'] ?? 0);
                }
            } else {
                $totalStock = max(0, intval($product->stock) - $qty);
            }

            $product->update([
                'colors' => $colors,
                'sizes' => $sizes,
                'stock' => $totalStock,
            ]);
        }

        $order->update(['status' => 'confirmed']);
        \App\Models\ActivityLog::record(
            'order_confirmed',
            "Commande #{$order->id} confirmée — Client : {$order->customer_name} — Total : {$order->total} DH",
            'Order',
            $order->id
        );
        return response()->json($order);
    }

    public function cancel(Order $order)
    {
        $order->update(['status' => 'cancelled']);
        \App\Models\ActivityLog::record(
            'order_cancelled',
            "Commande #{$order->id} annulée — Client : {$order->customer_name}",
            'Order',
            $order->id
        );
        return response()->json($order);
    }
    
}