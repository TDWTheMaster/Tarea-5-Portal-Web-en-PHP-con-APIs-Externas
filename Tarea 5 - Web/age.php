<?php include 'header.php'; ?>
<h1 class="mb-4">Predicción de Edad</h1>
<form method="GET" class="mb-4">
    <div class="mb-3">
        <label for="name" class="form-label">Ingrese un nombre:</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Ejemplo: Juan" required>
    </div>
    <button type="submit" class="btn btn-primary">Predecir Edad</button>
</form>

<?php
if (isset($_GET['name'])) {
    $name = urlencode(trim($_GET['name']));
    $url = "https://api.agify.io/?name=" . $name;
    
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT => 5
    ]);
    
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        echo '<div class="alert alert-danger">Error al conectar con la API: ' . curl_error($ch) . '</div>';
    } else {
        $data = json_decode($response, true);
        
        if (isset($data['age'])) {
            $age = $data['age'];
            $category = match (true) {
                $age < 18 => ['Joven', 'teenager', '👶'],
                $age <= 50 => ['Adulto', 'adult', '🧑'],
                default => ['Anciano', 'elderly', '👴']
            };
            
            // Generar imagen relacionada desde Unsplash con parámetro aleatorio para evitar cacheo
            $image_url = "https://source.unsplash.com/600x400/?{$category[1]},face&rand=" . uniqid();
            ?>
            <div class="card shadow mt-4">
                <div class="card-body">
                    <h2 class="mb-3"><?= htmlspecialchars($data['name']) ?>: <?= $age ?> años</h2>
                    <div class="alert alert-<?= $category[0] === 'Joven' ? 'info' : ($category[0] === 'Adulto' ? 'warning' : 'secondary') ?>">
                        Clasificación: <span class="badge bg-dark"><?= $category[0] ?> <?= $category[2] ?></span>
                    </div>
                    <div class="ratio ratio-16x9">
                        <img src="<?= $image_url ?>" 
                             class="img-fluid rounded-3 shadow-sm"
                             alt="Imagen de <?= $category[0] ?>"
                             onerror="this.src='https://via.placeholder.com/600x400?text=Imagen+no+disponible'">
                    </div>
                    <p class="mt-3 text-muted small">* Imagen representativa basada en la clasificación de edad</p>
                </div>
            </div>
            <?php
        } else {
            echo '<div class="alert alert-warning">No se pudo determinar la edad para este nombre</div>';
        }
    }
    curl_close($ch);
}
?>

<style>
    .badge { font-size: 1.1em; padding: 0.6em 1em; }
</style>

<?php include 'footer.php'; ?>
