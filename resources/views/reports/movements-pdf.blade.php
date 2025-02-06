<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório de Movimentações</title>
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
            font-size: 10px;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #fafafa;
        }
        .type {
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .type-in {
            background-color: #dcfce7;
            color: #166534;
        }
        .type-out {
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
        <h1>Relatório de Movimentações</h1>
        <p>Gerado em: {{ now()->format('d/m/Y H:i:s') }}</p>
        @if(request('start_date') || request('end_date'))
            <p>
                Período: 
                {{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') : 'Início' }}
                até
                {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') : 'Hoje' }}
            </p>
        @endif
        @if(request('type'))
            <p>Tipo: {{ request('type') === 'in' ? 'Entrada' : 'Saída' }}</p>
        @endif
        @if(request('product_id'))
            <p>Produto: {{ \App\Models\Product::find(request('product_id'))?->name }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Data/Hora</th>
                <th>Produto</th>
                <th>Tipo</th>
                <th>Quantidade</th>
                <th>Requisição de Material</th>
                <th>Usuário</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($movements as $movement)
                <tr>
                    <td>{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $movement->product->name }}</td>
                    <td>
                        @if($movement->type === 'in')
                            <span class="type type-in">Entrada</span>
                        @else
                            <span class="type type-out">Saída</span>
                        @endif
                    </td>
                    <td>{{ $movement->quantity }}</td>
                    <td>
                        @if($movement->service_order_id)
                            {{ $movement->serviceOrder->number }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $movement->user->name }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Nenhuma movimentação encontrada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Este relatório foi gerado automaticamente pelo sistema de gestão de estoque.</p>
    </div>
</body>
</html>
