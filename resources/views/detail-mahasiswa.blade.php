<div class="row">
    <div class="col-xs-6">
        <table class="table table-hover">
            <tbody>
            <tr class="">
                <th style="width:30%">NPM</th>
                <td>{{$mahasiswa['MhswID']}}</td>
            </tr>
            <tr class="">
                <th style="width:30%">Nama</th>
                <td>{{$mahasiswa['Nama']}}</td>
            </tr>
            <tr class="">
                <th style="width:30%">Prodi</th>
                <td>{{@$mahasiswa['prodi']['Nama']}}</td>
            </tr>
            <tr class="">
                <th style="width:30%">Fakultas</th>
                <td>{{@$mahasiswa['prodi']['fakultas']['Nama']}}</td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="col-xs-6">
        <table class="table table-hover">
            <tbody><tr class="">
                <th style="width:30%">Nomor Transkrip</th>
                <td>{!! @$mahasiswa['tugas_akhir']['NiceNoSeriIjazah']?@$mahasiswa['tugas_akhir']['NiceNoSeriIjazah']:'<small class="text-danger">Silahkan generate Nomor Transkrip terlebih dahulu.</small>' !!}</td>
            </tr>
            <tr class="">
                <th style="width:30%">Tanggal Lulus</th>
                <td>{{@$mahasiswa['tugas_akhir']['NiceTanggalLulus']}}</td>
            </tr>
            <tr class="">
                <th style="width:30%">IPK</th>
                <td>{{@$mahasiswa['IPK']}}</td>
            </tr>
            <tr class="">
                <th style="width:30%">Lama Studi</th>
                <td>{{@$mahasiswa['LamaStudi']}}</td>
            </tr>
            </tbody></table>
    </div>
    <div class="col-xs-12 text-center">

        @if(session('npm') && @$mahasiswa)
            <a href="{{route('opsi_print')}}" class="btn btn-success" disabled="disabled" id="button-cetak">
                <span style="" id="hide-button-cetak">Loading...</span>
                <span id="show-button-cetak" style="display: none;">Cetak</span>
            </a>
            <a href="{{route('generate_nomor_transkrip')}}" class="btn btn-success" id="button-generate" style="display: none;">
                Generate Nomor Transkrip
            </a>
            <br>
            <br>
            @push('js')
                <script>
                    var NiceNoSeriIjazah = '{{@$mahasiswa['tugas_akhir']['NiceNoSeriIjazah']}}';
                    function loadingButton() {
                        $.ajax({
                            'url': '{{route('load_transkrip')}}',
                            'type': 'get',
                            'success': function (a, b) {
                                if (
                                    typeof a != 'undefined' && typeof a.status != 'undefined' && a.status
                                    &&
                                    typeof NiceNoSeriIjazah != 'undefined'
                                    && NiceNoSeriIjazah != ''
                                    && NiceNoSeriIjazah
                                ) {
                                    $("#button-cetak").removeAttr('disabled');
                                    $("#hide-button-cetak").hide();
                                    $("#show-button-cetak").show();
                                    $("#pdf-transkrip-link").show();
                                    $("#button-generate").hide();

                                } else if (
                                    !NiceNoSeriIjazah
                                ) {
                                    $("#button-generate").show();
                                    $("#button-cetak").hide();

                                } else {
                                    $("#button-cetak").attr('disabled', 'disabled');
                                    $("#hide-button-cetak").show();
                                    $("#show-button-cetak").hide();
                                }
                            }
                        })
                    }
                    loadingButton();
                </script>
            @endpush
        @endif
    </div>
</div>
