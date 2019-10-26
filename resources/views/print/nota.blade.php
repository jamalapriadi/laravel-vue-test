<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Order # {{$nota->no_order}}</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style type="text/css" media="print">
        @page { size: landscape; }
    </style>
</head>
<body onload="window.print();window.close();">
    <div class="container">
        <br><br>
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3">
                <div class="form-group row" style="margin-top:10px;">
                    <label for="" class="control-label">{{$nota->perusahaan->nama}}</label>
                </div>
                <div class="form-group row" style="margin-top:-20px;">
                    <label for="" class="control-label">{{$nota->perusahaan->ket->no_hp}}</label>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <h3 class="text-center">NOTA PENJUALAN</h3>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3" style="margin-top:10px;">
                <div class="form-group row">
                    <label for="" class="control-label">Sales : {{$nota->sales->nm}}</label>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group row">
                    <label for="" class="control-label">Nomor</label>
                    <div class="col-lg-9 col-md-9 col-sm-9">
                        : {{$nota->no_order}}
                    </div>
                </div>
                <div class="form-group row" style="margin-top:-20px;">
                    <label for="" class="control-label">Tanggal</label>
                    <div class="col-lg-9 col-md-9 col-sm-9">
                        : {{$nota->tgl}}
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group row">
                    <label for="" class="control-label">Customer</label>
                    <div class="col-lg-9 col-md-9 col-sm-9">
                        {{$nota->picking->po->customer->nm}}
                        <br>
                        {{$nota->picking->po->customer->nm_toko}}
                        <br>
                        {{$nota->picking->po->customer->alamat}}
                    </div>
                </div>
            </div>

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

            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group row">
                    <label for="" class="control-label">No. HP</label>
                    <div class="col-lg-9 col-md-9 col-sm-9">
                        : {{$user->perusahaan->ket->no_hp}} (WHATSAPP)
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="control-label">Email</label>
                    <div class="col-lg-9 col-md-9 col-sm-9">
                        : {{$user->perusahaan->ket->email}}
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="form-group">
                            <label for="" class="control-label">Penerima</label>
                            <br>
                            <br>
                            <br>
                            {{$nota->picking->po->customer->nm}}
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="form-group">
                            <label for="" class="control-label">Hormat Kami</label>
                            <br>
                            <br>
                            <br>
                            ......................
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>