@extends('backEnd.layouts.main')
@section('title', 'User - '.config('app.name'))
@section('content')
<div class="pagetitle">
    <h1>Data User</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
        <li class="breadcrumb-item">User Management</li>
        <li class="breadcrumb-item active">User</li>
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
                      <h5 class="card-title">User List</h5>
                    </div>
                    <div class="col-md-8 mt-2">
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-outline-info me-2" id="btn-struktur">
                                <i class="bi bi-person-lines-fill"></i>  Struktur Organisasi
                            </button>
                            @can('user-create')
                                <button class="btn btn-outline-primary"  data-bs-toggle="modal" data-bs-target="#modal-create">
                                    <i class="bi bi-plus-circle-fill"></i>  Create User
                                </button>
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
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Role</th>
                            <th scope="col">Tanggal Buat</th>
                            <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$user->name}}</td>
                                    <td>{{$user->email}}</td>
                                    <td>{{$user->roles->pluck('name')[0] ?? '-'}}</td>
                                    <td>{{$user->translated_date}}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="bi bi-gear-fill"></i>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                @can('user-edit')
                                                    <a class="dropdown-item edit" href="#"  data-id="{{$user->id}}" data-url-update="{{route('user.update', $user->id)}}" data-url="{{route('user.show', $user->id)}}">
                                                    <em class="bi bi-pencil-fill open-card-option"></em>
                                                        Edit
                                                </a>
                                                @endcan
                                                @can('user-delete')
                                                <a class="dropdown-item delete" href="#" data-id="{{$user->id}}" data-url-destroy="{{route('user.destroy', $user->id)}}">
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
  </section>
  @include('backEnd.userManagement.user.create')
  @include('backEnd.userManagement.user.struktur')
  @include('backEnd.userManagement.user.edit')
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            @if (count($errors) > 0)
                $('#modal-create').modal('show');
            @endif
            $('#data-table tbody').on('click', '.edit', function () {
                var id = $(this).data('id');
                var url = $(this).data('url-update');
                var url_hit = $(this).data('url');
                $.ajax({
                    url: url_hit,
                    type: 'GET',
                }).done(function (response) {
                    if(response.status){
                        $('#name_edit').val(response.data.name);
                        $('#username_edit').val(response.data.username);
                        $('#email_edit').val(response.data.email);
                        $('#password_edit').val(response.data.password);
                        let option_role = "";
                        for (let i = 0; i < response.roles.length; i++) {
                            let selected_role = response.roles[i].selected ? "selected='"+response.roles[i].selected+"'" : ""
                            option_role += "<option value='"+response.roles[i].id+"' "+selected_role+">"+response.roles[i].name+"</option>";
                        }
                        $('#role_id_edit').html(option_role);
                        $("#form-edit").attr('action', url);
                        $('#modal-edit').modal('show');
                    }
                })
                .fail(function () {
                    console.log("error");
                });
            });
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
            $('#btn-struktur').on('click', function() {
                $.ajax({
                    url: "{{route('user.getStruktur')}}",
                    type: "GET",
                    success: function (response) {
                        if(response.status){
                            $("#struktur").html(response.data);
                            $("#modal-struktur").modal('show');
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        // Swal.fire("Error!", "Please try again", "error").then(function(){
                        //     location.reload();
                        // });
                        Swal.fire("Error!", "Please try again", "error");
                    }
                });
            });
        });
    </script>
@endpush
