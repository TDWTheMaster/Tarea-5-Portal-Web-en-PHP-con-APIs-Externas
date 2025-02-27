<?php include 'header.php'; ?>
<div class="row justify-content-center">
  <div class="col-md-8">
    <h1 class="mb-4 text-center">Información de un Pokémon</h1>
    <form method="GET" class="mb-4">
        <div class="mb-3">
            <label for="pokemon" class="form-label">Ingrese el nombre del Pokémon:</label>
            <input type="text" class="form-control" id="pokemon" name="pokemon" placeholder="Ejemplo: pikachu" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Buscar Pokémon</button>
    </form>

    <?php
    if(isset($_GET['pokemon'])){
        $pokemon = strtolower(trim($_GET['pokemon']));
        $url = "https://pokeapi.co/api/v2/pokemon/".$pokemon;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Deshabilita la verificación SSL para pruebas
        $response = curl_exec($ch);
        if(curl_errno($ch)){
            echo '<div class="alert alert-danger">Error al conectar con la API de Pokémon.</div>';
        } else {
            $data = json_decode($response, true);
            if(isset($data['sprites'])){
                $image = $data['sprites']['front_default'];
                $experience = $data['base_experience'];
                $abilities = array_map(function($a){ return $a['ability']['name']; }, $data['abilities']);
                ?>
                <div class="card shadow mt-4">
                    <div class="card-body text-center">
                        <h2 class="card-title"><?= ucfirst($pokemon) ?></h2>
                        <img src="<?= $image ?>" alt="<?= $pokemon ?>" class="img-fluid mb-3" style="max-width: 200px;">
                        <p class="card-text"><strong>Experiencia Base:</strong> <?= $experience ?></p>
                        <p class="card-text"><strong>Habilidades:</strong> <?= implode(", ", $abilities) ?></p>
                    </div>
                </div>
                <?php
            } else {
                echo '<div class="alert alert-warning">No se encontró información para el Pokémon ingresado.</div>';
            }
        }
        curl_close($ch);
    }
    ?>
  </div>
</div>
<?php include 'footer.php'; ?>
