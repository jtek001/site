    <!-- Rodapé -->
    <footer class="main-footer">
        <div class="container footer-grid">
            <div class="footer-col">
                <!-- Caminho da imagem absoluto a partir da raiz -->
                <img src="/img/logo-branco.png" width="220" alt="Jtek Info" style="margin-bottom: 15px;">
                <p>Assistência Técnica em Informática<br> Redes e Infraestrutura em Geral</p>
                <p class="cnpj">CNPJ: 60.167.603/0001-04</p>
              <br>
              <p>Nova Parceria:</p>
              <p><a href="/gdoor"><img src="/img/parceiros/gdoor.png" width="220" ></a></p>
              <p><a href="/dell"><img src="/img/parceiros/dellen.png" width="220" ></a></p>
            </div>
            <div class="footer-col">
                <h4>Navegação</h4>
                <ul>
                    <li><a href="/">Incio</a></li>
                    <li><a href="/servicos">Serviços</a></li>
                    <li><a href="/sobre">Sobre</a></li>
                    <li><a href="/parceiros">Parceiros</a></li>
                    <li><a href="/clientes">Clientes</a></li>
                    <li><a href="/app" target="_blank">O.S</a></li>
                    <li><a href="/webmail" target="_blank">Webmail</a></li>
                </ul>
            </div>
             <div class="footer-col">
                <h4>Serviços</h4>
                <ul>
                    <li><a href="/servicos/redes">Redes e Infraestrutura</a></li>
                    <li><a href="/servicos/seguranca">Segurança Digital</a></li>
                    <li><a href="/servicos/suporte">Suporte Técnico</a></li>
                    <li><a href="/servicos/manutencao">Manutenão</a></li>
                    <li><a href="/servicos/backup">Backups</a></li>
                    <li><a href="/servicos/treinamento">Treinamento</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Contato</h4>
                <ul>
                    <li><a href="mailto:contato@jtekinfo.com.br">contato@jtekinfo.com.br</a></li>
                    <li><a href="https://wa.me/5543999302023" target="_blank">(43) 99930-2023</a></li>
              </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <!-- O link para a área de admin j  um caminho absoluto -->
            <p><a href="/admin" title="Acesso Restrito" style="text-decoration: none; color: #ccc;">&copy;</a> <?php echo date("Y"); ?> Jtek Info. Todos os direitos reservados.</p>
        </div>
    </footer>
	<!-- JavaScript para o Acordeão -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const accordionItems = document.querySelectorAll('.accordion-header');
            
            accordionItems.forEach(item => {
                item.addEventListener('click', function () {
                    // Fecha todos os outros itens abertos
                    accordionItems.forEach(otherItem => {
                        if (otherItem !== this) {
                            otherItem.classList.remove('active');
                            otherItem.nextElementSibling.style.display = 'none';
                        }
                    });

                    // Abre ou fecha o item clicado
                    this.classList.toggle('active');
                    const content = this.nextElementSibling;
                    if (content.style.display === 'block') {
                        content.style.display = 'none';
                    } else {
                        content.style.display = 'block';
                    }
                });
            });
        });
    </script>
</body>
</html>
