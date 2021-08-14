 <!-- General JS Scripts -->
  <script src="<?= base_url().JSPATH ?>/app.min.js"></script>
  <!-- JS Libraies -->
  <!-- Page Specific JS File -->
  <!-- Template JS File -->
  <script src="<?= base_url().JSPATH ?>/scripts.js"></script>
  <!-- Custom JS File -->
  <script src="<?= base_url().JSPATH ?>/custom.js"></script>
    <!-- JS Libraies -->
  <script src="<?= base_url().JSLIB ?>/apexcharts/apexcharts.min.js"></script>
  <script src="<?= base_url().JSPATH ?>/page/index.js"></script>
  <!-- JS Libraies -->
  <script src="<?= base_url().JSLIB ?>/datatables/datatables.min.js"></script>
  <script src="<?= base_url().JSLIB ?>/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
  <script src="<?= base_url().JSLIB ?>/jquery-ui/jquery-ui.min.js"></script>
  <!-- Page Specific JS File -->
  <script src="<?= base_url().JSPATH ?>/page/datatables.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script>
    var sports = {
        config: {
        register: "<?php echo base_url("/home/registeradmin"); ?>",
        login: "<?php echo base_url("/home/autenticate"); ?>",
        countries: "<?php echo base_url("/admin/locationscontroller/getallcountry") ?>",
        state: "<?php echo base_url("/admin/locationscontroller/getallstate") ?>",
        city: "<?php echo base_url("/admin/locationscontroller/getallcity") ?>",
        add_club : "<?php echo base_url("/admin/clubs/add_club") ?>", 
        delete_club : "<?php echo base_url("/admin/clubs/delete_club") ?>", 
        get_club : "<?php echo base_url("/admin/clubs/get_club_details") ?>",
        edit_club : "<?php echo base_url("/admin/clubs/edit_club") ?>", 
        view_members : "<?php echo base_url("/admin/clubs/view_members") ?>", 
		add_league : "<?php echo base_url("/admin/league/add_league") ?>", 
        delete_league : "<?php echo base_url("/admin/league/delete_league") ?>", 
        get_league : "<?php echo base_url("/admin/league/get_league_details") ?>",
        edit_league : "<?php echo base_url("/admin/league/edit_league") ?>",
		add_team : "<?php echo base_url("/admin/team/add_team") ?>", 
        delete_team : "<?php echo base_url("/admin/team/delete_team") ?>", 
        get_team : "<?php echo base_url("/admin/team/get_team_details") ?>",
        edit_team : "<?php echo base_url("/admin/team/edit_team") ?>",
        base_url: "<?php echo base_url(); ?>"
        }
    }
  </script>