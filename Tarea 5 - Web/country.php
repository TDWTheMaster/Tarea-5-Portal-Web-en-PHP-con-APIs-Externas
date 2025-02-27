<?php include 'header.php'; ?>

<div class="container">
    <h1 class="mb-4 text-center">🌍 Datos de un País</h1>
    
    <form method="GET" class="mb-5">
        <div class="row g-3">
            <div class="col-md-9">
                <input type="text" 
                       class="form-control form-control-lg" 
                       id="country" 
                       name="country" 
                       placeholder="Ejemplo: Dominican Republic" 
                       required>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary btn-lg w-100">
                    <i class="bi bi-search me-2"></i>Buscar País
                </button>
            </div>
        </div>
    </form>

    <?php
    if(isset($_GET['country'])){
        $country = rawurlencode(trim($_GET['country']));
        $url = "https://restcountries.com/v3.1/name/".$country;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Deshabilitar SSL solo para pruebas
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MiApp/1.0; +http://tusitio.com)');
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);

        if($curl_error) {
            echo '<div class="alert alert-danger">Error de conexión: ' . $curl_error . '</div>';
        } else {
            $data = json_decode($response, true);

            if($http_code == 200 && is_array($data) && isset($data[0])){
                $pais = $data[0];
                $flag = $pais['flags']['png'] ?? '';
                $capital = $pais['capital'][0] ?? 'N/A';
                $population = number_format($pais['population'] ?? 0);
                $currencies = isset($pais['currencies']) ? implode(", ", array_keys($pais['currencies'])) : 'N/A';

                ?>
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="mb-4 text-center"><?= htmlspecialchars($pais['name']['common']) ?></h2>
                        
                        <div class="text-center mb-4">
                            <?php if ($flag): ?>
                                <img src="<?= $flag ?>" 
                                     alt="Bandera de <?= htmlspecialchars($pais['name']['common']) ?>" 
                                     class="img-fluid rounded shadow-sm" 
                                     style="max-width: 200px;">
                            <?php endif; ?>
                        </div>
                        
                        <p class="fs-5"><span class="fw-bold">🏙️ Capital:</span> <?= htmlspecialchars($capital) ?></p>
                        <p class="fs-5"><span class="fw-bold">👥 Población:</span> <?= htmlspecialchars($population) ?></p>
                        <p class="fs-5"><span class="fw-bold">💱 Moneda(s):</span> <?= htmlspecialchars($currencies) ?></p>
                    </div>
                </div>
                <?php
            } else {
                echo '<div class="alert alert-warning">No se encontraron datos para el país ingresado.</div>';
            }
        }
    }
    ?>

</div>

<?php include 'footer.php'; ?>
