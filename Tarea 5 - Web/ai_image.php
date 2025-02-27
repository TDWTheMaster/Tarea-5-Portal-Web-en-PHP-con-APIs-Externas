<?php include 'header.php'; ?>

<div class="container">
    <h1 class="mb-4 text-center">🖼️ Generador de Imágenes con IA</h1>
    
    <form method="GET" class="mb-5">
        <div class="row g-3">
            <div class="col-md-9">
                <input type="text" 
                    class="form-control form-control-lg" 
                    id="keyword" 
                    name="keyword" 
                    placeholder="Ejemplo: sunset, dog, mountain (usa inglés)"
                    required>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary btn-lg w-100">
                    <i class="bi bi-image-fill me-2"></i>Generar
                </button>
            </div>
        </div>
    </form>

    <?php
    if(isset($_GET['keyword'])){
        $access_key = "pTKb4MwnbtonJbM394JhbnhYZNgWE98JqSun8z_Y0lY";
        $keyword = urlencode(trim($_GET['keyword']));
        $url = "https://api.unsplash.com/photos/random?query={$keyword}&client_id={$access_key}";

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => [
                'Accept-Version: v1'
            ]
        ]);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);

        if($curl_error) {
            echo '<div class="alert alert-danger">Error de conexión: ' . $curl_error . '</div>';
        } else {
            $data = json_decode($response, true);

            if($http_code == 200) {
                if(isset($data['urls']['regular'])) {
                    ?>
                    <div class="card shadow">
                        <div class="card-body">
                            <h3 class="mb-4 text-center">Resultado para: <?= htmlspecialchars($_GET['keyword']) ?></h3>
                            <div class="ratio ratio-16x9">
                                <img src="<?= $data['urls']['regular'] ?>" 
                                    class="img-fluid rounded-3" 
                                    alt="<?= htmlspecialchars($data['alt_description'] ?? 'Imagen generada') ?>">
                            </div>
                            <div class="mt-3 text-center">
                                <p class="text-muted small">
                                    📷 Foto por: 
                                    <a href="<?= $data['user']['links']['html'] ?>?utm_source=tu_app&utm_medium=referral" 
                                        target="_blank">
                                        <?= $data['user']['name'] ?>
                                    </a> en 
                                    <a href="https://unsplash.com/?utm_source=tu_app&utm_medium=referral" 
                                        target="_blank">
                                        Unsplash
                                    </a>
                                </p>
                               
                            </div>
                        </div>
                    </div>
                    <?php
                } else {
                    echo '<div class="alert alert-warning">No se encontraron imágenes. Intenta con palabras en inglés.</div>';
                }
            } else {
                $error_msg = $data['errors'][0] ?? 'Error desconocido';
                echo "<div class='alert alert-danger'>Error $http_code: " . htmlspecialchars($error_msg) . "</div>";
                
                // Mensaje especial para límite de solicitudes
                if($http_code == 403) {
                    echo '<div class="alert alert-info mt-2">Límite de solicitudes alcanzado (50/hr en modo Demo).</div>';
                }
            }
        }
    }
    ?>

</div>

<?php include 'footer.php'; ?>
