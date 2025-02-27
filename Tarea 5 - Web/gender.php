<?php include 'header.php'; ?>
<div class="row justify-content-center">
  <div class="col-md-8">
    <h1 class="mb-4 text-center">Predicción de Género</h1>
    <form method="GET" class="mb-4">
      <div class="mb-3">
        <label for="name" class="form-label">Ingrese un nombre:</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Ejemplo: Maria" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Predecir Género</button>
    </form>
    <?php
    if(isset($_GET['name'])){
      $name = urlencode(trim($_GET['name']));
      $url = "https://api.genderize.io/?name=".$name;
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Deshabilita la verificación SSL para pruebas
      $response = curl_exec($ch);
      if(curl_errno($ch)){
          echo '<div class="alert alert-danger">Error al conectar con la API: ' . curl_error($ch) . '</div>';
      }
      else {
          $data = json_decode($response, true);
          if(isset($data['gender'])){
              $gender = $data['gender'];
              echo '<div class="card shadow-sm mb-4"><div class="card-body text-center">';
              if($gender == 'male'){
                  echo '<h2 class="blue">Masculino 🔵</h2>';
              } elseif($gender == 'female'){
                  echo '<h2 class="pink">Femenino 🟣</h2>';
              } else {
                  echo '<h2>No se pudo determinar el género.</h2>';
              }
              echo '</div></div>';
          } else {
              echo '<div class="alert alert-warning">No se recibió una respuesta válida de la API.</div>';
          }
      }
      curl_close($ch);
    }
    ?>
  </div>
</div>
<?php include 'footer.php'; ?>
