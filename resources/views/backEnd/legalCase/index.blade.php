@extends('backEnd.layouts.main')
@section('title', 'Pencatatan Kasus - '.config('app.name'))
@section('content')
<div class="pagetitle">
    <h1>Data Pencatatan Kasus</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active">Pencatatan Kasus</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->
  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                      <h5 class="card-title">Pencatatan Kasus List</h5>
                    </div>
                    <div class="col-md-8 mt-2">
                        <div class="d-flex justify-content-end">
                            @can('legal-case-create')
                                <a href="{{ route('legal_case.create') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-plus-circle-fill"></i>  Tambah Pencatatan Kasus
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <!-- Table with stripped rows -->
                    <table class="table table-striped" id="data-table">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">No. Perkara</th>
                            <th scope="col">Klarifikasi Perkara</th>
                            <th scope="col">Para Pihak</th>
                            <th scope="col">Tanggal Sidang</th>
                            <th scope="col">Keterangan</th>
                            <th scope="col">Mediator</th>
                            <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($legalCases as $legalCase)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$legalCase->case_number}}</td>
                                    <td>{{$legalCase->clarification}}</td>
                                    <td>
                                        <strong>Penggugat</strong>
                                        {!! $legalCase->getPlaintiffNamesHtml() !!}
                                        
                                        <strong>Tergugat</strong>
                                        {!! $legalCase->getDefendantNamesHtml() !!}
                                    </td>
                                    <td>{{Carbon\Carbon::parse($legalCase->trial_date)->translatedFormat('l, d F Y')}}</td>
                                    <td>{{$legalCase->notes}}</td>
                                    <td>{{$legalCase->mediator}}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="bi bi-gear-fill"></i>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                @can('legal-case-edit')
                                                <a class="dropdown-item edit" href="{{ route('legal_case.edit', $legalCase->id) }}">
                                                    <em class="bi bi-pencil-fill open-card-option"></em>
                                                        Edit
                                                </a>
                                                @endcan
                                                @can('legal-case-list')
                                                <a class="dropdown-item detail" href="#" data-url="{{route('legal_case.show', $legalCase->id)}}">
                                                    <em class="bi bi-eye-fill close-card"></em>
                                                    Detail
                                                </a>
                                                @endcan
                                                @can('legal-case-delete')
                                                <a class="dropdown-item delete" href="#" data-id="{{$legalCase->id}}" data-url-destroy="{{route('legal_case.destroy', $legalCase->id)}}">
                                                    <em class="bi bi-trash-fill close-card"></em>
                                                    Delete
                                                </a>
                                                @endcan
                                              
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- End Table with stripped rows -->
                </div>
            </div>
        </div>
      </div>
    </div>
    @include('backEnd.legalCase.show')
  </section>
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            $('#data-table tbody').on('click', '.delete', function () {
                var id = $(this).data('id');
                var url = $(this).data('url-destroy');
                Swal.fire({
                    title: "Are you sure delete it?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes",
                }).then((result) => {
                    if (result.isConfirmed){
                        $.ajax({
                            url: url,
                            type: "DELETE",
                            data: {
                                "_token": "{{ csrf_token() }}",
                            },
                            success: function (response) {
                                if(response.status){
                                    Swal.fire("Done!", "It was succesfully deleted!", "success").then(function(){
                                        location.reload();
                                    });
                                }else{
                                    Swal.fire("Error deleting!", "Please try again", "error").then(function(){
                                        location.reload();
                                    });
                                }
                            },
                            error: function (xhr, ajaxOptions, thrownError) {
                                Swal.fire("Error deleting!", "Please try again", "error").then(function(){
                                    location.reload();
                                });
                        }
                        });
                    }
                });
            });

            $('#data-table tbody').on('click', '.detail', function () {
                let url = $(this).data('url');
                $.ajax({
                    url: url,
                    type: "GET",
                    success: function (response) {
                        if(response.status){
                            let data = response.data;
                            $("#case_number").text(data.case_number);
                            $("#clarification").text(data.clarification);
                            $("#trial_date").text(data.trial_date);
                            $("#mediator").text(data.mediator);
                            $("#notes").text(data.notes);
                            $("#description").text(data.description);
                            $("#url_sk").attr('href', response.file_sk);
                            $("#url_gugat").attr('href', response.file_suit);
                            $("#url_bukti").attr('href', response.file_proof);
                            $("#data_penggugat").html(response.penggugat);
                            $("#data_tergugat").html(response.tergugat);
                            $("#modal-show").modal('show');
                        
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        Swal.fire("Error Data Not Found!", "Please try again", "error").then(function(){
                            location.reload();
                        });
                    }
                });
            });
        });
    </script>
@endpush
