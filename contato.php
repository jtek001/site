<?php include 'header.php'; ?>

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
                <?php elseif ($_GET['status'] == 'erro_arquivo'): ?>
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
                    
                    <div class="contact-info-item">
                        <strong>Email:</strong>
                        <p><a href="mailto:contato@jtekinfo.com.br">contato@jtekinfo.com.br</a></p>
                    </div>
                    
                    <div class="contact-info-item">
                        <strong>Telefone / WhatsApp:</strong>
                        <p><a href="https://wa.me/5543999302023" target="_blank">(43) 99930-2023</a></p>
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
                            <label for="nome">Nome Completo</label>
                            <input type="text" id="nome" name="nome" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Seu Melhor Email</label>
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

</main>

<?php include 'footer.php'; ?>
