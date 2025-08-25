<?php
// contact_handler.php - Versão simples usando apenas função mail() nativa do PHP
date_default_timezone_set('America/Sao_Paulo');
// Carregar variáveis de ambiente usando parser simples para .env
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && substr($line, 0, 1) !== '#') {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value, '"\'');
        }
    }
} else {
    // Se não encontrar o arquivo .env, usar getenv() como fallback
    $_ENV['RECIPIENT_EMAIL'] = getenv('RECIPIENT_EMAIL');
    $_ENV['SMTP_HOST'] = getenv('SMTP_HOST');
    $_ENV['SMTP_USER'] = getenv('SMTP_USER');
    $_ENV['SMTP_PASS'] = getenv('SMTP_PASS');
    $_ENV['SMTP_SECURE'] = getenv('SMTP_SECURE');
    $_ENV['SMTP_PORT'] = getenv('SMTP_PORT');
}

// Configurações de email - usando env ou valores padrão
$to_email = $_ENV['RECIPIENT_EMAIL'] ?? "angelpolicarpo@arqbits.com.br";

// Função para sanitizar dados de entrada
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Função para validar email
function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Função para enviar email usando função mail() nativa do PHP
function send_contact_email($name, $email, $subject, $message, $to_email) {
    // Configurar headers
    $headers = array();
    $headers[] = "From: " . $email;
    $headers[] = "Reply-To: " . $email;
    $headers[] = "Content-Type: text/html; charset=UTF-8";
    $headers[] = "X-Mailer: PHP/" . phpversion();
    $headers[] = "MIME-Version: 1.0";
    
    // Obter os componentes da data/hora atual para criar ID único
    $ano = (int) date('Y');
    $mes = (int) date('m');
    $dia = (int) date('d');
    $hora = (int) date('H');
    $minuto = (int) date('i');
    $segundo = (int) date('s');
    
    // Calcular a soma dos valores
    $soma = $ano + $mes + $dia + $hora + $minuto + $segundo;
    
    $full_subject = '[' . $soma . '] ' . $subject;
    
    $body = "
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>Formulário do Site</title>
    </head>
    <body>
        <p><strong>Nome:</strong> " . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . "<br>
        <strong>Email:</strong> " . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . "<br>
        <strong>Assunto:</strong> " . htmlspecialchars($subject, ENT_QUOTES, 'UTF-8') . "<br>
        <strong>Mensagem:</strong></p><br>
        <div style='background: #f5f5f5; padding: 15px; border-left: 4px solid #007cba;'>
            " . nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8')) . "
        </div>
        <hr>
        <p><small>Enviado em: " . date('d/m/Y H:i:s') . "</small></p>
    </body>
    </html>
    ";

    return mail($to_email, $full_subject, $body, implode("\r\n", $headers));
}

// Função para salvar mensagem em arquivo de log
function log_contact_message($name, $email, $subject, $message) {
    $log_entry = array(
        'timestamp' => date('Y-m-d H:i:s'),
        'name' => $name,
        'email' => $email,
        'subject' => $subject,
        'message' => $message,
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
    );
    
    $log_file = __DIR__ . '/logs/contact_messages.log';
    
    // Criar diretório de logs se não existir
    $log_dir = dirname($log_file);
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0755, true);
    }
    
    $log_line = json_encode($log_entry, JSON_UNESCAPED_UNICODE) . "\n";
    
    // Criar arquivo de log se não existir
    if (!file_exists($log_file)) {
        file_put_contents($log_file, "Contact Messages Log - Created: " . date('Y-m-d H:i:s') . "\n");
    }
    
    return file_put_contents($log_file, $log_line, FILE_APPEND | LOCK_EX);
}

