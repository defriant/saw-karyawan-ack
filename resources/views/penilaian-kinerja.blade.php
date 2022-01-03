@extends('layouts.master')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-headline" id="panel-prediksi-loading">
            {{-- Penilaian --}}
            <div class="panel-heading">
                <h3 class="panel-title">Penilaian Karyawan</h3>
            </div>
            <div class="panel-body">
                <p>Periode penilaian :</p>
                <div class="input-group" style="width: 300px">
                    <input class="form-control" type="text" id="periode-penilaian" readonly>
                    <span class="input-group-btn"><button class="btn btn-primary" type="button" id="search-penilaian"><i class="fas fa-search"></i></button></span>
                </div>
                <div class="loader" id="penilaian-karyawan-loader">
                    
                </div>
                <br>
                <table class="table" id="table-penilaian">
                    <thead>
                        <tr id="thead-penilaian">
                            
                        </tr>
                    </thead>
                    <tbody id="tbody-penilaian">
                        
                    </tbody>
                </table>
            </div>
            <hr>

            {{-- Normalisasi Penilaian --}}
            <div class="panel-heading">
                <h3 class="panel-title">Normalisasi Penilaian</h3>
            </div>
            <div class="panel-body">
                <div class="loader" id="normalisasi-penilaian-loader">
                    <i class="fas fa-ban" style="font-size: 5rem; opacity: .5"></i>
                    <h5 style="margin-top: 2.5rem; opacity: .75">Belum ada data yang dipilih</h5>
                </div>
                <table class="table" id="table-normalisasi-penilaian">
                    <thead>
                        <tr id="thead-normalisasi-penilaian">
                            
                        </tr>
                    </thead>
                    <tbody id="tbody-normalisasi-penilaian">
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalUpdatePenilaian" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            
        </div>
    </div>
</div>
@endsection