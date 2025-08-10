<?php include 'header.php'; ?>

<main>
    <!-- Seção 1: Banner Principal -->
    <section class="hero-banner" style="background-image: linear-gradient(rgba(26, 26, 26, 0.3), rgba(26, 26, 26, 0.3)), url('img/banner.jpg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2'); background-size: cover; background-position: center;">
        <div class="container">
            <h1>Soluções de TI que Impulsionam o seu Negócio.</h1>
            <p>Da infraestrutura de redes ao suporte técnico especializado, garantimos a tecnologia que sua empresa precisa para crescer.</p>
            <a href="/contato" class="btn btn-primary">Fale com um Especialista</a>
        </div>
    </section>

    <!-- Seção 2: Serviços -->
    <section class="services-section">
        <div class="container">
            <h2>Tudo o que sua Empresa Precisa em um Só Lugar</h2>
            <div class="services-grid">
                <div class="service-card">
                    <h3>Redes e Infraestrutura</h3>
                    <p>Projetamos e implementamos redes seguras e eficientes para garantir a conectividade da sua empresa.</p>
                    <a href="/servicos/redes">Saiba Mais →</a>
                </div>
                <div class="service-card">
                    <h3>Segurança Digital</h3>
                    <p>Monitoramento proativo e soluções de segurança para proteger seus dados contra ameaas.</p>
                    <a href="/servicos/seguranca">Saiba Mais →</a>
                </div>
                <div class="service-card">
                    <h3>Suporte e Manutenção</h3>
                    <p>Suporte técnico ágil, presencial ou remoto, para manter suas operaões funcionando sem interrupções.</p>
                    <a href="/servicos/suporte">Saiba Mais →</a>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Seção 3: Prova Social (Depoimentos) -->
    <section class="testimonials-section">
        <div class="container">
            <h2>O que Nossos Clientes Dizem</h2>
            <div class="testimonial-card">
                <blockquote>
              	Contratar um provedor de serviços de TI foi a melhor
 decisão que tomei em relação à infraestrutura. Ele nos
 fornece suporte contínuo, monitoramento proativo e 
soluções. Estamos mais seguros e produtivos do que 
nunca, e não poderíamos estar mais felizes com os
 resultados."
              </blockquote>
                <cite>– Junior/Fercontabil</cite>
            </div>
          <br>
           <div class="testimonial-card">
                <blockquote>
              	Estamos extremamente satisfeitos com os serviços 
prestados. Desde que começamos a trabalhar com ele, 
nossa infraestrutura de TI se tornou muito mais estável e 
eficiente. Ele é sempre proativo, resolve problemas com 
rapidez. Além disso, o suporte é excepcional, sempre 
disponível e pronto para ajudar
              </blockquote>
                <cite>–  Robertinho/Grupo Ribeiro</cite>
            </div>
        </div>
    </section>

    <!-- Seço 4: Chamada para Ação Final -->
    <section class="cta-section">
        <div class="container">
            <h1>Pronto para Modernizar a TI da sua Empresa?</h1>
            <p>Vamos conversar sobre como nossas soluções podem ajudar você a alcançar seus objetivos.</p>
            <a href="/contato" class="btn btn-secondary">Solicite um Orçamento Sem Compromisso</a>
        </div>
    </section>

</main>
    <!-- Seço do Mapa -->
    <section class="map-section">
        <h1 class="map-title">Cidades Atendidas</h1>
        <!-- O elemento DIV onde o mapa será renderizado -->
        <div id="map"></div>
    </section>  
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

                    // Verifica se a cidade  'Sengés, PR' para usar um cone azul
                    if (cidade === 'Sengés, PR') {
                        markerOptions.icon = 'img/blue-dot.png';
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
<?php include 'footer.php'; ?>
