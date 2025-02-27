<?php include 'header.php'; ?>
<div class="row justify-content-center">
  <div class="col-md-8">
    <h1 class="mb-4 text-center">Noticias desde WordPress</h1>
    <form method="GET" class="mb-4">
        <div class="mb-3">
            <label for="news_site" class="form-label">Seleccione la página de noticias:</label>
            <select name="news_site" id="news_site" class="form-select">
                <option value="wordpress">WordPress News</option>
                <!-- Puedes agregar más opciones aquí -->
            </select>
        </div>
        <button type="submit" class="btn btn-primary w-100">Obtener Noticias</button>
    </form>

    <?php
    if(isset($_GET['news_site']) && $_GET['news_site'] == 'wordpress'){
        $url = "https://wordpress.org/news/wp-json/wp/v2/posts?per_page=3";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Deshabilita la verificación SSL para pruebas
        $response = curl_exec($ch);
        if(curl_errno($ch)){
            echo '<div class="alert alert-danger">Error al conectar con la API de noticias.</div>';
        } else {
            $posts = json_decode($response, true);
            if(is_array($posts) && count($posts) > 0){
                echo '<div class="row">';
                foreach($posts as $post){
                    $title = $post['title']['rendered'];
                    $excerpt = strip_tags($post['excerpt']['rendered']);
                    $link = $post['link'];
                    ?>
                    <div class="col-md-4">
                        <div class="card mb-4 shadow-sm">
                            <img src="https://s.w.org/style/images/about/WordPress-logotype-wmark.png" 
                                 class="card-img-top p-3" 
                                 alt="Logo WordPress" 
                                 style="height: 150px; object-fit: contain;">
                            <div class="card-body">
                                <h5 class="card-title"><?= $title ?></h5>
                                <p class="card-text"><?= $excerpt ?></p>
                                <a href="<?= $link ?>" target="_blank" class="btn btn-outline-primary w-100">Leer más</a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                echo '</div>';
            } else {
                echo '<div class="alert alert-warning">No se encontraron noticias.</div>';
            }
        }
        curl_close($ch);
    }
    ?>
  </div>
</div>
<?php include 'footer.php'; ?>
