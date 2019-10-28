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

                <div class="alert alert-danger">
                    {{$exception->getMessage() ?? 'Mahasiswa tidak ditemukan / mahasiswa belum dinyatakan lulus'}}. Silahkan cari mahasiswa lain.
                    <br/>
                    <a href="" onclick="clearFilter(event, this)" ><small>Cari yang lain</small></a>
                    <form action="" id="clear-filter" method="post">
                        @csrf
                        <input type="hidden" name="npm" value="clear">
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
