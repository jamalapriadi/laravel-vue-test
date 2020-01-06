<!DOCTYPE html>
<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Order # {{$nota->no_order}}</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style type="text/css" media="print">
        @page {
            size: 21cm 29.7cm;
            margin: 0; /* change the margins as you want them to be. */
        }

        body  
        {
            /* this affects the margin on the content before sending to printer */ 
            margin: 0px;  
        }
        table {
            border-spacing: 0px;
            border-collapse: separate;
        }
        
    </style>
    
   
</head>
<body style=" margin-left:-5%; margin-right:2%;margin-top:-5%;height:100%;">
    @php
        $jumlah_detail = count($nota->detail);
        $hasil_bagi = floor(count($nota->detail) / 9);
        $sisa_bagi = count($nota->detail) % 9;
        
        if($hasil_bagi == 0){
            $jumlah_halaman = $hasil_bagi+1;
        }else{
            $jumlah_halaman = $hasil_bagi+1;
        }
        
        $mulai=0;
        $selesai = 8;
    @endphp 
    
    @for($a=1; $a<= $jumlah_halaman; $a++)
        <table style="width:100%; margin-bottom:5px;">
            <tr>
                <td style="padding:0;font-size:12px;">{{$nota->perusahaan->nama}}</td>
                <td style="padding:0;" width="40%"><h3 class="text-center">NOTA PENJUALAN</h3></td>
                <td style="padding:0;font-size:12px;text-align:right;">
                    Sales : {{$nota->sales->nm}}
                </td>
            </tr>
            <tr>
                
                 <td style="padding:0;font-size:12px;" >Customer : {{$nota->picking->po->customer->nm}}</td>
                    
              
            </tr>
        </table>
    
        <table width="100%" style="font-size:12px;">
            <tr>
                <td style="padding:0;" width="15%">Nomor</td>     
                <td style="padding:0;" width="25%">: {{$nota->no_order}}</td>
        
               
            </tr>
            <tr>
                <td style="padding:0;">Tanggal</td>
                <td width=195px; style="padding:0;">: {{$nota->tgl}} </td>
                <td width=357px; style="font-size:12px" >Alamat : {{$nota->picking->po->customer->alamat}} {{$nota->picking->po->customer->tlpn}}</td>
                <td> Tanggal Jt. {{$nota->tgljt}}</td>
              
            </tr>
        </table>
        
        <table class="table table-striped" style="font-size:12px;width:100%;height:230px;">
            
                <tr>
                    <th width=300px; style="padding:2px;">Nama Barang</th>
                    <th width=50px; style="padding:2px; text-align:right;">Dos</th>
                    <th width=50px; style="padding:2px; text-align:right;">Pcs</th>
                    <th width=100px; style="padding:2px; text-align:right;">Harga</th>
                    <th width=50px; style="padding:2px; text-align:right;">Disc1</th>
                    <th width=50px; style="padding:2px; text-align:right;">Disc2</th>
                    <th style="padding:2px; text-align:right;">Jumlah</th>
                </tr>
                
                @if($a==1)
                    @php
                        $mulai=0;
                        $selesai = 10;
                    @endphp
                @else
                    @php
                        $mulai= $selesai;
                        $selesai = $mulai + 10;
                    @endphp
                @endif
            
                @foreach($nota->detail as $key=>$row)
                    @if($key >= $mulai && $key < $selesai)
                        <tr>
                            <td style="padding:2px;">{{substr($row->nm,0,45)}}</td>
                            <td style="padding:2px; text-align:right;">{{$row->pivot->dos}}</td>
                            <td style="padding:2px; text-align:right;">{{$row->pivot->pcs}}</td>
                            <td style="padding:2px; text-align:right;">{{number_format($row->pivot->hrg)}}</td>
                            <td style="padding:2px; text-align:right;">{{$row->pivot->diskon_persen}} %</td>
                            <td style="padding:2px; text-align:right;">{{$row->pivot->diskon_persen_2}} %</td>
                            <td style="padding:2px; text-align:right;">{{number_format($row->pivot->subtotal)}}</td>
                        </tr>
                    @endif
                @endforeach
            </table>
            
            @if($a == $jumlah_halaman)
            <table class="table table-striped" style="font-size:12px;width:100%;margin-bottom:-3px;margin-top:-15px">
                <tr>
                    <th width=150px; style="padding:2px;" rowspan="2">{{auth()->user()->username}}</th>
                    <th style="padding:2px;" rowspan="2">{{$nota->update_at}}</th>
                                    <th width=200px; style="padding:2px;" colspan="3">Page {{$a}} of {{$jumlah_halaman}}</th>
                    <th style="padding:2px;">DISKON TAMBAHAN</th>
                    <th style="padding:2px; text-align:right;">{{number_format($nota->diskon_rupiah)}}</th>
                </tr>
                <tr>
                    <th style="padding:2px;" colspan="3"></th>
                    <th style="padding:2px;">TOTAL</th>
                    <th style="padding:2px; text-align:right;">{{number_format($nota->total)}}</th>
                </tr>
            </table>
            @endif
            
    
        
            <table class="table" style="font-size:12px;">
                <tr>
                    <td style="padding:2px;" width="40%">
                        No. HP : {{$user->perusahaan->ket->no_hp}} (WHATSAPP) 
                        <br>
                        Email : {{$user->perusahaan->ket->email}}
                    </td>
                    <td style="padding:2px;text-align:center;" width="30%">
                        Penerima
                        <br>
                        <br>
                        <br>
                        {{$nota->picking->po->customer->nm}}
                    </td>
                    <td style="padding:2px;text-align:center;" width="30%">
                        Hormat Kami
                        <br>
                        <br>
                        <br>
                        ......................
                    </td>
                </tr>
            </table>
            
            
        
        
        @if($a != $jumlah_halaman)
            <div class="page-break"></div>
        @endif
        
    @endfor
    
</body>
</html>