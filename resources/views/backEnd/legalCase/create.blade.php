@extends('backEnd.layouts.main')
@section('title', 'Tambah Pencatatan Kasus - '.config('app.name'))
@section('content')
<div class="pagetitle">
    <h1>Tambah Pencatatan Kasus</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active">Tambah Pencatatan Kasus</li>
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
                      <h5 class="card-title">Form Pencatatan Kasus</h5>
                    </div>
                    <div class="col-md-8 mt-2">
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('legal_case.index') }}" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-left-circle-fill"></i>  Kembali
                            </a>
                        </div>
                    </div>
                </div>
                <form action="{{route('legal_case.store')}}" method="POST" enctype="multipart/form-data" id="form-create">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <label for="case_number" class="form-label">Nomor Perkara <span class="text-red">*</span></label>
                            <input type="text" class="form-control  @error('case_number') is-invalid @enderror" name="case_number" id="case_number" autocomplete="off" placeholder="Nomor Perkara">
                            @error('case_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="clarification" class="form-label">Klarifikasi Perkara <span class="text-red">*</span></label>
                            <input type="text" class="form-control  @error('clarification') is-invalid @enderror"  name="clarification" id="clarification" autocomplete="off" placeholder="Klarifikasi Perkara">
                            @error('clarification')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="trial_date" class="form-label">Tanggal Sidang <span class="text-red">*</span></label>
                            <input type="date" class="form-control @error('trial_date') is-invalid @enderror" name="trial_date" id="trial_date" autocomplete="off" placeholder="Tanggal Sidang">
                            @error('trial_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <h3 class="card-title">Data Penggugat</h3>
                            <div class="table-responsive">
                                <table class="table table-bordered fs-6">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Nama <span class="text-red">*</span></th>
                                            <th>Tempat Lahir <span class="text-red">*</span></th>
                                            <th>Tanggal Lahir <span class="text-red">*</span></th>
                                            <th><button type="button" id="btn-tambah-penggugat" class="btn btn-primary btn-sm"><i class="bi bi-plus"></i> Tambah</button></th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-penggugat">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <h3 class="card-title">Data Tergugat</h3>
                            <div class="table-responsive">
                                <table class="table table-bordered fs-6">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Nama <span class="text-red">*</span></th>
                                            <th>Tempat Lahir <span class="text-red">*</span></th>
                                            <th>Tanggal Lahir <span class="text-red">*</span></th>
                                            <th><button type="button" id="btn-tambah-tergugat" class="btn btn-primary btn-sm"><i class="bi bi-plus"></i> Tambah</button></th>
                                        </tr>
                                        <tbody id="tbody-tergugat">

                                        </tbody>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="mediator" class="form-label">Mediator <span class="text-red">*</span></label>
                            <select name="mediator" id="mediator" class="form-control" data-selectjs="true" data-placeholder="Mediator">
                                <option value="" selected disabled>Pilih Mediator</option>
                                <option value="Mediator 1">Mediator 1</option>
                                <option value="Mediator 2">Mediator 2</option>
                                <option value="Mediator 3">Mediator 3</option>
                            </select>
                            @error('mediator')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12 my-4">
                            <label for="notes" class="form-label">Keterangan Kasus <span class="text-red">*</span></label>
                            <textarea name="notes" id="notes" cols="30" rows="5" class="form-control @error('notes') is-invalid @enderror" placeholder="Keterangan Kasus"></textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label for="description" class="form-label">Deskripsi Kasus</label>
                            <textarea name="description" id="description" cols="30" rows="5" class="form-control" placeholder="Deskripsi"></textarea>
                        </div>
                        <div class="col-md-4 my-4">
                            <label for="file_sk" class="form-label">SK</label>
                            <input type="file" class="form-control" name="file_sk" id="file_sk">
                           
                        </div>
                        <div class="col-md-4 my-4">
                            <label for="file_suit" class="form-label">Gugatan</label>
                            <input type="file" class="form-control"  name="file_suit" id="file_suit">
                        </div>
                        <div class="col-md-4 my-4">
                            <label for="file_proof" class="form-label">Bukti</label>
                            <input type="file" class="form-control @error('file_proof') is-invalid @enderror" name="file_proof" id="file_proof">
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary float-end" id="btn-save" disabled>Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
      </div>
    </div>
  </section>
@endsection
@push('scripts')
    <script src="{{ asset("assets/js/validation-js/legal-case.js") }}"></script>
    <script>
       $(document).ready(function() {
            $('#btn-tambah-penggugat').click(function() {
                let row = `
                    <tr>
                        <td class="counter_penggugat"></td>
                        <td><input type="text" class="form-control" name="name_penggugat[]" autocomplete="off" placeholder="Nama"></td>
                        <td><input type="text" class="form-control" name="place_of_birth_penggugat[]" autocomplete="off" placeholder="Tempat Lahir"></td>
                        <td><input type="date" class="form-control" name="date_of_birth_penggugat[]"></td>
                        <td><button class="btn btn-danger btn-sm btn-remove"><i class="bi bi-trash"></i> Hapus</button></td>
                    </tr>
                `;
                $('#tbody-penggugat').append(row);
                updateCounterPenguggat();
                toggleSaveButton();
            });

            $('#tbody-penggugat').on('click', '.btn-remove', function() {
                $(this).closest('tr').remove();
                updateCounterPenguggat();
                toggleSaveButton();
            });

            $('#btn-tambah-tergugat').click(function() {
                let row = `
                    <tr>
                        <td class="counter_tergugat"></td>
                        <td><input type="text" class="form-control" name="name_tergugat[]" autocomplete="off" placeholder="Nama"></td>
                        <td><input type="text" class="form-control" name="place_of_birth_tergugat[]" autocomplete="off" placeholder="Tempat Lahir"></td>
                        <td><input type="date" class="form-control" name="date_of_birth_tergugat[]"></td>
                        <td><button class="btn btn-danger btn-sm btn-remove"><i class="bi bi-trash"></i> Hapus</button></td>
                    </tr>
                `;
                $('#tbody-tergugat').append(row);
                updateCounterTergugat();
                $("#btn-save").attr('disabled', false);
                toggleSaveButton();
            });

            $('#tbody-tergugat').on('click', '.btn-remove', function() {
                $(this).closest('tr').remove();
                updateCounterTergugat();
                toggleSaveButton();
            });
        });

        function updateCounterPenguggat() {
            let counterPenguggat  = 1;
            $('#tbody-penggugat tr').each(function() {
                $(this).find('.counter_penggugat').text(counterPenguggat);
                counterPenguggat++;
            });
        }

        function updateCounterTergugat() {
            let counterTergugat  = 1;
            $('#tbody-tergugat tr').each(function() {
                $(this).find('.counter_tergugat').text(counterTergugat);
                counterTergugat++;
            });
        }

        function toggleSaveButton() {
            if ($('#tbody-penggugat tr').length === 0 || $('#tbody-tergugat tr').length === 0) {
                $("#btn-save").attr('disabled', true);
            } else {
                $("#btn-save").attr('disabled', false);
            }
            }
    </script>
@endpush
