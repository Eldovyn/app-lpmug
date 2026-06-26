  <!-- General JS Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.3.1/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery.nicescroll@3.7.6/dist/jquery.nicescroll.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.24.0/min/moment.min.js"></script>

  <script src="<?=base_url()?>template/assets/js/stisla.js"></script> 
  
  <!-- JS Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/jquery-ui-dist@1.12.1/jquery-ui.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/cleave.js@1.4.7/dist/cleave.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/cleave.js@1.4.7/dist/addons/cleave-phone.us.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-daterangepicker@3.0.3/daterangepicker.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-colorpicker@3.0.3/dist/js/bootstrap-colorpicker.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-timepicker@0.5.2/js/bootstrap-timepicker.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-tagsinput@0.7.1/dist/bootstrap-tagsinput.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.0.6-rc.1/dist/js/select2.full.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/selectric@1.13.0/public/jquery.selectric.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/prismjs@1.15.0/prism.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.pwstrength/2.0.3/jquery.pwstrength.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.11/dist/summernote-bs4.min.js"></script>

  <!--<script src="<?=base_url()?>/template/assets/js/popper.min.js"></script>-->
  <!--<script src="<?=base_url()?>/template/assets/js/bootstrap.min.js"></script>-->
  <script src="<?=base_url()?>template/assets/js/datatables.min.js"></script>
  <script src="<?=base_url()?>template/assets/js/dataTables.bootstrap4.js"></script>
  <?php if (url_is('rekapan*')): ?>
  <script src="<?=base_url()?>template/assets/js/dataTables.buttons.js"></script>
  <script src="<?=base_url()?>template/assets/js/buttons.bootstrap4.js"></script>
  <script src="<?=base_url()?>template/assets/js/jszip.min.js"></script>
  <script src="<?=base_url()?>template/assets/js/pdfmake.min.js"></script>
  <script src="<?=base_url()?>template/assets/js/vfs_fonts.js"></script>
  <script src="<?=base_url()?>template/assets/js/buttons.html5.min.js"></script>
  <script src="<?=base_url()?>template/assets/js/buttons.print.min.js"></script>
  <?php endif; ?>
  
  <!-- Template JS File -->
  <script src="<?=base_url()?>template/assets/js/scripts.js?v=<?= time() ?>"></script>
  
  <!-- SweetAlert2 Premium Modals -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
  <script src="<?=base_url()?>template/assets/js/custom.js"></script>

  <!-- Page Specific JS File -->
  <script src="<?=base_url()?>template/assets/js/page/components-table.js"></script>
  <!-- <script src="<?=base_url()?>/template/assets/js/page/forms-advanced-forms.js"></script> -->
  <!-- <script src="<?=base_url()?>/template/assets/js/page/modules-datatables.js"></script> -->
  <script src="<?=base_url()?>template/assets/js/page/bootstrap-modal.js"></script>
  
<script>
  $(document).ready(function() {
    $('.nav-item.dropdown > a.nav-link.has-dropdown').off('click').on('click', function(e) {
      e.preventDefault();
      var $parent = $(this).parent();

      if ($parent.hasClass('active')) {
        $parent.removeClass('active');
        $parent.find('.dropdown-menu').slideUp(200);
      } else {
        $('.nav-item.dropdown').removeClass('active').find('.dropdown-menu').slideUp(200);
        $parent.addClass('active');
        $parent.find('.dropdown-menu').slideDown(200);
      }
    });
  });
</script>
</body>
</html>


<!-- ========================================================== -->
<!-- Credential Author -->
<!--
Author    : WENDY
Project   : Lembaga Pengabdian Masyarakat Universitas Gunadarma
-->
<!-- ========================================================== -->