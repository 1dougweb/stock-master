<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório de Requisições de Material</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0;
        }
        .header p {
            font-size: 12px;
            color: #666;
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #fafafa;
        }
        .status {
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-pending {
            background-color: #fef9c3;
            color: #854d0e;
        }
        .status-in-progress {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .status-completed {
            background-color: #dcfce7;
            color: #166534;
        }
        .status-canceled {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            color: #666;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Relatório de Requisições de Material</h1>
        <p>Gerado em: {{ now()->format('d/m/Y H:i:s') }}</p>
        @if(request('start_date') || request('end_date'))
            <p>
                Período: 
                {{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') : 'Início' }}
                até
                {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') : 'Hoje' }}
            </p>
        @endif
        @if(request('status'))
            <p>Status: {{ ucfirst(request('status')) }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Número</th>
                <th>Cliente</th>
                <th>Status</th>
                <th>Produtos</th>
                <th>Valor Total</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td>{{ $order->number }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td>
                        @switch($order->status)
                            @case('pending')
                                <span class="status status-pending">Pendente</span>
                                @break
                            @case('in_progress')
                                <span class="status status-in-progress">Em Andamento</span>
                                @break
                            @case('completed')
                                <span class="status status-completed">Concluído</span>
                                @break
                            @case('canceled')
                                <span class="status status-canceled">Cancelado</span>
                                @break
                        @endswitch
                    </td>
                    <td>
                        <ul>
                            @foreach($order->items as $item)
                                <li>{{ $item->product->name }} ({{ $item->quantity }})</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>R$ {{ number_format($order->total_amount, 2, ',', '.') }}</td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Nenhuma requisição de material encontrada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Este relatório foi gerado automaticamente pelo sistema de gestão de estoque.</p>
    </div>
</body>
</html>
