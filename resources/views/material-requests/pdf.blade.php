<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Requisição de Material #{{ $materialRequest->number }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .info {
            margin-bottom: 20px;
        }
        .info-row {
            margin-bottom: 5px;
        }
        .label {
            font-weight: bold;
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
            background-color: #f3f4f6;
        }
        .total {
            text-align: right;
            font-weight: bold;
            margin-top: 10px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
        }
        .signature {
            margin-top: 50px;
            text-align: center;
        }
        .signature-line {
            display: inline-block;
            width: 250px;
            border-top: 1px solid #000;
            margin-top: 50px;
            padding-top: 5px;
        }
        .signature-name {
            font-weight: bold;
        }
        .signature-role {
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Requisição de Material</div>
        <div>Número: {{ $materialRequest->number }}</div>
    </div>

    <div class="info">
        <div class="info-row">
            <span class="label">Data:</span>
            <span>{{ $materialRequest->created_at->format('d/m/Y H:i') }}</span>
        </div>
        <div class="info-row">
            <span class="label">Funcionário Requisitante:</span>
            <span>{{ $materialRequest->employee->name }} - {{ $materialRequest->employee->department }}</span>
        </div>
    </div>

    <div class="info">
        <div class="info-row">
            <span class="label">Cliente:</span>
            <span>{{ $materialRequest->customer_name }}</span>
        </div>
        @if($materialRequest->customer_phone)
            <div class="info-row">
                <span class="label">Telefone:</span>
                <span>{{ $materialRequest->customer_phone }}</span>
            </div>
        @endif
        @if($materialRequest->customer_email)
            <div class="info-row">
                <span class="label">Email:</span>
                <span>{{ $materialRequest->customer_email }}</span>
            </div>
        @endif
    </div>

    <div class="info">
        <div class="label">Descrição:</div>
        <div>{{ $materialRequest->description }}</div>
    </div>

    @if($materialRequest->notes)
        <div class="info">
            <div class="label">Observações:</div>
            <div>{{ $materialRequest->notes }}</div>
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Produto</th>
                <th>Quantidade</th>
                <th>Valor Unitário</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($materialRequest->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>R$ {{ number_format($item->product->price, 2, ',', '.') }}</td>
                    <td>R$ {{ number_format($item->quantity * $item->product->price, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Total: R$ {{ number_format($materialRequest->total_amount, 2, ',', '.') }}
    </div>

    <div class="signature">
        <div class="signature-line">
            <div class="signature-name">{{ $materialRequest->employee->name }}</div>
            <div class="signature-role">{{ $materialRequest->employee->role }} - {{ $materialRequest->employee->department }}</div>
        </div>
    </div>

    <div class="footer">
        Documento gerado em {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>
