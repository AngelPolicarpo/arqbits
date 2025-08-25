<?php
// Incluir o processador de contato
require_once 'contact_handler.php';

// Configurações básicas
$site_title = "Arqbits";
$site_description = "Somos uma empresa focada em soluções arquivísticas e tecnológicas com ênfase em dados.";
$contact_email = "contato@arqbits.com.br";

// Dados dos serviços
$services = [
    [
        'icon' => 'search',
        'title' => 'Digitalização',
        'description' => 'Transforme documentos físicos e registros em formatos digitais acessíveis'
    ],
    [
        'icon' => 'archive',
        'title' => 'Higienização',
        'description' => 'Limpe e organize seus dados para otimizar a acessibilidade, conformidade, conservação e preservação'
    ],
    [
        'icon' => 'database',
        'title' => 'Gestão de Bases Físicas e Digitais',
        'description' => 'Organização e manutenção sistemáticas dos seus arquivos'
    ],
    [
        'icon' => 'chart',
        'title' => 'Análise de Dados',
        'description' => ' Extraia ideias e padrões significativos das suas informações, agregando valor e garantindo o posicionamento estratégico'
    ],
    [
        'icon' => 'wrench',
        'title' => 'Engenharia',
        'description' => 'Soluções técnicas personalizadas adaptadas às suas necessidades específicas'
    ],
    [
        'icon' => 'building',
        'title' => 'Arquitetura',
        'description' => 'Desenvolva sistemas e estruturas robustas para sua infraestrutura de dados'
    ]
];

// Processamento do formulário de contato
$form_result = handle_contact_form_submission();
$message_sent = $form_result['success'];
$errors = $form_result['errors'];
$form_message = $form_result['message'];

