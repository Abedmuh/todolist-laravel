@extends('layouts.main')

@section('content')
<main class="container">
  <!-- START DATA -->
  <div class="my-3 p-3 bg-body rounded shadow-sm">
    <!-- TOMBOL TAMBAH DATA -->
    <div class="pb-3">
      <a href='' class="btn btn-primary tombol-tambah">+ Tambah Data</a>
    </div>
    <table class="table table-striped" id="myTable">
      <thead>
        <tr>
          <th class="col-md-1">No</th>
          <th class="col-md-2">Nama</th>
          <th class="col-md-5">Todo</th>
          <th class="col-md-2">Last Update</th>
          <th class="col-md-2">Aksi</th>
        </tr>
      </thead>
    </table>

  </div>
  <!-- AKHIR DATA -->
</main>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Form</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- START FORM -->
        <div class="alert alert-danger d-none"></div>
        <div class="alert alert-success d-none"></div>

        <div class="mb-3 row">
          <label for="nama" class="col-sm-2 col-form-label">Nama</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" name='nama' id="nama">
          </div>
        </div>
        <div class="mb-3 row">
          <label for="todo" class="col-sm-2 col-form-label">Todo</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" name='todo' id="todo">
          </div>
        </div>
        <!-- AKHIR FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary tombol-simpan">Simpan</button>
        <button type="button" class="btn btn-primary tombol-update">Update</button>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous">
</script>
<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
  crossorigin="anonymous"></script>
<script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function() {
      $('#myTable').DataTable({
        processing: true,
        serverside: true,
        ajax: "{{ url('todolist') }}",
        columns: [{
            data: 'DT_RowIndex',
            name: 'DT_RowIndex',
            orderable: false,
            searchable: false
        }, {
            data: 'name',
            name: 'Name'
        }, {
            data: 'todo',
            name: 'Todo'
        },{
            data: 'updated_at',
            name: 'Last Update'
        }, {
            data: 'action',
            name: 'action'
        }]
      });
  });

  // GLOBAL SETUP 
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });

  // 02_PROSES SIMPAN 
  $('body').on('click', '.tombol-tambah', function(e) {
      e.preventDefault();
      $('#exampleModal').modal('show');
      $('.tombol-simpan').show();
      $('.tombol-update').hide();
      $('.tombol-simpan').click(function() {
        $.ajax({
          url: 'todolist',
          type: 'POST',
          data: {
              name: $('#nama').val(),
              todo: $('#todo').val()
          },
          success: function(response) {
              $('.alert-success').removeClass('d-none');
              $('.alert-success').html(response.success);
              $('#myTable').DataTable().ajax.reload();
              console.log(response.success);
              $('#exampleModal').modal('hide');
          }
        });
      });
  });

  // 03_PROSES EDIT 
  $('body').on('click', '.tombol-edit', function(e) {
    var id = $(this).data('id');
    $('#exampleModal').modal('show');
    $('.tombol-simpan').hide();
    $('.tombol-update').show();
    $.ajax({
      url: 'todolist/' + id + '/edit',
      type: 'GET',
      success: function(response) {
        $('#exampleModal').modal('show');
        console.log(response.result);
        $('#nama').val(response.result.name);
        $('#todo').val(response.result.todo);
        $('.tombol-update').click(function() {
          $.ajax({
            url: 'todolist/' + id,
            type: 'PUT',
            data: {
                name: $('#nama').val(),
                todo: $('#todo').val()
            },
            success: function(response) {
                $('.alert-success').removeClass('d-none');
                $('.alert-success').html(response.success);
                $('#myTable').DataTable().ajax.reload();
                $('#exampleModal').modal('hide');
            }
          });
        });
      }
    });
  });

  // 04_PROSES Delete 
  $('body').on('click', '.tombol-del', function(e) {
      if (confirm('Hapus data?') == true) {
          var id = $(this).data('id');
          $.ajax({
              url: 'todolist/' + id,
              type: 'DELETE',
          });
          $('#myTable').DataTable().ajax.reload();
      }
  });

  $('#exampleModal').on('hidden.bs.modal', function() {
      $('#nama').val('');
      $('#todo').val('');

      $('.alert-danger').addClass('d-none');
      $('.alert-danger').html('');

      $('.alert-success').addClass('d-none');
      $('.alert-success').html('');
  });
</script>
@endsection