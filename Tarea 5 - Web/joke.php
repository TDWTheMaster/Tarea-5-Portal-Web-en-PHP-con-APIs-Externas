<?php include 'header.php'; ?>

<div class="container">
    <h1 class="mb-4 text-center">😂 Generador de Chistes en Español</h1>

    <div class="text-center mb-4">
        <a href="" class="btn btn-primary btn-lg">
            <i class="bi bi-emoji-laughing me-2"></i>Generar un Chiste
        </a>
    </div>

    <?php
    $url = "https://v2.jokeapi.dev/joke/Any?lang=es&type=twopart"; // Chistes en español, tipo pregunta-respuesta
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);

    if ($curl_error) {
        echo '<div class="alert alert-danger">Error de conexión: ' . $curl_error . '</div>';
    } else {
        $data = json_decode($response, true);

        if ($http_code == 200 && isset($data['setup']) && isset($data['delivery'])) {
            ?>
            <div class="card shadow-lg mb-5">
                <div class="card-body">
                    <h3 class="card-title mb-4">🎲 Aquí tienes un chiste:</h3>
                    <p class="fs-4"><strong><?= htmlspecialchars($data['setup']) ?></strong></p>
                    <p class="fs-5 text-muted"><?= htmlspecialchars($data['delivery']) ?></p>
                </div>
            </div>
            <?php
        } else {
            echo '<div class="alert alert-warning">No se pudo obtener un chiste en este momento.</div>';
        }
    }
    ?>
</div>

<?php include 'footer.php'; ?>
