<?php include '../header.php'; ?>

<main>
    <!-- Cabeçalho da Página -->
    <section class="page-header">
        <div class="container">
            <h1>Contato</h1>
            <p>Estamos prontos para ajudar. Envie sua mensagem ou entre em contato pelos nossos canais.</p>
        </div>
    </section>

    <!-- Seção de Contato -->
    <section class="contact-page-section">
        <div class="container">

            <!-- Bloco para exibir mensagens de status -->
            <?php if (isset($_GET['status'])): ?>
                <?php if ($_GET['status'] == 'sucesso'): ?>
                    <div class="alert alert-success">
                        <strong>Obrigado!</strong> Sua mensagem foi enviada com sucesso.
                    </div>
                <?php elseif ($_GET['status'] == 'erro'): ?>
                    <div class="alert alert-error">
                        <strong>Ops!</strong> Por favor, preencha todos os campos corretamente.
                    </div>
                <?php elseif ($_GET['status'] == 'erro_db'): ?>
                     <div class="alert alert-error">
                        <strong>Erro!</strong> Ocorreu um problema ao salvar sua mensagem. Tente novamente.
                    </div>
                <?php endif; ?>
            <?php endif; ?>


            <div class="contact-grid">
                
                <!-- Coluna da Esquerda: Informações de Contato -->
                <div class="contact-info">
                    <h3>Informações de Contato</h3>
                    <p>Sinta-se à vontade para nos ligar ou enviar um e-mail. Estamos disponíveis para discutir suas necessidades.</p>
                    <p>&nbsp;</p>
                    <div class="contact-info-item">
                        <strong>Email:</strong>
                        <p><a href="mailto:contato@jtekinfo.com.br">contato@jtekinfo.com.br</a></p>
                    </div>
                    
                    <div class="contact-info-item">
                        <strong>Telefone / WhatsApp:</strong>
                        <p><a href="https://wa.me/5543999302023" target="_blank">(43)9.9930-2023</a></p>
                    </div>

                    <div class="contact-info-item">
                        <strong>Endereço:</strong>
                        <p>Sengés, Paraná, Brasil</p>
                    </div>

                    <div class="contact-info-item">
                        <strong>Horário de Atendimento:</strong>
                        <p>Segunda a Sábado, das 8h às 18h</p>
                    </div>
                </div>

                <!-- Coluna da Direita: Formulário -->
                <div class="contact-form">
                    <h3>Envie uma Mensagem</h3>
                    <form id="contactForm" action="processa_contato.php" method="POST">
                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type="text" id="nome" name="nome" required>
                        </div>
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="assunto">Assunto</label>
                            <input type="text" id="assunto" name="assunto" required>
                        </div>
                        <div class="form-group">
                            <label for="mensagem">Mensagem</label>
                            <textarea id="mensagem" name="mensagem" rows="6" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary-dark">Enviar Mensagem</button>
                    </form>
                </div>

            </div>
        </div>
    </section>
    <!-- Seção do Mapa -->
    <section class="map-section">
        <h1 class="map-title">Cidades Atendidas</h1>
        <!-- O elemento DIV onde o mapa será renderizado -->
        <div id="map"></div>
    </section>    
</main>

<?php include '../footer.php'; ?>

<!-- Scripts do Google Maps -->
<script>
    // Função de callback que  executada quando a API do Google Maps é carregada
    function initMap() {
        // 1. LISTA DE CIDADES
        const cidades = [
            'Sengés, PR',
            'Jaguariaíva, PR',
            'Pira do Sul, PR',
            'Arapoti, PR',
            'Wenceslau Braz, PR',
            'São José da Boa Vista, PR',
            'Tomazina, PR',
            'Siqueira Campos, PR',
            'Santana do Itararé, PR',
            'Itararé, SP',
            'Itaberá, SP',
            'Itapeva, SP',
            'Itaporanga, SP'
        ];

        const map = new google.maps.Map(document.getElementById('map'), {
            zoom: 4,
            center: { lat: -24.25, lng: -49.5 }, // Centro aproximado da sua região
        });

        const geocoder = new google.maps.Geocoder();
        const bounds = new google.maps.LatLngBounds();
        let markersCount = 0;

        cidades.forEach(cidade => {
            geocoder.geocode({ 'address': cidade }, function(results, status) {
                if (status === 'OK') {
                    
                    // --- PERSONALIZAÇÃO DO MARCADOR ---
                    let markerOptions = {
                        map: map,
                        position: results[0].geometry.location,
                        title: cidade
                    };

                    // Verifica se a cidade é 'Sengés, PR' para usar um ícone azul
                    if (cidade === 'Sengés, PR') {
                        markerOptions.icon = '../img/blue-dot.png';
                    }
                    
                    const marker = new google.maps.Marker(markerOptions);
                    // --- FIM DA PERSONALIZAÇÃO ---

                    bounds.extend(marker.getPosition());
                    markersCount++;
                } else {
                    console.error('Geocode falhou para a cidade "' + cidade + '" pelo seguinte motivo: ' + status);
                }
                
                if (markersCount === cidades.length) {
                    map.fitBounds(bounds);
                    if (cidades.length === 1) {
                        map.setZoom(9);
                    }
                }
            });
        });
    }
</script>

<script async
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCx-vHZi-fVnqhxg2B47xmXH-5G2HDCuk8&callback=initMap">
</script>
