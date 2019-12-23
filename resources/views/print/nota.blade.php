<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Order # {{$nota->no_order}}</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style type="text/css" media="print">
        body {
            margin-top: 47px;
            font-size: 9px;
            font-family: Arial, Helvetica, sans-serif;
        }
        table {
            border-spacing: 0px;
            border-collapse: separate;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <table style="width:100%">
        <tr>
            <td>{{$nota->perusahaan->nama}}</td>
            <td><h3 class="text-center">NOTA PENJUALAN</h3></td>
            <td>
                <label for="" class="control-label">Sales : {{$nota->sales->nm}}</label>
            </td>
        </tr>
        <tr>
            <td>{{$nota->perusahaan->ket->no_hp}}</td>
        </tr>
    </table>

    <br>

    <table width="100%">
        <tr>
            <td width="15%">Nomor</td>
            <td>: {{$nota->no_order}}</td>
            <td width="30%">Customer</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>: {{$nota->tgl}}</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>
                {{$nota->picking->po->customer->nm}}
                <br>
                {{$nota->picking->po->customer->alamat}}
                <br>
                {{$nota->picking->po->customer->tlpn}}
            </td>
        </tr>
    </table>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Dos</th>
                <th>PCS</th>
                <th>Harga</th>
                <th>Diskon 1 (%)</th>
                <th>Diskon 2 (%)</th>
                <!-- <th>Discount</th> -->
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($nota->detail as $row)
                <tr>
                    <td>{{$row->nm}}</td>
                    <td>{{$row->pivot->dos}}</td>
                    <td>{{$row->pivot->pcs}}</td>
                    <td>{{number_format($row->pivot->hrg)}}</td>
                    <td>{{$row->pivot->diskon_persen}} %</td>
                    <td>{{$row->pivot->diskon_persen_2}} %</td>
                    <!-- <td>{{$row->pivot->diskon_rupiah}}</td> -->
                    <td>{{number_format($row->pivot->subtotal)}}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th rowspan="2">{{$nota->update_at}}</th>
                <th rowspan="2">{{auth()->user()->username}}</th>
                <th colspan="4">DISKON TAMBAHAN</th>
                <th>{{number_format($nota->diskon_rupiah)}}</th>
            </tr>
            <tr>
                <th colspan="4">TOTAL</th>
                <th>{{number_format($nota->total)}}</th>
            </tr>
        </tfoot>
    </table>


    <table class="table">
        <tr>
            <td width="40%">
                No. HP : {{$user->perusahaan->ket->no_hp}} (WHATSAPP) 
                <br>
                Email : {{$user->perusahaan->ket->email}}
            </td>
            <td width="30%">
                Penerima

                <br>
                <br>
                <br>
                {{$nota->picking->po->customer->nm}}
            </td>
            <td width="30%">
                Hormat Kami

                <br>
                <br>
                <br>
                ......................
            </td>
        </tr>
    </table>
    
</body>
</html>