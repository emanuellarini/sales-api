<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <p>{{ $date }} - Total das vendas diárias: R$ {{ $total }}</p>

        @if(count($sales))
            <table style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Valor</th>
                        <th>Comissão</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sales as $key => $sale)
                        <tr>
                            <th>{{ $key+1 }}</th>
                            <th>R$ {{ number_format($sale->amount, 2, ',', '.') }}</th>
                            <th>R$ {{ number_format($sale->commission, 2, ',', '.') }}</th>
                            <th>{{ $sale->created_at->format('d/m/Y H:i') }}</th>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else

        @endif
    </body>
</html>
