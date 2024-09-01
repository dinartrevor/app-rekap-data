<div class="modal fade" id="modal-show" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Detail Pencatatan Kasus</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
           <div class="row">
                <div class="col-lg-6">
                    <table class="table table-bordered">
                        <tr>
                            <th>No. Perkara</th>
                            <td id="case_number"></td>
                        </tr>
                        <tr>
                            <th>Klarifikasi Perkara</th>
                            <td id="clarification"></td>
                        </tr>
                        <tr>
                            <th>Tanggal Sidang</th>
                            <td id="trial_date"></td>
                        </tr>
                        <tr>
                            <th>Mediator</th>
                            <td id="mediator"></td>
                        </tr>
                        <tr>
                            <th>Keterangan Kasus</th>
                            <td id="notes"></td>
                        </tr>
                        <tr>
                            <th>Deskripsi</th>
                            <td id="description"></td>
                        </tr>
                    </table>
                  
                </div>
                <div class="col-md-6 my-2">
                    <table class="table table-bordered">
                        <tr>
                            <th>File SK</th>
                            <th><a href="" id="url_sk" class="btn btn-info btn-sm" target="_blank"><i class="bi bi-eye-fill"></i> Lihat File</a></th>
                        </tr>
                        <tr>
                            <th>File Gugat</th>
                            <th><a href="" id="url_gugat" class="btn btn-info btn-sm" target="_blank"><i class="bi bi-eye-fill"></i> Lihat File</a></th>
                        </tr>
                        <tr>
                            <th>File Bukti</th>
                            <th><a href="" id="url_bukti" class="btn btn-info btn-sm" target="_blank"><i class="bi bi-eye-fill"></i> Lihat File</a></th>
                        </tr>
                    </table>
                    <strong>Penggugat</strong>
                    <div id="data_penggugat"></div>

                    <strong>Tergugat</strong>
                    <div id="data_tergugat"></div>
                </div>
           </div>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
