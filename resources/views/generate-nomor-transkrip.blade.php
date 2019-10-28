@extends('template')

@section('body')
    <!-- Full Width Column -->
    <div class="content-wrapper">
        <div class="container">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Generate Nomor Transkrip
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
                                        </span>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <!-- /.box-body -->

                    <form action="" method="post">
                        @csrf

                    <div class="row">
                        <div class="col-xs-6 col-xs-offset-3">
                            <table class="table table-hover">
                                <tbody>

                                @if($errors && @$errors->any())
                                    <tr class="">
                                        <td colspan="2">
                                            @foreach($errors->all() as $error)
                                                {{$error}}
                                            @endforeach
                                        </td>
                                    </tr>
                                    <?php
                                        dd($errors);
                                    ?>
                                @endif


                                <tr class="">
                                    <th style="width:40%">Tanggal Lulus</th>
                                    <td>
                                        {{@$tugas_akhir['NiceTanggalLulus']}}
                                    </td>
                                </tr>

                                @if(@$nomortranskrip)
                                    <tr class="">
                                        <th style="width:40%">No Transkrip</th>
                                        <td>
                                            {{$nomortranskrip}}
                                        </td>
                                    </tr>
                                @endif
                                @if(@$noseri)
                                    <tr class="">
                                        <th style="width:40%">No Seri Ijazah</th>
                                        <td>
                                            {{$noseri}}
                                        </td>
                                    </tr>
                                @endif

                                @if(!@$nomortranskrip && !@$noseri)
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
                                            @foreach (range(date('Y'), date('Y')-3) as $tahun)
                                                <option value="{{$tahun}}" {!! $tahun==(session('tahun')??date('Y') )?' selected="selected" ':'' !!}>{{$tahun}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>

                                <tr class="">
                                    <td style="" colspan="2">
                                        <small>
                                            <i>
                                                Pilih bulan Januari 1930, jika ingin melakukan generate nomor transkrip sesuai tanggal lulus mahasiswa.
                                            </i>
                                        </small>
                                    </td>
                                </tr>

                                <tr class="">
                                    <td colspan="2" class="text-center">
                                        <button class="btn btn-success">Generate</button>
                                    </td>
                                </tr>
                                @endif


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