// Função para obter ícones SVG
function get_icon($type) {
    $icons = [
        'search' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10,9 9,9 8,9"/></svg>',
        'archive' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="21,8 21,21 3,21 3,8"/><rect x="1" y="3" width="22" height="5"/><line x1="10" y1="12" x2="14" y2="12"/></svg>',
        'database' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></svg>',
        'chart' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></svg>',
        'wrench' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>',
        'building' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-building w-8 h-8 text-primary" aria-hidden="true"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"></rect><path d="M9 22v-4h6v4"></path><path d="M8 6h.01"></path><path d="M16 6h.01"></path><path d="M12 6h.01"></path><path d="M12 10h.01"></path><path d="M12 14h.01"></path><path d="M16 10h.01"></path><path d="M16 14h.01"></path><path d="M8 10h.01"></path><path d="M8 14h.01"></path></svg>',
        'mail' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m2 7 8.97 5.7a1.94 1.94 0 0 0 2.06 0L22 7"/></svg>',
        'message' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>'
    ];
    return $icons[$type] ?? '';
}
?>
<!DOCTYPE html>
<html lang="pt-br" class="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($site_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($site_description); ?>">
    
    <!-- CSS baseado no Tailwind fornecido -->
    <style>
        <?php include 'style.css'; ?>
        
        /* Estilos adicionais específicos */
        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            background-image: linear-gradient(
            var(--background-img),
            var(--background-img)
        ), url('hero-bg.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        
        .section-padding {
            padding: 6rem 1.5rem;
        }
        
        .card {
            background: var(--card);
            color: var(--card-foreground);
            border-radius: 0.75rem;
            border: 1px solid var(--border);
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            white-space: nowrap;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            cursor: pointer;
            outline: none;
            height: 2.5rem;
            padding: 0.5rem 1rem;
        }
        
        .btn-primary {
            background: var(--chart-1);
            color: var(--primary-foreground);
            box-shadow: var(--shadow-xs);
            align-self: center;
            width: 200px;
        }
        
        .btn-primary:hover {
            background: var(--primary);
            opacity: 0.9;
        }
        
        .btn-outline {
            border: 1px solid var(--border);
            background: var(--background);
            box-shadow: var(--shadow-xs);
            align-self: center;
            width: 200px;
        }
        
        .btn-outline:hover {
            background: var(--accent);
            color: var(--accent-foreground);
        }
        
        .btn-lg {
            height: 2.75rem;
            padding: 0.5rem 1.5rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        
        .form-input {
            width: 100%;
            height: 2.25rem;
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--border);
            border-radius: 0.375rem;
            background: transparent;
            font-size: 0.875rem;
            transition: all 0.2s;
            outline: none;
        }
        
        .form-input:focus {
            border-color: var(--ring);
            box-shadow: 0 0 0 3px var(--ring);
            opacity: 0.5;
        }
        
        .form-textarea {
            min-height: 4rem;
            padding: 0.5rem 0.75rem;
            resize: vertical;
        }
        
        .grid {
            display: grid;
        }
        
        .grid-cols-1 { grid-template-columns: repeat(1, minmax(0, 1fr)); }
        .grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .grid-cols-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        
        .gap-4 { gap: 1rem; }
        .gap-6 { gap: 1.5rem; }
        .gap-8 { gap: 2rem; }
        .gap-12 { gap: 3rem; }
        
        @media (min-width: 640px) {
            .sm\:grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .sm\:flex-row { flex-direction: row; }
        }
        
        @media (min-width: 768px) {
            .md\:grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .md\:text-4xl { font-size: 2.25rem; line-height: 2.5rem; }
            .md\:text-6xl { font-size: 3.75rem; line-height: 1; }
            .md\:text-xl { font-size: 1.25rem; line-height: 1.75rem; }
        }
        
        @media (min-width: 1024px) {
            .lg\:grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .lg\:grid-cols-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        }
        
        .text-center { text-align: center; }
        .text-lg { font-size: 1.125rem; line-height: 1.75rem; }
        .text-xl { font-size: 1.25rem; line-height: 1.75rem; }
        .text-3xl { font-size: 1.875rem; line-height: 2.25rem; }
        .text-4xl { font-size: 2.25rem; line-height: 2.5rem; }
        
        .font-bold { font-weight: 700; }
        .font-semibold { font-weight: 600; }
        
        .max-w-2xl { max-width: 42rem; }
        .max-w-3xl { max-width: 48rem; }
        .max-w-4xl { max-width: 56rem; }
        .max-w-6xl { max-width: 72rem; }
        
        .mx-auto { margin-left: auto; margin-right: auto; }
        .mb-16 { margin-bottom: 4rem; }
        .mt-6 { margin-top: 1.5rem; }
        .pt-4 { padding-top: 1rem; }
        
        .space-y-4 > * + * { margin-top: 1rem; }
        .space-y-6 > * + * { margin-top: 1.5rem; }
        .space-y-8 > * + * { margin-top: 2rem; }
        
        .flex { display: flex; }
        .flex-col { flex-flow: row wrap; }
        .items-center { align-items: center; }
        .justify-center { justify-content: center; }
        
        .w-full { width: 100%; }
        .w-16 { width: 4rem; }
        .h-16 { height: 4rem; }
        .w-6 { width: 1.5rem; }
        .h-6 { height: 1.5rem; }
        
        .rounded-full { border-radius: 9999px; }
        
        .bg-primary\/10 { 
            background: var(--primary);
            opacity: 0.1;
        }
        
        .text-primary { color: var(--primary); }
        .text-muted-foreground { color: var(--muted-foreground); }
        
        .leading-relaxed { line-height: 1.625; }
        
        .prose { max-width: 65ch; }
        .prose-lg { font-size: 1.125rem; line-height: 1.777; }
        
        .bg-muted\/30 { 
            background: var(--muted);
            opacity: 0.3;
        }
        
        .icon-container {
            width: 4rem;
            height: 4rem;
            margin: 0 auto;
            background: var(--primary);
            opacity: 0.5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .icon-container svg {
            width: 2rem;
            height: 2rem;
            color: var(--background);
        }
        
        .alert {
            padding: 1rem;
            border-radius: 0.375rem;
            margin-bottom: 1rem;
        }
        
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
        }
        
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }
        
        body {
            background: var(--background);
            color: var(--foreground);
            font-family: var(--font-sans);
        }
        
        .smooth-scroll {
            scroll-behavior: smooth;
        }
        
        ul li {
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body class="smooth-scroll">
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="max-w-4xl mx-auto text-center space-y-8">
            <h1 class="text-4xl md:text-6xl font-bold">
                Seus Documentos Possuem uma História para Contar
            </h1>
            <p class="text-lg md:text-xl text-muted-foreground max-w-3xl mx-auto leading-relaxed">
                Uma empresa focada em soluções arquivísticas e tecnológicas.<br>
                Nosso papel é converter os seus dados em respostas e apoio às decisões do seu negócio através da digitalização,
                análise e gestão inteligente de dados.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center pt-4">
                <a href="#services" class="btn btn-primary btn-lg">
                    Saber Mais
                </a>
                <a href="#contact" class="btn btn-outline btn-lg">
                    Fale Conosco
                </a>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="section-padding">
        <div class="max-w-6xl mx-auto">
            <div class="text-center space-y-4 mb-16">
                <h2 class="text-3xl md:text-4xl font-bold">
                    Soluções Arquivísticas e Tecnológicas
                </h2>
                <p class="text-lg text-muted-foreground max-w-2xl mx-auto">
                    Soluções abrangentes para desbloquear o potencial dos seus documentos e dados.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($services as $service): ?>
                <div class="card text-center">
                    <div class="space-y-4">
                        <div class="icon-container">
                            <?php echo get_icon($service['icon']); ?>
                        </div>
                        <h3 class="text-xl font-semibold"><?php echo htmlspecialchars($service['title']); ?></h3>
                        <p class="text-muted-foreground leading-relaxed">
                            <?php echo htmlspecialchars($service['description']); ?>
                        </p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="section-padding bg-muted">
        <div class="max-w-4xl mx-auto text-center space-y-8">
            <h2 class="text-3xl md:text-4xl font-bold">
                Crescendo com um Propósito Claro
            </h2>
            <div class="prose prose-lg mx-auto text-muted-foreground">
                <p class="text-lg leading-relaxed">
                    Somos uma empresa dando os primeiros passos, mas com uma missão clara:
                    preencher a lacuna entre seus registros e o contexto tecnológico atual. Se você
                    está lidando com dias ou décadas de documentos em papel, arquivos digitais
                    complexos ou planilhas robustas, acreditamos que cada pedaço de informação
                    tem valor potencial.<br>
                    Nossa abordagem combina a Ciência da Informação e a Ciência da
                    Computação, representadas pela Biblioteconomia e Arquivologia de um lado, e
                    do outro a Análise de Dados e a Engenharia de Dados, respectivamente. Uma
                    união que busca entender as histórias que seus documentos contam e
                    transformar esse entendimento em decisões estratégicas de negócios
                </p>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="section-padding">
        <div class="max-w-4xl mx-auto">
            <div class="text-center space-y-4 mb-16">
                <h2 class="text-3xl md:text-4xl font-bold">
                    Ficou com alguma dúvida?
                </h2>
                <p class="text-lg text-muted-foreground">
                    Nós adoraríamos saber mais sobre o seu projeto e te contar sobre a viabilidade informacional dele.
                    Vamos explorar seus dados e extrair ideias juntos?
                </p>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Contact Form -->
                <div class="card">
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <?php echo get_icon('message'); ?>
                            <h3 class="text-xl font-semibold">Comece uma Conversa</h3>
                        </div>
                        
                        <?php if ($message_sent): ?>
                            <div class="alert alert-success">
                                <?php echo htmlspecialchars($form_message); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-error">
                                <ul>
                                    <?php foreach ($errors as $error): ?>
                                        <li><?php echo htmlspecialchars($error); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Contêiner para mensagens AJAX -->
                        <div id="form-message" style="display: none;"></div>
                        
                        <form method="POST" id="contact-form" class="space-y-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="form-group">
                                    <label for="name" class="form-label">Nome</label>
                                    <input type="text" id="name" name="name" class="form-input" placeholder="Seu nome" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" id="email" name="email" class="form-input" placeholder="nome@exemplo.com" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="subject" class="form-label">Assunto</label>
                                <input type="text" id="subject" name="subject" class="form-input" placeholder="Como podemos ajudar?" value="<?php echo htmlspecialchars($_POST['subject'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="message" class="form-label">Mensagem</label>
                                <textarea id="message" name="message" class="form-input form-textarea" placeholder="Conte-nos sobre seus documentos, dados ou necessidades de arquivamento..." rows="5" required><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                            </div>
                            <button type="submit" name="submit_contact" class="btn btn-primary w-full">
                                Enviar Mensagem
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Contact Info -->
                <div class="space-y-8">
                    <div class="card">
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <?php echo get_icon('mail'); ?>
                                <h3 class="text-xl font-semibold">Contato Direto</h3>
                            </div>
                            <p class="text-muted-foreground">
                                Prefere email? Entre em contato diretamente e responderemos em até 24 horas.
                            </p>
                            <a href="mailto:<?php echo $contact_email; ?>" class="btn btn-outline w-full">
                                <?php echo $contact_email; ?>
                            </a>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <h4 class="text-lg font-semibold">O que Incluir</h4>
                        <ul class="text-muted-foreground">
                            <li>• Tipo e volume de documentos ou dados</li>
                            <li>• Formato e condição atuais do armazenamento</li>
                            <li>• Metas ou dúvidas específicas que você tenha</li>
                            <li>• Cronograma e considerações sobre o orçamento</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

 <!-- Incluir JavaScript -->
    <script src="script.js"></script>
    
    <script>
        // Smooth scrolling para links âncora (fallback se script.js não carregar)
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>