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
                        <form action="" method="post">
                            <div class="row">
                                <div class="col-md-6 col-md-offset-3">
                                    <div class="input-group ">
                                        @csrf
                                        <input type="text" name="npm" placeholder="Cari Mahasiswa, masukkan NPM" class="form-control" value="{{session('npm') ?? old('npm')}}">
                                        <span class="input-group-btn">
                                            <button type="submit" class="btn btn-success">Cari</button>

                                            <a onclick="clearFilter(event, this)" class="btn btn-default">Reset</a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <form action="" id="clear-filter" method="post">
                            @csrf
                            <input type="hidden" name="npm" value="clear">
                        </form>
                    </div>
                    <!-- /.box-body -->

                    @includeWhen(session('npm') && @$mahasiswa, 'detail-mahasiswa')

                    @includeWhen(session('npm') && @$mahasiswa, 'pdf-transkrip-link', ['link' => route('generate_transkrip_preview')])

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
