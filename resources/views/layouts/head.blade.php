<title>@yield('title')</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
<style>
    .general-text {
  font-size: 20px; /* Change the font size to 20 pixels */
  text-decoration: underline; /* Add underline to the text */

}
.table-rounded {
    border-radius: 100px; /* Adjust the value as needed */
}
</style>
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<!-- daterangepicker CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<!-- Custom CSS for the date range picker -->
<style>
    .daterangepicker {
        border-radius: 4px;
        color: hsl(0, 0%, 20%);
        font-size: 14px;
    }

    .daterangepicker .ranges ul li.active {
        background-color: #007bff;
        color: white;
    }

    .daterangepicker .drp-calendar {
        border: none;
    }

    .daterangepicker .drp-calendar.left {
        border-right: none;
    }
    /* Masquer le texte "Logout" par défaut */
.logout-text {
    display: none;
}

/* Afficher le texte "Logout" lors du hover sur le lien */
.logout-link:hover .logout-text {
    display: inline;
}

</style>
@yield('css')
