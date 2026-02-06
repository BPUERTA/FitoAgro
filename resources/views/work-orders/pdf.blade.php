<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Work Order {{ $workOrder->code }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #111; }
        h1 { font-size: 18px; margin: 0 0 8px; }
        h2 { font-size: 14px; margin: 16px 0 8px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background: #f5f5f5; }
        .muted { color: #666; }
    </style>
</head>
<body>
    <h1>Work Order {{ $workOrder->code }}</h1>
    <p class="muted">Client: {{ $workOrder->client->name }}</p>
    <p class="muted">Priority: {{ ucfirst($workOrder->priority) }} | Status: {{ ucfirst($workOrder->status) }}</p>
    <p class="muted">Scheduled: {{ $workOrder->scheduled_start_at?->format('Y-m-d H:i') ?? '-' }} - {{ $workOrder->scheduled_end_at?->format('Y-m-d H:i') ?? '-' }}</p>

    <h2>Farms</h2>
    @forelse($workOrder->farms->groupBy('bloque') as $block => $farms)
        <h3>Block {{ $block }}</h3>
        <table>
            <thead>
                <tr>
                    <th>Farm</th>
                    <th>Ha</th>
                    <th>Distance</th>
                    <th>Applied</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($farms as $farm)
                    <tr>
                        <td>{{ $farm->exploitation_name }}</td>
                        <td>{{ number_format($farm->has, 2) }}</td>
                        <td>{{ $farm->distancia_poblado ?? '-' }}</td>
                        <td>{{ $farm->fecha_aplicacion?->format('Y-m-d') ?? '-' }}</td>
                        <td>{{ ucfirst($farm->line_status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @empty
        <p class="muted">No farms.</p>
    @endforelse

    <h2>Products</h2>
    @forelse($productsByBlock as $block => $products)
        <h3>Block {{ $block }}</h3>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Dose</th>
                    <th>Block ha</th>
                    <th>Line total</th>
                    <th>Unit price</th>
                    <th>Line cost</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ number_format($product->dosis, 2) }} {{ $product->um_dosis }}</td>
                        <td>{{ number_format($product->total_has_bloque, 2) }}</td>
                        <td>{{ number_format($product->total_linea_producto, 2) }} {{ $product->um_total }}</td>
                        <td>{{ $product->precio_unitario ?? '-' }}</td>
                        <td>{{ $product->total_costo_linea ?? '-' }}</td>
                        <td>{{ $product->nota ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @empty
        <p class="muted">No products.</p>
    @endforelse
</body>
</html>
