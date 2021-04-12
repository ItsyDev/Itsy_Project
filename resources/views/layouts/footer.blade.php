<div class="footer transition">
  <hr>
  <p>
    &copy; 2020 All Right Reserved by <a href="#" target="_blank">ITSYadmin</a>
  </p>
</div>

<div class="modal fade" id="isLogout">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body text-secondary">
        <h4>Anda yakin ingin logout?</h4>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <a href="{{ route("logout") }}" class="btn btn-danger btn-sm">Logout</a>
        <button type="button" class="btn btn-success btn-sm" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>