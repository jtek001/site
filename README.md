Visão Geral do Projeto: Site Jtek Info
Este documento descreve a concepção, os objetivos e a estrutura técnica do site jtekinfo.com.br, desenvolvido para servir como a principal presença digital da empresa.

1. Conceito e Objetivos
A ideia central do site é funcionar como um cartão de visitas digital, que seja ao mesmo tempo moderno, profissional e funcional. Os principais objetivos são:

Estabelecer Credibilidade: Apresentar a Jtek Info como uma empresa sólida e confiável no setor de tecnologia da informação, utilizando um design limpo e monocromático (preto, branco e cinza) que transmite sofisticação e foco.

Apresentar Serviços Claramente: Detalhar o portfólio de serviços de forma organizada, permitindo que potenciais clientes entendam rapidamente como a Jtek Info pode solucionar os seus problemas.

Gerar Prova Social: Exibir uma lista de clientes e parceiros satisfeitos, além de depoimentos reais, para construir confiança e validar a qualidade do trabalho oferecido.

Facilitar o Contato: Oferecer múltiplos canais de contato de fácil acesso, incluindo um formulário que armazena as mensagens de forma segura e organizada num banco de dados.

Demonstrar Expertise: Através de páginas detalhadas para cada serviço e uma futura área de blog, posicionar a Jtek Info como uma autoridade nos seus campos de atuação.

2. Estrutura Técnica do Site
O site foi construído utilizando uma arquitetura baseada em PHP e MySQL, o que garante modularidade, segurança e facilidade de manutenção. A estrutura está dividida em duas áreas principais: o Frontend (o site público) e o Backend (a área administrativa).

2.1. Frontend (Site Público)
Esta é a parte do site que os visitantes veem. A estrutura de arquivos foi pensada para ser reutilizável e organizada.

Tecnologias: HTML5, CSS3, PHP.

Arquivos Principais:

header.php e footer.php: Componentes modulares que são incluídos em todas as páginas, garantindo consistência no topo e no rodapé do site. O uso de caminhos absolutos (/css/style.css) garante que o estilo funcione corretamente em todas as pastas.

index.php: A página inicial, projetada para causar uma primeira impressão forte com um banner de destaque, um resumo dos principais serviços e depoimentos de clientes.

servicos.php: Uma página central que funciona como um "hub", apresentando todos os serviços oferecidos com imagens e descrições curtas, cada uma com um link para a sua página de detalhes.

Páginas de Detalhes de Serviço (redes.php, seguranca.php, etc.): Cada serviço possui a sua própria página com conteúdo detalhado, explicando os benefícios e o escopo do trabalho.

gdoor.php: Uma página de aterragem dedicada à nova parceria de revenda do sistema ERP, com um layout focado em informar e converter o interesse em contato.

sobre.php, parceiros.php, clientes.php: Páginas institucionais que constroem a narrativa da marca e reforçam a sua autoridade e rede de contatos.

contato.php: Página com um formulário de contato que interage com o processa_contato.php.

2.2. Backend (Sistema de Gestão)
O backend é composto por duas partes principais: o sistema de rastreamento de visitantes e a área administrativa para gestão de contatos.

Rastreamento de Visitantes:

db_config.php: Um arquivo de configuração central e seguro que armazena as credenciais de acesso ao banco de dados MySQL.

track_visitor.php: Um script que é chamado em todas as páginas (através do header.php) para coletar dados anónimos dos visitantes (IP, página visitada, navegador) e guardá-los na tabela visitantes do banco de dados.

Gestão de Contatos (Formulário):

processa_contato.php: Script que recebe os dados do formulário da página de contato, valida-os e insere-os de forma segura na tabela contatos do banco de dados, utilizando prepared statements para prevenir injeções de SQL.

Área Administrativa (/admin/):

Segurança: Localizada numa pasta dedicada para separar o acesso público do privado.

admin/index.php: Funciona como um portal de login, protegido por uma senha definida diretamente no código (hardcoded), uma solução simples e eficaz para um único utilizador administrativo. O acesso é controlado através de sessões PHP ($_SESSION).

admin/listar_contatos.php: O painel principal da área restrita. Ele lê as mensagens diretamente da tabela contatos do MySQL e exibe-as num formato de "acordeão" (sanfona), que é limpo e fácil de usar. Permite a exclusão permanente de mensagens do banco de dados.

Esta estrutura foi projetada para ser ao mesmo tempo robusta e escalável, permitindo que novas funcionalidades, como um sistema de blog ou novas páginas de serviço, possam ser adicionadas no futuro de forma organizada e eficiente.
