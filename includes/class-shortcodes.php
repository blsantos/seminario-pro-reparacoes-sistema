<?php
class SeminarioShortcodes {
    
    private $database;
    
    public function __construct() {
        $this->database = new SeminarioDatabase();
        
        add_shortcode('seminario_inscricao', array($this, 'shortcode_inscricao'));
        add_shortcode('seminario_paineis', array($this, 'shortcode_paineis'));
        add_shortcode('seminario_conferencistas', array($this, 'shortcode_conferencistas'));
        add_shortcode('seminario_observadores', array($this, 'shortcode_observadores'));
        
        // Carregar CSS dos shortcodes no frontend
        add_action('wp_enqueue_scripts', array($this, 'enqueue_shortcode_styles'));
    }
    
    public function enqueue_shortcode_styles() {
        wp_enqueue_style(
            'seminario-shortcodes',
            plugins_url('assets/shortcodes.css', dirname(__FILE__) . '/../seminario-sistema-completo.php'),
            array(),
            '1.0.0'
        );
        
        // Enqueue jQuery e script para formulário de inscrição
        wp_enqueue_script('jquery');
        wp_enqueue_script(
            'seminario-frontend',
            plugins_url('assets/frontend.js', dirname(__FILE__) . '/../seminario-sistema-completo.php'),
            array('jquery'),
            '1.0.0',
            true
        );
        
        // Localizar script com AJAX URL e nonce
        wp_localize_script('seminario-frontend', 'seminario_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('seminario_nonce')
        ));
    }
    
    public function shortcode_inscricao($atts) {
        $paineis = $this->database->get_paineis();
        $limite_paineis = $this->database->get_configuracao('limite_paineis_por_pessoa');
        $inscricoes_abertas = $this->database->get_configuracao('inscricoes_abertas');
        
        if (!$inscricoes_abertas) {
            return '<div class="seminario-notice">As inscrições estão fechadas no momento.</div>';
        }
        
        ob_start();
        ?>
        <div class="seminario-inscricao-form">
            <h2>Inscrição - II Seminário Internacional Pró-Reparações</h2>
            
            <form id="seminario-form" method="post">
                <div class="seminario-form-row">
                    <div class="seminario-form-group">
                        <label for="nome">Nome Completo *</label>
                        <input type="text" id="nome" name="nome" required>
                    </div>
                    <div class="seminario-form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                </div>
                
                <div class="seminario-form-row">
                    <div class="seminario-form-group">
                        <label for="telefone">Telefone</label>
                        <input type="tel" id="telefone" name="telefone">
                    </div>
                    <div class="seminario-form-group">
                        <label for="pais">País</label>
                        <input type="text" id="pais" name="pais">
                    </div>
                </div>
                
                <div class="seminario-form-row">
                    <div class="seminario-form-group">
                        <label for="cidade">Cidade</label>
                        <input type="text" id="cidade" name="cidade">
                    </div>
                    <div class="seminario-form-group">
                        <label for="instituicao">Instituição</label>
                        <input type="text" id="instituicao" name="instituicao">
                    </div>
                </div>
                
                <div class="seminario-form-group">
                    <label for="cargo">Cargo/Função</label>
                    <input type="text" id="cargo" name="cargo">
                </div>
                
                <div class="seminario-form-group">
                    <label for="area_interesse">Área de Interesse</label>
                    <textarea id="area_interesse" name="area_interesse" rows="3"></textarea>
                </div>
                
                <div class="seminario-form-group">
                    <label>Painéis de Interesse (máximo <?php echo $limite_paineis; ?>)</label>
                    <div class="seminario-paineis-checkbox">
                        <?php foreach ($paineis as $painel): ?>
                            <label class="seminario-checkbox-label">
                                <input type="checkbox" name="paineis_escolhidos[]" value="<?php echo $painel->id; ?>">
                                <strong><?php echo esc_html($painel->titulo); ?></strong><br>
                                <small><?php echo date('d/m/Y', strtotime($painel->data_painel)) . ' - ' . date('H:i', strtotime($painel->hora_inicio)) . ' às ' . date('H:i', strtotime($painel->hora_fim)); ?></small>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="seminario-form-group">
                    <label for="necessidades_especiais">Necessidades Especiais</label>
                    <textarea id="necessidades_especiais" name="necessidades_especiais" rows="3" placeholder="Descreva qualquer necessidade especial para sua participação no evento"></textarea>
                </div>
                
                <div class="seminario-form-submit">
                    <button type="submit" id="seminario-submit-btn">Enviar Inscrição</button>
                </div>
            </form>
            
            <div id="seminario-message" class="seminario-message" style="display: none;"></div>
            
            <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('#seminario-form').on('submit', function(e) {
                    e.preventDefault();
                    
                    var $form = $(this);
                    var $submitBtn = $('#seminario-submit-btn');
                    var $message = $('#seminario-message');
                    
                    // Desabilitar botão
                    $submitBtn.prop('disabled', true).text('Enviando...');
                    
                    // Preparar dados
                    var formData = {
                        action: 'seminario_submit_inscricao',
                        nonce: seminario_ajax.nonce,
                        nome: $('input[name="nome"]').val(),
                        email: $('input[name="email"]').val(),
                        telefone: $('input[name="telefone"]').val(),
                        pais: $('input[name="pais"]').val(),
                        cidade: $('input[name="cidade"]').val(),
                        instituicao: $('input[name="instituicao"]').val(),
                        cargo: $('input[name="cargo"]').val(),
                        area_interesse: $('textarea[name="area_interesse"]').val(),
                        necessidades_especiais: $('textarea[name="necessidades_especiais"]').val(),
                        paineis_escolhidos: []
                    };
                    
                    // Coletar painéis selecionados
                    $('input[name="paineis_escolhidos[]"]:checked').each(function() {
                        formData.paineis_escolhidos.push($(this).val());
                    });
                    
                    // Enviar via AJAX
                    $.ajax({
                        url: seminario_ajax.ajax_url,
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            if (response.success) {
                                $message.removeClass('error').addClass('success')
                                       .html('✅ ' + response.data).show();
                                $form[0].reset();
                            } else {
                                $message.removeClass('success').addClass('error')
                                       .html('❌ ' + response.data).show();
                            }
                        },
                        error: function() {
                            $message.removeClass('success').addClass('error')
                                   .html('❌ Erro de conexão. Tente novamente.').show();
                        },
                        complete: function() {
                            $submitBtn.prop('disabled', false).text('Enviar Inscrição');
                            $('html, body').animate({
                                scrollTop: $message.offset().top - 100
                            }, 500);
                        }
                    });
                });
            });
            </script>
        </div>
        <?php
        return ob_get_clean();
    }
    
    public function shortcode_paineis($atts) {
        $paineis = $this->database->get_paineis();
        
        // Agrupar painéis por data
        $paineis_por_data = array();
        foreach ($paineis as $painel) {
            $data = date('Y-m-d', strtotime($painel->data_painel));
            if (!isset($paineis_por_data[$data])) {
                $paineis_por_data[$data] = array();
            }
            $paineis_por_data[$data][] = $painel;
        }
        
        ksort($paineis_por_data);
        
        ob_start();
        ?>
        <div class="seminario-paineis-list">
            <h2>Painéis Temáticos - II Seminário Internacional Pró-Reparações</h2>
            <p class="seminario-subtitle">10 a 14 de novembro de 2025 • Belo Horizonte - MG</p>
            
            <?php foreach ($paineis_por_data as $data => $paineis_dia): ?>
                <div class="seminario-dia-section">
                    <h3 class="seminario-data-header"><?php echo date('d/m/Y - l', strtotime($data)); ?></h3>
                    
                    <div class="seminario-paineis-grid">
                        <?php foreach ($paineis_dia as $painel): ?>
                            <div class="seminario-painel-card">
                                <div class="seminario-painel-imagem">
                                    <?php if ($painel->imagem_url): 
                                        $imagem_url = $painel->imagem_url;
                                        // Se for um caminho relativo, converter para URL absoluto
                                        if (strpos($imagem_url, 'http') !== 0 && strpos($imagem_url, 'assets/') === 0) {
                                            $imagem_url = plugins_url($imagem_url, dirname(__FILE__) . '/../seminario-sistema-completo.php');
                                        }
                                    ?>
                                        <img src="<?php echo esc_url($imagem_url); ?>" alt="<?php echo esc_attr($painel->titulo); ?>" loading="lazy">
                                    <?php else: ?>
                                        <div class="seminario-imagem-placeholder">
                                            <span>🎯</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="seminario-painel-content">
                                    <h4><?php echo esc_html($painel->titulo); ?></h4>
                                    
                                    <div class="seminario-painel-horario">
                                        <span class="seminario-icon">⏰</span>
                                        <?php echo date('H:i', strtotime($painel->hora_inicio)) . ' às ' . date('H:i', strtotime($painel->hora_fim)); ?>
                                    </div>
                                    
                                    <div class="seminario-painel-local">
                                        <span class="seminario-icon">📍</span>
                                        <?php echo esc_html($painel->local); ?>
                                    </div>
                                    
                                    <div class="seminario-painel-palestrantes">
                                        <span class="seminario-icon">🎤</span>
                                        <strong>Palestrantes:</strong> <?php echo esc_html($painel->palestrantes); ?>
                                    </div>
                                    
                                    <?php if ($painel->mediador): ?>
                                        <div class="seminario-painel-mediador">
                                            <span class="seminario-icon">🎙️</span>
                                            <strong>Mediação:</strong> <?php echo esc_html($painel->mediador); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="seminario-painel-descricao">
                                        <p><?php echo esc_html($painel->descricao); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }
    
    public function shortcode_conferencistas($atts) {
        $conferencistas = $this->database->get_conferencistas();
        
        // Agrupar conferencistas por país para melhor organização
        $conferencistas_por_pais = array();
        foreach ($conferencistas as $conferencista) {
            $pais = !empty($conferencista->pais) ? $conferencista->pais : 'Não informado';
            if (!isset($conferencistas_por_pais[$pais])) {
                $conferencistas_por_pais[$pais] = array();
            }
            $conferencistas_por_pais[$pais][] = $conferencista;
        }
        
        ksort($conferencistas_por_pais);
        
        ob_start();
        ?>
        <div class="seminario-conferencistas-list">
            <h2>Conferencistas Confirmados</h2>
            <p class="seminario-subtitle">Autoridades e especialistas participantes do seminário</p>
            
            <?php foreach ($conferencistas_por_pais as $pais => $conferencistas_grupo): ?>
                <?php if (count($conferencistas_por_pais) > 1): ?>
                    <div class="seminario-pais-section">
                        <h3 class="seminario-pais-header">🌍 <?php echo esc_html($pais); ?></h3>
                <?php endif; ?>
                
                <div class="seminario-conferencistas-grid">
                    <?php foreach ($conferencistas_grupo as $conferencista): ?>
                        <div class="seminario-conferencista-item">
                            <div class="seminario-conferencista-foto">
                                <?php if ($conferencista->foto_url): 
                                    $foto_url = $conferencista->foto_url;
                                    // Se for um caminho relativo, converter para URL absoluto
                                    if (strpos($foto_url, 'http') !== 0 && strpos($foto_url, 'assets/') === 0) {
                                        $foto_url = plugins_url($foto_url, dirname(__FILE__) . '/../seminario-sistema-completo.php');
                                    }
                                ?>
                                    <img src="<?php echo esc_url($foto_url); ?>" alt="<?php echo esc_attr($conferencista->nome); ?>" loading="lazy">
                                <?php else: ?>
                                    <div class="seminario-foto-placeholder">
                                        <span>👤</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="seminario-conferencista-info">
                                <h3><?php echo esc_html($conferencista->nome); ?></h3>
                                <?php if ($conferencista->cargo): ?>
                                    <p class="seminario-cargo"><?php echo esc_html($conferencista->cargo); ?></p>
                                <?php endif; ?>
                                <?php if ($conferencista->instituicao): ?>
                                    <p class="seminario-instituicao">📚 <?php echo esc_html($conferencista->instituicao); ?></p>
                                <?php endif; ?>
                                <?php if ($conferencista->pais && count($conferencistas_por_pais) <= 1): ?>
                                    <p class="seminario-pais">🌍 <?php echo esc_html($conferencista->pais); ?></p>
                                <?php endif; ?>
                                <?php if ($conferencista->biografia): ?>
                                    <div class="seminario-biografia">
                                        <p><?php echo esc_html($conferencista->biografia); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if (count($conferencistas_por_pais) > 1): ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }
    
    public function shortcode_observadores($atts) {
        $observadores = $this->database->get_observadores();
        
        // Separar confirmados e pendentes
        $confirmados = array();
        $pendentes = array();
        
        foreach ($observadores as $observador) {
            if ($observador->status_confirmacao === 'confirmado') {
                $confirmados[] = $observador;
            } else {
                $pendentes[] = $observador;
            }
        }
        
        ob_start();
        ?>
        <div class="seminario-observadores-list">
            <h2>Observadores Internacionais</h2>
            <p class="seminario-subtitle">Especialistas convidados para acompanhar e avaliar o seminário</p>
            
            <?php if (!empty($confirmados)): ?>
                <div class="seminario-status-section">
                    <h3 class="seminario-status-header confirmados">✅ Participação Confirmada</h3>
                    
                    <div class="seminario-observadores-grid">
                        <?php foreach ($confirmados as $observador): ?>
                            <div class="seminario-observador-item">
                                <div class="seminario-observador-foto">
                                    <?php if ($observador->foto_url): 
                                        $foto_url = $observador->foto_url;
                                        // Se for um caminho relativo, converter para URL absoluto
                                        if (strpos($foto_url, 'http') !== 0 && strpos($foto_url, 'assets/') === 0) {
                                            $foto_url = plugins_url($foto_url, dirname(__FILE__) . '/../seminario-sistema-completo.php');
                                        }
                                    ?>
                                        <img src="<?php echo esc_url($foto_url); ?>" alt="<?php echo esc_attr($observador->nome); ?>" loading="lazy">
                                    <?php else: ?>
                                        <div class="seminario-foto-placeholder">
                                            <span>👁️</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="seminario-observador-info">
                                    <h3><?php echo esc_html($observador->nome); ?></h3>
                                    <?php if ($observador->cargo): ?>
                                        <p class="seminario-cargo"><?php echo esc_html($observador->cargo); ?></p>
                                    <?php endif; ?>
                                    <?php if ($observador->instituicao): ?>
                                        <p class="seminario-instituicao">🏛️ <?php echo esc_html($observador->instituicao); ?></p>
                                    <?php endif; ?>
                                    <?php if ($observador->pais): ?>
                                        <p class="seminario-pais">🌍 <?php echo esc_html($observador->pais); ?></p>
                                    <?php endif; ?>
                                    <?php if ($observador->especialidade): ?>
                                        <p class="seminario-especialidade">🎯 <strong>Especialidade:</strong> <?php echo esc_html($observador->especialidade); ?></p>
                                    <?php endif; ?>
                                    <?php if ($observador->biografia): ?>
                                        <div class="seminario-biografia">
                                            <p><?php echo esc_html($observador->biografia); ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($pendentes)): ?>
                <div class="seminario-status-section">
                    <h3 class="seminario-status-header pendentes">⏳ Aguardando Confirmação</h3>
                    
                    <div class="seminario-observadores-grid">
                        <?php foreach ($pendentes as $observador): ?>
                            <div class="seminario-observador-item pendente">
                                <div class="seminario-observador-foto">
                                    <?php if ($observador->foto_url): 
                                        $foto_url = $observador->foto_url;
                                        if (strpos($foto_url, 'http') !== 0 && strpos($foto_url, 'assets/') === 0) {
                                            $foto_url = plugins_url($foto_url, dirname(__FILE__) . '/../seminario-sistema-completo.php');
                                        }
                                    ?>
                                        <img src="<?php echo esc_url($foto_url); ?>" alt="<?php echo esc_attr($observador->nome); ?>" loading="lazy">
                                    <?php else: ?>
                                        <div class="seminario-foto-placeholder">
                                            <span>👁️</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="seminario-observador-info">
                                    <h3><?php echo esc_html($observador->nome); ?></h3>
                                    <?php if ($observador->cargo): ?>
                                        <p class="seminario-cargo"><?php echo esc_html($observador->cargo); ?></p>
                                    <?php endif; ?>
                                    <?php if ($observador->instituicao): ?>
                                        <p class="seminario-instituicao">🏛️ <?php echo esc_html($observador->instituicao); ?></p>
                                    <?php endif; ?>
                                    <?php if ($observador->pais): ?>
                                        <p class="seminario-pais">🌍 <?php echo esc_html($observador->pais); ?></p>
                                    <?php endif; ?>
                                    <?php if ($observador->especialidade): ?>
                                        <p class="seminario-especialidade">🎯 <strong>Especialidade:</strong> <?php echo esc_html($observador->especialidade); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }
}
