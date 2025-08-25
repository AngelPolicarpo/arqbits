// script.js - JavaScript para melhorar a experiência do usuário

// Aguardar o DOM carregar
document.addEventListener('DOMContentLoaded', function() {
    initSmoothScrolling();
    initFormValidation();
    initDarkModeToggle();
    initAnimations();
    initContactForm();
});

// Navegação suave para âncoras
function initSmoothScrolling() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                const headerOffset = 80; // Ajustar conforme necessário
                const elementPosition = targetElement.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                
                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
}

// Validação de formulário em tempo real
function initFormValidation() {
    const form = document.querySelector('#contact-form');
    if (!form) return;
    
    const inputs = form.querySelectorAll('input, textarea');
    
    inputs.forEach(input => {
        // Validação em tempo real
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        // Remover erro quando o usuário começa a digitar
        input.addEventListener('input', function() {
            clearFieldError(this);
        });
    });
    
    // Validação no envio - sempre usa AJAX
    form.addEventListener('submit', function(e) {
        e.preventDefault(); // Sempre prevenir o envio padrão
        
        let isValid = true;
        
        inputs.forEach(input => {
            if (!validateField(input)) {
                isValid = false;
            }
        });
        
        if (!isValid) {
            showFormMessage('Por favor, corrija os erros antes de enviar.', 'error');
        } else {
            // Se válido, enviar via AJAX
            submitFormAjax(form);
        }
    });
}

// Função para validar campo individual
function validateField(field) {
    const value = field.value.trim();
    const fieldName = field.name;
    let isValid = true;
    let errorMessage = '';
    
    // Validações básicas
    if (field.hasAttribute('required') && !value) {
        errorMessage = 'Este campo é obrigatório.';
        isValid = false;
    } else if (fieldName === 'email' && value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            errorMessage = 'Email inválido.';
            isValid = false;
        }
    } else if (fieldName === 'message' && value && value.length < 10) {
        errorMessage = 'Mensagem deve ter pelo menos 10 caracteres.';
        isValid = false;
    }
    
    // Mostrar ou limpar erro
    if (!isValid) {
        showFieldError(field, errorMessage);
    } else {
        clearFieldError(field);
    }
    
    return isValid;
}

// Mostrar erro em campo específico
function showFieldError(field, message) {
    clearFieldError(field);
    
    field.classList.add('error');
    field.setAttribute('aria-invalid', 'true');
    
    const errorElement = document.createElement('div');
    errorElement.className = 'field-error text-sm text-destructive mt-1';
    errorElement.textContent = message;
    errorElement.id = field.id + '-error';
    
    field.parentNode.appendChild(errorElement);
    field.setAttribute('aria-describedby', errorElement.id);
}

// Limpar erro de campo
function clearFieldError(field) {
    field.classList.remove('error');
    field.removeAttribute('aria-invalid');
    field.removeAttribute('aria-describedby');
    
    const existingError = field.parentNode.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
}

// Mostrar mensagem geral do formulário
function showFormMessage(message, type = 'info') {
    const messageContainer = document.querySelector('#form-message');
    if (!messageContainer) return;
    
    messageContainer.className = `alert alert-${type}`;
    messageContainer.textContent = message;
    messageContainer.style.display = 'block';
    
    // Auto-ocultar após 5 segundos
    setTimeout(() => {
        messageContainer.style.display = 'none';
    }, 5000);
}

// Toggle de modo escuro
function initDarkModeToggle() {
    // Verificar preferência salva
    const savedTheme = localStorage.getItem('theme');
    const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    
    if (savedTheme) {
        document.documentElement.className = savedTheme;
    } else if (systemPrefersDark) {
        document.documentElement.className = 'dark';
    }
    
    // Criar botão de toggle (opcional)
    createDarkModeToggle();
    
    // Escutar mudanças na preferência do sistema
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
        if (!localStorage.getItem('theme')) {
            document.documentElement.className = e.matches ? 'dark' : '';
        }
    });
}

