   <?php
    // Detectar la página actual
    $current_page = basename($_SERVER['PHP_SELF']);
    ?>

   <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
       <div class="container">
           <a class="navbar-brand" href="./index.php"><img src="./img/logo.png" style=width:48px;> Sistema Traumatología</a>
           <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
               <span class="navbar-toggler-icon"></span>
           </button>
           <div class="collapse navbar-collapse" id="navbarNav">
               <ul class="navbar-nav">
                   <li class="nav-item">
                       <a class="nav-link <?= $current_page == 'index.php' ? 'active' : '' ?>" href="./index.php">Inicio</a>
                   </li>
                   <li class="nav-item">
                       <a class="nav-link <?= $current_page == 'registrar.php' ? 'active' : '' ?>" href="./registrar.php">Registrar Accidente</a>
                   </li>
                   <li class="nav-item">
                       <a class="nav-link <?= $current_page == 'pacientes.php' ? 'active' : '' ?>" href="./pacientes.php">Lista de accidentados</a>
                   </li>
                   <li class="nav-item">
                       <a class="nav-link <?= $current_page == 'estadisticas.php' ? 'active' : '' ?>" href="estadisticas.php">Estadisticas</a>
                   </li>
               </ul>
           </div>
       </div>
   </nav>