// Função principal de processamento
function process_contact_form() {
    $result = array(
        'success' => false,
        'message' => '',
        'errors' => array()
    );
    
    // Verificar se é uma requisição POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $result['errors'][] = "Método de requisição inválido.";
        return $result;
    }
    
    // Capturar e sanitizar dados
    $name = sanitize_input($_POST['name'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $subject = sanitize_input($_POST['subject'] ?? 'Contato do Website');
    $message = sanitize_input($_POST['message'] ?? '');
    
    // Validações
    if (empty($name)) {
        $result['errors'][] = "Nome é obrigatório.";
    } elseif (strlen($name) < 2) {
        $result['errors'][] = "Nome deve ter pelo menos 2 caracteres.";
    }
    
    if (empty($email)) {
        $result['errors'][] = "Email é obrigatório.";
    } elseif (!is_valid_email($email)) {
        $result['errors'][] = "Email inválido.";
    }
    
    if (empty($message)) {
        $result['errors'][] = "Mensagem é obrigatória.";
    } elseif (strlen($message) < 10) {
        $result['errors'][] = "Mensagem deve ter pelo menos 10 caracteres.";
    } elseif (strlen($message) > 2000) {
        $result['errors'][] = "Mensagem muito longa (máximo 2000 caracteres).";
    }
    
    // Verificação anti-spam básica
    $spam_keywords = array('casino', 'viagra', 'cialis', 'porn', 'xxx');
    $content_to_check = strtolower($name . ' ' . $email . ' ' . $subject . ' ' . $message);
    
    foreach ($spam_keywords as $keyword) {
        if (strpos($content_to_check, $keyword) !== false) {
            $result['errors'][] = "Mensagem contém conteúdo suspeito.";
            break;
        }
    }
    
    // Verificar se há muitos links na mensagem
    $link_count = preg_match_all('/https?:\/\/[^\s]+/', $message);
    if ($link_count > 2) {
        $result['errors'][] = "Muitos links na mensagem.";
    }
    
    // Se há erros, retornar
    if (!empty($result['errors'])) {
        return $result;
    }
    
    // Tentar enviar email
    try {
        $email_sent = send_contact_email($name, $email, $subject, $message, $GLOBALS['to_email']);
        
        if ($email_sent) {
            // Log da mensagem
            log_contact_message($name, $email, $subject, $message);
            
            $result['success'] = true;
            $result['message'] = "Mensagem enviada com sucesso! Entraremos em contato em breve.";
        } else {
            $result['errors'][] = "Falha ao enviar email. Tente novamente mais tarde.";
        }
    } catch (Exception $e) {
        error_log("Contact form error: " . $e->getMessage());
        $result['errors'][] = "Erro interno do servidor. Tente novamente mais tarde.";
    }
    
    return $result;
}

// Função para verificar rate limiting
function check_rate_limit($ip, $max_attempts = 150, $window_minutes = 60) {
    $rate_limit_file = __DIR__ . '/logs/rate_limit.json';
    $current_time = time();
    $window_seconds = $window_minutes * 60;
    
    // Criar diretório se não existir
    $rate_limit_dir = dirname($rate_limit_file);
    if (!is_dir($rate_limit_dir)) {
        mkdir($rate_limit_dir, 0755, true);
    }
    
    // Carregar dados existentes
    $rate_data = array();
    if (file_exists($rate_limit_file)) {
        $content = file_get_contents($rate_limit_file);
        $rate_data = json_decode($content, true) ?? array();
    }
    
    // Limpar dados antigos
    foreach ($rate_data as $stored_ip => $attempts) {
        $rate_data[$stored_ip] = array_filter($attempts, function($timestamp) use ($current_time, $window_seconds) {
            return ($current_time - $timestamp) < $window_seconds;
        });
        
        if (empty($rate_data[$stored_ip])) {
            unset($rate_data[$stored_ip]);
        }
    }
    
    // Verificar tentativas do IP atual
    $ip_attempts = isset($rate_data[$ip]) ? $rate_data[$ip] : array();
    
    if (count($ip_attempts) >= $max_attempts) {
        return false;
    }
    
    // Registrar nova tentativa
    $ip_attempts[] = $current_time;
    $rate_data[$ip] = $ip_attempts;
    
    // Salvar dados atualizados
    file_put_contents($rate_limit_file, json_encode($rate_data));
    
    return true;
}

// Função para resposta JSON (AJAX)
function send_json_response($data) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

// Processamento para requisições AJAX
if (isset($_GET['ajax']) && $_GET['ajax'] === '1') {
    $client_ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    
    if (!check_rate_limit($client_ip)) {
        send_json_response(array(
            'success' => false,
            'message' => 'Muitas tentativas. Tente novamente em alguns minutos.',
            'errors' => array('Rate limit excedido.')
        ));
    }
    
    $result = process_contact_form();
    send_json_response($result);
}

// Função para uso no arquivo principal (mantendo compatibilidade)
function handle_contact_form_submission() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_contact'])) {
        $client_ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        
        if (!check_rate_limit($client_ip)) {
            return array(
                'success' => false,
                'message' => 'Muitas tentativas. Tente novamente em alguns minutos.',
                'errors' => array('Rate limit excedido.')
            );
        }
        
        return process_contact_form();
    }
    
    return array('success' => false, 'message' => '', 'errors' => array());
}

?>