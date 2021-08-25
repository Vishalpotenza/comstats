<!DOCTYPE html>

<html lang="en">





<!-- auth-login.html  21 Nov 2019 03:49:32 GMT -->

<head>

  <meta charset="UTF-8">

  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">

  <title><?php echo (isset($title) ? $title : '');  ?></title>

  <!-- General CSS Files -->

  <link rel="stylesheet" href="<?= base_url().CSSPATH ?>/app.min.css">

  <!-- <link rel="stylesheet" href="assets/bundles/bootstrap-social/bootstrap-social.css"> -->

  <!-- Template CSS -->

  <link rel="stylesheet" href="<?= base_url().CSSPATH ?>/style.css">

  <link rel="stylesheet" href="<?= base_url().CSSPATH ?>/components.css">

  

  <link rel="stylesheet" href="<?= base_url().JSLIB ?>/datatables/datatables.min.css">

  <link rel="stylesheet" href="<?= base_url().JSLIB ?>/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">

  <!-- Custom style CSS -->

  <link rel="stylesheet" href="<?= base_url().CSSPATH ?>/custom.css">

  <link rel='shortcut icon' type='image/x-icon' href="<?= base_url().IMGPATH ?>/favicon.ico" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<script>
var base_url = "<?php echo base_url(); ?>";
</script>
<input type="hidden" name="base_url" id="base_url" value="<?php echo base_url(); ?>" />
			<input type="hidden" name="profile_images_path" id="profile_images_path" value="<?php echo base_url().'/public/uploads/profile_images/'; ?>" />