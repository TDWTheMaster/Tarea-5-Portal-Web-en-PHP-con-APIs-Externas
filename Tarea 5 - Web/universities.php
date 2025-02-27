<?php include 'header.php'; ?>
<div class="row justify-content-center">
  <div class="col-md-8">
    <h1 class="mb-4 text-center">Universidades de un País</h1>
    <form method="GET" class="mb-4">
      <div class="mb-3">
        <label for="country" class="form-label">Ingrese el nombre del país (en inglés):</label>
        <input type="text" class="form-control" id="country" name="country" placeholder="Ejemplo: Dominican Republic" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Buscar Universidades</button>
    </form>
    <?php
    if(isset($_GET['country'])){
        $country = urlencode(trim($_GET['country']));
        $url = "http://universities.hipolabs.com/search?country=".$country;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Deshabilita la verificación SSL para pruebas
        $response = curl_exec($ch);
        if(curl_errno($ch)){
            echo '<div class="alert alert-danger">Error al conectar con la API.</div>';
        } else {
            $data = json_decode($response, true);
            if(is_array($data) && count($data) > 0){
                echo '<ul class="list-group">';
                foreach($data as $uni){
                    $name = $uni['name'];
                    $domains = implode(", ", $uni['domains']);
                    echo '<li class="list-group-item">';
                    echo "<h5>$name</h5>";
                    echo "<p>Dominio: $domains</p>";
                    echo "<p>Página web: <a href='{$uni['web_pages'][0]}' target='_blank'>{$uni['web_pages'][0]}</a></p>";
                    echo '</li>';
                }
                echo '</ul>';
            } else {
                echo '<div class="alert alert-warning">No se encontraron universidades para este país.</div>';
            }
        }
        curl_close($ch);
    }
    ?>
  </div>
</div>
<?php include 'footer.php'; ?>
