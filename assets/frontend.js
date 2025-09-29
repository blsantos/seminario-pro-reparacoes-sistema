/**
 * Seminário Pró-Reparações - Frontend JavaScript
 * Scripts para funcionalidades públicas e formulários
 */

jQuery(document).ready(function($) {
    
    // Validação em tempo real do formulário de inscrição
    $('#seminario-form input[type="email"]').on('blur', function() {
        var email = $(this).val();
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (email && !emailRegex.test(email)) {
            $(this).addClass('error');
            showFieldError($(this), 'Email inválido');
        } else {
            $(this).removeClass('error');
            hideFieldError($(this));
        }
    });
    
    // Validação de campos obrigatórios
    $('#seminario-form input[required], #seminario-form textarea[required]').on('blur', function() {
        if (!$(this).val().trim()) {
            $(this).addClass('error');
            showFieldError($(this), 'Este campo é obrigatório');
        } else {
            $(this).removeClass('error');
            hideFieldError($(this));
        }
    });
    
    // Controle de seleção de painéis
    var limitePaineis = parseInt($('#seminario-form').data('limite-paineis')) || 3;
    
    $('input[name="paineis_escolhidos[]"]').on('change', function() {
        var selecionados = $('input[name="paineis_escolhidos[]"]:checked').length;
        
        if (selecionados >= limitePaineis) {
            $('input[name="paineis_escolhidos[]"]:not(:checked)').prop('disabled', true);
            showMessage('Você atingiu o limite máximo de ' + limitePaineis + ' painéis.', 'warning');
        } else {
            $('input[name="paineis_escolhidos[]"]').prop('disabled', false);
            hideMessage();
        }
        
        // Atualizar contador
        updatePainelCounter(selecionados, limitePaineis);
    });
    
    // Funções auxiliares
    function showFieldError($field, message) {
        hideFieldError($field);
        $field.after('<div class="field-error" style="color: #D72638; font-size: 12px; margin-top: 4px;">' + message + '</div>');
    }
    
    function hideFieldError($field) {
        $field.siblings('.field-error').remove();
    }
    
    function showMessage(message, type) {
        var className = type === 'warning' ? 'warning' : 'info';
        var icon = type === 'warning' ? '⚠️' : 'ℹ️';
        
        $('#seminario-form').prepend(
            '<div class="seminario-form-message ' + className + '" style="padding: 10px; margin-bottom: 15px; border-radius: 6px; background: #fff3cd; color: #856404; border: 1px solid #ffeaa7;">' +
            icon + ' ' + message +
            '</div>'
        );
    }
    
    function hideMessage() {
        $('.seminario-form-message').fadeOut(function() {
            $(this).remove();
        });
    }
    
    function updatePainelCounter(selected, limit) {
        var $counter = $('#painel-counter');
        if ($counter.length === 0) {
            $('.seminario-paineis-checkbox').before(
                '<div id="painel-counter" style="margin-bottom: 10px; font-size: 14px; color: #666;"></div>'
            );
            $counter = $('#painel-counter');
        }
        
        $counter.text('Painéis selecionados: ' + selected + '/' + limit);
        
        if (selected >= limit) {
            $counter.css('color', '#D72638');
        } else {
            $counter.css('color', '#2A9D8F');
        }
    }
    
    // Smooth scrolling para âncoras
    $('a[href*="#"]').on('click', function(e) {
        var target = $(this.hash);
        if (target.length) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: target.offset().top - 100
            }, 800);
        }
    });
    
    // Lazy loading para imagens dos shortcodes
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    observer.unobserve(img);
                }
            });
        });
        
        const lazyImages = document.querySelectorAll('img[data-src]');
        lazyImages.forEach(img => imageObserver.observe(img));
    }
    
    // Animações de entrada para cards
    function animateOnScroll() {
        $('.seminario-painel-card, .seminario-conferencista-item, .seminario-observador-item').each(function() {
            var elementTop = $(this).offset().top;
            var elementBottom = elementTop + $(this).outerHeight();
            var viewportTop = $(window).scrollTop();
            var viewportBottom = viewportTop + $(window).height();
            
            if (elementBottom > viewportTop && elementTop < viewportBottom) {
                $(this).addClass('animate-in');
            }
        });
    }
    
    $(window).on('scroll', animateOnScroll);
    animateOnScroll(); // Executar na primeira carga
});