// Criar botão de toggle do modo escuro
function createDarkModeToggle() {
    const toggleButton = document.createElement('button');
    toggleButton.innerHTML = '🌓';
    toggleButton.className = 'fixed top-4 right-4 w-12 h-12 rounded-full bg-card border shadow-md flex items-center justify-center z-50 hover:bg-accent transition-colors';
    toggleButton.title = 'Alternar modo escuro';
    toggleButton.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: var(--card, #ffffff);
        border: 1px solid var(--border, #e2e8f0);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        cursor: pointer;
        font-size: 20px;
        transition: all 0.2s ease;
        color: var(--foreground, #000000);
    `;

    toggleButton.addEventListener('click', () => {
        const currentTheme = document.documentElement.className;
        const newTheme = currentTheme === 'dark' ? '' : 'dark';
        
        document.documentElement.className = newTheme;
        localStorage.setItem('theme', newTheme);
    });
    
    document.body.appendChild(toggleButton);
}

// Animações de entrada
function initAnimations() {
    // Intersection Observer para animações
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, observerOptions);
    
    // Observar elementos que devem animar
    document.querySelectorAll('.card, .section-padding h2, .hero-section h1').forEach(el => {
        el.classList.add('animate-target');
        observer.observe(el);
    });
}

// Funcionalidade avançada do formulário de contato
function initContactForm() {
    const form = document.querySelector('#contact-form');
    if (!form) return;
    
    // Contador de caracteres para textarea
    const textarea = form.querySelector('textarea[name="message"]');
    if (textarea) {
        createCharacterCounter(textarea);
    }
    
    // Auto-resize para textarea
    if (textarea) {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    }
}

// Criar contador de caracteres
function createCharacterCounter(textarea) {
    const maxLength = 2000;
    const minLength = 10;
    
    const counter = document.createElement('div');
    counter.className = 'character-counter text-sm text-muted-foreground mt-2';
    counter.innerHTML = `<span id="char-count">0</span>/${maxLength} caracteres (mínimo ${minLength})`;
    
    textarea.parentNode.appendChild(counter);
    
    const charCountSpan = document.getElementById('char-count');
    
    textarea.addEventListener('input', function() {
        const currentLength = this.value.length;
        charCountSpan.textContent = currentLength;
        
        // Alterar cor baseado no comprimento
        if (currentLength < minLength) {
            counter.className = 'character-counter text-sm text-destructive mt-2';
        } else if (currentLength > maxLength * 0.9) {
            counter.className = 'character-counter text-sm text-yellow-600 mt-2';
        } else {
            counter.className = 'character-counter text-sm text-muted-foreground mt-2';
        }
    });
}

// Função para envio AJAX (agora sempre ativa)
async function submitFormAjax(form) {
    const submitButton = form.querySelector('button[type="submit"]');
    const originalText = submitButton.textContent;
    
    // Mostrar loading
    submitButton.textContent = 'Enviando...';
    submitButton.disabled = true;
    
    try {
        const formData = new FormData(form);
        
        const response = await fetch('contact_handler.php?ajax=1', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showFormMessage(result.message, 'success');
            form.reset();
            
            // Reset character counter
            const charCount = document.getElementById('char-count');
            if (charCount) charCount.textContent = '0';
            
            // Reset textarea height
            const textarea = form.querySelector('textarea');
            if (textarea) textarea.style.height = 'auto';
            
            // Limpar todos os erros de campo
            form.querySelectorAll('input, textarea').forEach(field => {
                clearFieldError(field);
            });
        } else {
            showFormMessage(result.errors.join(' '), 'error');
        }
    } catch (error) {
        console.error('Error submitting form:', error);
        showFormMessage('Erro ao enviar mensagem. Tente novamente.', 'error');
    } finally {
        // Restaurar botão
        submitButton.textContent = originalText;
        submitButton.disabled = false;
    }
}

// Utilitários gerais
const utils = {
    // Debounce para otimizar eventos
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },
    
    // Verificar se elemento está visível
    isElementInViewport(el) {
        const rect = el.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    },
    
    // Animar números (counter animation)
    animateNumber(element, start, end, duration = 1000) {
        const startTime = performance.now();
        const difference = end - start;
        
        function updateNumber(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            const currentNumber = Math.floor(start + (difference * progress));
            element.textContent = currentNumber;
            
            if (progress < 1) {
                requestAnimationFrame(updateNumber);
            }
        }
        
        requestAnimationFrame(updateNumber);
    }
};

// CSS para animações (adicionado via JavaScript)
const animationStyles = `
<style>
.animate-target {
    opacity: 0;
    transform: translateY(30px);
    transition: opacity 0.6s ease-out, transform 0.6s ease-out;
}

.animate-target.animate-in {
    opacity: 1;
    transform: translateY(0);
}

.field-error {
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.character-counter {
    transition: color 0.3s ease;
}

.btn:active {
    transform: translateY(1px);
}

.card:hover {
    transform: translateY(-2px);
    transition: transform 0.3s ease;
}



@media (prefers-reduced-motion: reduce) {
    .animate-target,
    .btn,
    .card {
        transition: none;
        animation: none;
    }
}
</style>
`;

// Adicionar estilos de animação ao head
document.head.insertAdjacentHTML('beforeend', animationStyles);

// Exportar funções para uso global
window.ContactFormUtils = {
    validateField,
    showFormMessage,
    utils
};