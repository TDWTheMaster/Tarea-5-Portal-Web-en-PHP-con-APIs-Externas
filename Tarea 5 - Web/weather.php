<?php include 'header.php'; ?>
<div class="row justify-content-center">
  <div class="col-md-8">
    <h1 class="mb-4 text-center">Clima en República Dominicana</h1>
    <form method="GET" class="mb-4">
      <div class="mb-3">
        <label for="city" class="form-label">Ingrese el nombre de la ciudad:</label>
        <input type="text" class="form-control" id="city" name="city" placeholder="Ejemplo: Santo Domingo" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Buscar Clima</button>
    </form>

    <?php
    if (isset($_GET['city'])) {
        $city = urlencode(trim($_GET['city']));
        $country = urlencode("Dominican Republic");
        $geo_url = "https://nominatim.openstreetmap.org/search?city={$city}&country={$country}&format=json";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $geo_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MiApp/1.0; +http://tusitio.com)');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $geo_response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo '<div class="alert alert-danger">Error al conectar con la API de geolocalización: ' . curl_error($ch) . '</div>';
        } else {
            $geo_data = json_decode($geo_response, true);
            if (is_array($geo_data) && count($geo_data) > 0) {
                $lat = $geo_data[0]['lat'];
                $lon = $geo_data[0]['lon'];
                $weather_url = "https://api.open-meteo.com/v1/forecast?latitude={$lat}&longitude={$lon}&current_weather=true";

                curl_setopt($ch, CURLOPT_URL, $weather_url);
                $weather_response = curl_exec($ch);

                if (curl_errno($ch)) {
                    echo '<div class="alert alert-danger">Error al conectar con la API de clima: ' . curl_error($ch) . '</div>';
                } else {
                    $weather_data = json_decode($weather_response, true);
                    if (isset($weather_data['current_weather'])) {
                        $temp = $weather_data['current_weather']['temperature'];
                        $weathercode = $weather_data['current_weather']['weathercode'];

                        $icon = match (true) {
                            $weathercode == 0 => "☀️",
                            $weathercode >= 1 && $weathercode <= 3 => "⛅",
                            $weathercode >= 45 && $weathercode < 67 => "🌧️",
                            default => "☁️"
                        };
                        ?>
                        <div class="card shadow mt-4">
                            <div class="card-body text-center">
                                <h2 class="mb-3">Clima en <?= htmlspecialchars($_GET['city']) ?></h2>
                                <p class="display-4"><?= $temp ?>°C <?= $icon ?></p>
                                <p class="text-muted">Información proporcionada por Open-Meteo</p>
                            </div>
                        </div>
                        <?php
                    } else {
                        echo '<div class="alert alert-warning">No se recibió información del clima.</div>';
                    }
                }
            } else {
                echo '<div class="alert alert-warning">No se pudo encontrar la ciudad en República Dominicana.</div>';
            }
        }
        curl_close($ch);
    }
    ?>
  </div>
</div>
<?php include 'footer.php'; ?>
