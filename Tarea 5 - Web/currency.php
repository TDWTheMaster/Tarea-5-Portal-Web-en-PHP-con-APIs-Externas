<?php include 'header.php'; ?>
<div class="row justify-content-center">
  <div class="col-md-6">
    <h1 class="mb-4 text-center">Conversión de Monedas</h1>
    <form method="GET" class="mb-4">
        <div class="mb-3">
            <label for="amount" class="form-label">Ingrese cantidad en USD:</label>
            <input type="number" step="any" class="form-control" id="amount" name="amount" placeholder="Ejemplo: 100" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Convertir</button>
    </form>

    <?php
    if(isset($_GET['amount'])){
        $amount = floatval($_GET['amount']);
        $url = "https://open.er-api.com/v6/latest/USD";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Deshabilita la verificación SSL para pruebas
        $response = curl_exec($ch);
        
        if(curl_errno($ch)){
            echo '<div class="alert alert-danger">Error al conectar con la API de monedas.</div>';
        } else {
            $data = json_decode($response, true);
            if(isset($data['rates'])){
                $rates = $data['rates'];
                
                // Conversión a DOP, EUR y GBP
                $dop = isset($rates['DOP']) ? round($rates['DOP'] * $amount, 2) : "N/A";
                $eur = isset($rates['EUR']) ? round($rates['EUR'] * $amount, 2) : "N/A";
                $gbp = isset($rates['GBP']) ? round($rates['GBP'] * $amount, 2) : "N/A";

                echo '<div class="card shadow-sm">';
                echo '<div class="card-body">';
                echo "<h2 class='mb-4 text-center'>Resultados de Conversión</h2>";
                echo "<p><strong>USD $amount</strong> = <span class='text-primary'>🇩🇴 DOP $dop</span></p>";
                echo "<p><strong>USD $amount</strong> = <span class='text-success'>💶 EUR $eur</span></p>";
                echo "<p><strong>USD $amount</strong> = <span class='text-warning'>💷 GBP $gbp</span></p>";
                echo '</div></div>';
            } else {
                echo '<div class="alert alert-warning">No se recibió información de la conversión.</div>';
            }
        }
        curl_close($ch);
    }
    ?>

  </div>
</div>
<?php include 'footer.php'; ?>
