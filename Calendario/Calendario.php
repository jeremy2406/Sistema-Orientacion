<?php include '../Componentes/header.php'; ?>
<?php include '../Componentes/Nav.php'; ?>


<link rel="stylesheet" href="<?= BASE_URL ?>Css/Calendario.css">
<link rel="stylesheet" href="<?= BASE_URL ?>Css/Estilos.css">


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mt-4">
    <div class="calendar-header">
        <h2 class="calendar-title">.</h2>
    </div>
    <div id='calendar'></div>
</div>

<style>
    a {
        color: black;
    }
</style>

<script src='../Js/calendar.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js'></script>


<?php include '../Componentes/footer.php'; ?>