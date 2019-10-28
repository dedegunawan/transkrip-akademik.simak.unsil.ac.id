@extends('template')

@section('body')
    <!-- Full Width Column -->
    <div class="content-wrapper">
        <div class="container">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Transkrip Nilai Akhir Cetak
                </h1>
            </section>

            <!-- Main content -->
            <section class="content">

                <div class="box box-default">
                    <div class="box-body">
                            <div class="row">
                                <div class="col-md-6 col-md-offset-3">
                                    <div class="input-group ">
                                        @csrf
                                        <input type="text" name="" class="form-control" value="{{session('npm') ?? old('npm')}}" disabled="disabled">
                                        <span class="input-group-btn">

                                            <a href="{{route('transkrip_index')}}" class="btn btn-default">Kembali</a>
                                            <a href="" class="btn btn-default" onclick="resetPengaturan(event, this);">Reset Pengaturan</a>
                                            @push('js')
                                                <form action="{{route('reset_opsi_print')}}" method="post" id="reset-pengaturan">
                                                    @csrf
                                                    <input type="hidden" name="reset_opsi_print" value="<?=rand();?>">
                                                </form>

                                                <script>
                                                    function resetPengaturan(event, el) {
                                                        event.preventDefault();
                                                        $("#reset-pengaturan").submit();
                                                    }
                                                </script>
                                            @endpush
                                        </span>
                                    </div>
                                </div>
                            </div>
                    </div>


                    <!-- /.box-body -->

                    <form action="" method="post">
                        @csrf
                        <input type="hidden" name="do-print" value="1">

                    <div class="row">
                        <div class="col-xs-6 col-xs-offset-3">
                            <table class="table table-hover">
                                <tbody>
                                <tr class="">
                                    <th style="width:40%">Pilih Tanda Tangan Kiri</th>
                                    <td>
                                        <select name="ttd_kiri" id="ttd_kiri" class="form-control">
                                            <option value="" selected="selected">--Pilih Salah Satu--</option>
                                            @foreach ($pejabats as $pejabat)
                                                <option value="{{$pejabat['Jabatan']}}" {!! $pejabat['Jabatan']==session('ttd_kiri')?' selected="selected" ':'' !!}>{{$pejabat['Jabatan']}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>

                                <tr class="">
                                    <th style="width:40%">Pilih Tanda Tangan Kanan</th>
                                    <td>
                                        <select name="ttd_kanan" id="ttd_kanan" class="form-control">
                                            <option value="" selected="selected">--Pilih Salah Satu--</option>
                                            @foreach ($pejabats as $pejabat)
                                                <option value="{{$pejabat['Jabatan']}}" {!! $pejabat['Jabatan']==session('ttd_kanan')?' selected="selected" ':'' !!}>{{$pejabat['Jabatan']}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>

                                <tr class="">
                                    <th style="width:40%">Tanggal</th>
                                    <td>
                                        <select name="tanggal" id="tanggal" class="form-control">
                                            @foreach (range(1, 31) as $tanggal)
                                                <option value="{{$tanggal}}" {!! $tanggal==(session('tanggal')??date('d') )?' selected="selected" ':'' !!}>{{$tanggal}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>

                                <tr class="">
                                    <th style="width:40%">Bulan</th>
                                    <td>
                                        <select name="bulan" id="bulan" class="form-control">
                                            @foreach (array_values(\DedeGunawan\TranskripAkademikUnsil\Entities\Tanggal::getListBulan()) as $key => $bulan)
                                                <option value="{{$key+1}}" {!! $key+1==(session('bulan')??date('m') )?' selected="selected" ':'' !!}>{{$bulan}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>

                                <tr class="">
                                    <th style="width:40%">Tahun</th>
                                    <td>
                                        <select name="tahun" id="tahun" class="form-control">
                                            <option value="1930" selected="selected">1930</option>
                                            @foreach (range(date('Y'), date('Y')-13) as $tahun)
                                                <option value="{{$tahun}}" {!! $tahun==(session('tahun')??date('Y') )?' selected="selected" ':'' !!}>{{$tahun}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>

                                <tr class="">
                                    <td style="" colspan="2">
                                        <small class="text-danger">
                                            <i>
                                                Pilih Tanggal cetak pada 1 Januari 1930 jika ingin mencetak Transkrip sesuai tanggal lulus mahasiswa.
                                            </i>
                                        </small>
                                    </td>
                                </tr>

                                <tr class="">
                                    <th style="width:40%">Bahasa</th>
                                    <td>
                                        <select name="bahasa" id="bahasa" class="form-control">
                                            @foreach (['id' => 'Bahasa Indonesia', 'en' => 'English Language'] as $key => $bahasa)
                                                <option value="{{$key}}" {!! $key==(session('bahasa')??'id' )?' selected="selected" ':'' !!}>{{$bahasa}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>

                                <tr class="">
                                    <td colspan="2" class="text-center">
                                        <button class="btn btn-success">Cetak</button>
                                    </td>
                                </tr>


                                </tbody>
                            </table>
                        </div>

                    </div>

                    </form>


                </div>


                <!-- /.box -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.container -->
    </div>
    @push('js')
        <script>
            function clearFilter(event, el) {
                event.preventDefault();
                $("#clear-filter").submit();
            }
        </script>
    @endpush
@endsection
