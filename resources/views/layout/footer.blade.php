<footer class="main-footer">
    <div class="float-right d-none d-sm-block">
        <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; 2021 <a href="#">DEVELOPMENT TEAM</a>.</strong> All rights reserved.
</footer>

{{-- <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside> --}}

<!-- /.control-sidebar -->
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('template') }}/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('template') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('template') }}/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="{{ asset('template') }}/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('template') }}/dist/js/demo.js"></script>
<script>
    $(document).ready(function() {
        $divisi = $('.adduserdivisi');
        $approval = $('.adduserapproval');
        $divisi.append('<option value="">---Choose Area First--</option>');
        $approval.append('<option value="">---Choose Division First--</option>');
        $('.adduserarea').change(function(e) {
            var $areaId = $(".adduserarea").val();
            if ($areaId === "") {
                $divisi.empty();
                $divisi.append('<option value="">---Choose Area First--</option>');
                $approval.empty();
                $approval.append('<option value="">---Choose Division First--</option>');
            } else {
                $divisi.empty();
                $divisi.append('<option value="">---Choose Division--</option>');
                $approval.empty();
                $approval.append(
                    '<option value="">---Choose Division First--</option>');
                $.ajax({
                    type: "GET",
                    url: "{{ url('divisi/get') }}/" + $areaId,
                    success: function(data) {
                        $.each(data, function(index, value) {
                            $divisi.append('<option value="' + value.id + '">' +
                                value
                                .name + '</option>');
                        });
                    }
                });
            }

        });

        $divisi.change(function(e) {
            var $areaId = $(".adduserarea").val();
            if ($divisi.val() === '' || $areaId === '') {
                $approval.empty();
                $approval.append('<option value="">---Choose Division First--</option>');
            } else {
                $.ajax({
                    type: "GET",
                    url: "{{ url('approval/get') }}?areaid=" + $areaId+"&divisiid="+$divisi.val(),
                    success: function(data) {
                      console.log("{{ url('approval/get') }}?areaid=" + $areaId+"&divisiid="+$divisi.val());
                        $approval.empty();
                        $approval.append(
                            '<option value="">---Choose Approval Person--</option>');
                        $.each(data, function(index, value) {
                            $approval.append('<option value="' + value.id + '">' +
                                value
                                .nama_lengkap + '</option>');
                        });
                    }
                });
            }
        });
    });
</script>
</body>

</html>
