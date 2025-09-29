<?php
class SeminarioAdmin {
    
    private $database;
    
    public function __construct() {
        $this->database = new SeminarioDatabase();
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }
    
    public function enqueue_admin_scripts() {
        wp_enqueue_style('seminario-admin-css', plugins_url('assets/seminario-modern.css', dirname(__FILE__)));
        wp_enqueue_script('seminario-admin-js', plugins_url('assets/frontend.js', dirname(__FILE__)), array('jquery'));
        
        // Enqueue WordPress media uploader
        wp_enqueue_media();
        wp_enqueue_script('seminario-media-upload', plugins_url('assets/media-upload.js', dirname(__FILE__)), array('jquery'), '1.0', true);
    }
    
    public function add_admin_menu() {
        add_menu_page(
            'Seminário Reparações',
            'Seminário',
            'manage_options',
            'seminario-dashboard',
            array($this, 'dashboard_page'),
            'dashicons-groups',
            30
        );
        
        add_submenu_page(
            'seminario-dashboard',
            'Dashboard',
            'Dashboard',
            'manage_options',
            'seminario-dashboard',
            array($this, 'dashboard_page')
        );
        
        add_submenu_page(
            'seminario-dashboard',
            'Inscrições',
            'Inscrições',
            'manage_options',
            'seminario-inscricoes',
            array($this, 'inscricoes_page')
        );
        
        add_submenu_page(
            'seminario-dashboard',
            'Painéis',
            'Painéis',
            'manage_options',
            'seminario-paineis',
            array($this, 'paineis_page')
        );
        
        add_submenu_page(
            'seminario-dashboard',
            'Conferencistas',
            'Conferencistas',
            'manage_options',
            'seminario-conferencistas',
            array($this, 'conferencistas_page')
        );
        
        add_submenu_page(
            'seminario-dashboard',
            'Observadores',
            'Observadores',
            'manage_options',
            'seminario-observadores',
            array($this, 'observadores_page')
        );
        
        add_submenu_page(
            'seminario-dashboard',
            'Configurações',
            'Configurações',
            'manage_options',
            'seminario-configuracoes',
            array($this, 'configuracoes_page')
        );
        
        add_submenu_page(
            'seminario-dashboard',
            'Locais',
            'Locais',
            'manage_options',
            'seminario-locais',
            array($this, 'locais_page')
        );
    }
    
    public function dashboard_page() {
        $stats = $this->database->get_estatisticas();
        ?>
        <div class="wrap seminario-admin">
            <div class="seminario-header">
                <h1>🎯 Dashboard - II Seminário Internacional Pró-Reparações</h1>
                <p>Um Projeto de Nação • 10-14 de Novembro de 2025 • Belo Horizonte - MG</p>
            </div>
            
            <div class="seminario-grid seminario-grid-4">
                <div class="seminario-stat card-stats">
                    <div class="seminario-stat-icon">📊</div>
                    <div class="seminario-stat-number"><?php echo $stats['total_inscricoes']; ?></div>
                    <div class="seminario-stat-label">Total de Inscrições</div>
                </div>
                <div class="seminario-stat card-royal">
                    <div class="seminario-stat-icon">📈</div>
                    <div class="seminario-stat-number"><?php echo $stats['inscricoes_hoje']; ?></div>
                    <div class="seminario-stat-label">Inscrições Hoje</div>
                </div>
                <div class="seminario-stat card-earth">
                    <div class="seminario-stat-icon">📅</div>
                    <div class="seminario-stat-number"><?php echo $stats['inscricoes_semana']; ?></div>
                    <div class="seminario-stat-label">Esta Semana</div>
                </div>
                <div class="seminario-stat" style="background: var(--gradient-warm);">
                    <div class="seminario-stat-icon">🌍</div>
                    <div class="seminario-stat-number" style="font-size: 24px;"><?php echo $stats['pais_mais_comum'] ?: 'N/A'; ?></div>
                    <div class="seminario-stat-label">País Mais Comum</div>
                </div>
            </div>
            
            <div class="seminario-stats">
                <div class="seminario-stat-card">
                    <h3>Total de Painéis</h3>
                    <div class="seminario-stat-number"><?php echo $stats['total_paineis']; ?></div>
                </div>
                <div class="seminario-stat-card">
                    <h3>Conferencistas</h3>
                    <div class="seminario-stat-number"><?php echo $stats['total_conferencistas']; ?></div>
                </div>
                <div class="seminario-stat-card">
                    <h3>Observadores</h3>
                    <div class="seminario-stat-number"><?php echo $stats['total_observadores']; ?></div>
                </div>
            </div>
            
            <div class="seminario-info">
                <h2>Informações do Evento</h2>
                <p><strong>Nome:</strong> II Seminário Internacional Pró-Reparações: Um Projeto de Nação</p>
                <p><strong>Data:</strong> 10 a 14 de Novembro de 2025</p>
                <p><strong>Local:</strong> Belo Horizonte - MG</p>
                <p><strong>Organização:</strong> Coletivo Minas Pró-Reparações</p>
            </div>
            
            <div class="seminario-shortcodes">
                <h2>Shortcodes Disponíveis</h2>
                <p><code>[seminario_inscricao]</code> - Formulário de inscrição</p>
                <p><code>[seminario_paineis]</code> - Lista de painéis</p>
                <p><code>[seminario_conferencistas]</code> - Lista de conferencistas</p>
                <p><code>[seminario_observadores]</code> - Lista de observadores</p>
            </div>
        </div>
        <?php
    }
    
    public function inscricoes_page() {
        // Processar ações CRUD
        if (isset($_POST['action'])) {
            if ($_POST['action'] == 'create') {
                $data = array(
                    'nome' => sanitize_text_field($_POST['nome']),
                    'email' => sanitize_email($_POST['email']),
                    'telefone' => sanitize_text_field($_POST['telefone']),
                    'pais' => sanitize_text_field($_POST['pais']),
                    'cidade' => sanitize_text_field($_POST['cidade']),
                    'instituicao' => sanitize_text_field($_POST['instituicao']),
                    'cargo' => sanitize_text_field($_POST['cargo']),
                    'area_interesse' => sanitize_textarea_field($_POST['area_interesse']),
                    'necessidades_especiais' => sanitize_textarea_field($_POST['necessidades_especiais']),
                    'paineis_escolhidos' => isset($_POST['paineis_escolhidos']) ? implode(',', array_map('intval', $_POST['paineis_escolhidos'])) : ''
                );
                $this->database->create_inscricao($data);
                echo '<div class="notice notice-success"><p>Inscrição criada com sucesso!</p></div>';
            } elseif ($_POST['action'] == 'update') {
                $data = array(
                    'nome' => sanitize_text_field($_POST['nome']),
                    'email' => sanitize_email($_POST['email']),
                    'telefone' => sanitize_text_field($_POST['telefone']),
                    'pais' => sanitize_text_field($_POST['pais']),
                    'cidade' => sanitize_text_field($_POST['cidade']),
                    'instituicao' => sanitize_text_field($_POST['instituicao']),
                    'cargo' => sanitize_text_field($_POST['cargo']),
                    'area_interesse' => sanitize_textarea_field($_POST['area_interesse']),
                    'necessidades_especiais' => sanitize_textarea_field($_POST['necessidades_especiais']),
                    'paineis_escolhidos' => isset($_POST['paineis_escolhidos']) ? implode(',', array_map('intval', $_POST['paineis_escolhidos'])) : ''
                );
                $this->database->update_inscricao(intval($_POST['id']), $data);
                echo '<div class="notice notice-success"><p>Inscrição atualizada com sucesso!</p></div>';
            }
        }
        
        if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
            $this->database->delete_inscricao(intval($_GET['id']));
            echo '<div class="notice notice-success"><p>Inscrição excluída com sucesso!</p></div>';
        }
        
        // Mostrar formulário de edição
        if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
            $this->edit_inscricao_form(intval($_GET['id']));
            return;
        }
        
        // Mostrar formulário de nova inscrição
        if (isset($_GET['action']) && $_GET['action'] == 'new') {
            $this->add_inscricao_form();
            return;
        }
        
        $search = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
        $inscricoes = $this->database->get_inscricoes($search);
        $stats = $this->database->get_estatisticas();
        ?>
        <div class="wrap seminario-admin">
            <div class="seminario-header">
                <h1>📝 Gestão de Inscrições</h1>
                <p>CRUD completo com busca e filtros</p>
            </div>
            
            <!-- Blocos de Estatísticas -->
            <div class="seminario-grid seminario-grid-3" style="margin-bottom: 24px;">
                <div class="seminario-stat card-royal">
                    <div class="seminario-stat-icon">📊</div>
                    <div class="seminario-stat-number"><?php echo count($inscricoes); ?></div>
                    <div class="seminario-stat-label">Total de Inscrições</div>
                </div>
                <div class="seminario-stat card-earth">
                    <div class="seminario-stat-icon">📈</div>
                    <div class="seminario-stat-number"><?php echo $stats['inscricoes_hoje']; ?></div>
                    <div class="seminario-stat-label">Inscrições Hoje</div>
                </div>
                <div class="seminario-stat card-stats">
                    <div class="seminario-stat-icon">⏰</div>
                    <div class="seminario-stat-number" style="font-size: 18px;">
                        <?php 
                        if (isset($stats['horario_popular']->hora_inicio)) {
                            echo date('H:i', strtotime($stats['horario_popular']->hora_inicio));
                        } else {
                            echo 'N/A';
                        }
                        ?>
                    </div>
                    <div class="seminario-stat-label">Horário Mais Procurado</div>
                </div>
            </div>
            
            <div class="seminario-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <form method="get" style="display: flex; gap: 12px; align-items: center; flex: 1;">
                        <input type="hidden" name="page" value="seminario-inscricoes">
                        <input type="search" name="s" value="<?php echo esc_attr($search); ?>" placeholder="Buscar por nome, email ou país..." class="seminario-input" style="flex: 1;">
                        <button type="submit" class="seminario-btn seminario-btn-primary">🔍 Buscar</button>
                    </form>
                    <a href="?page=seminario-inscricoes&action=new" class="seminario-btn seminario-btn-success">➕ Nova Inscrição</a>
                </div>
            </div>
            
            <div class="seminario-card">
                <table class="seminario-table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>País</th>
                        <th>Painéis Escolhidos</th>
                        <th>Data Inscrição</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($inscricoes)): ?>
                        <tr>
                            <td colspan="6" class="seminario-empty-state">Nenhuma inscrição encontrada.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($inscricoes as $inscricao): ?>
                            <tr>
                                <td><strong><?php echo esc_html($inscricao->nome); ?></strong></td>
                                <td><?php echo esc_html($inscricao->email); ?></td>
                                <td><?php echo esc_html($inscricao->pais); ?></td>
                                <td>
                                    <?php 
                                    if ($inscricao->paineis_escolhidos) {
                                        $paineis_ids = explode(',', $inscricao->paineis_escolhidos);
                                        $paineis = $this->database->get_paineis();
                                        foreach ($paineis_ids as $painel_id) {
                                            foreach ($paineis as $painel) {
                                                if ($painel->id == $painel_id) {
                                                    echo '<span class="seminario-painel-tag">' . esc_html(substr($painel->titulo, 0, 30)) . '...</span> ';
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($inscricao->data_inscricao)); ?></td>
                                <td>
                                    <a href="?page=seminario-inscricoes&action=edit&id=<?php echo $inscricao->id; ?>" class="seminario-btn seminario-btn-primary">✏️ Editar</a>
                                    <a href="?page=seminario-inscricoes&action=delete&id=<?php echo $inscricao->id; ?>" 
                                       class="seminario-btn seminario-btn-danger" 
                                       onclick="return confirm('Tem certeza que deseja excluir esta inscrição?')">🗑️ Excluir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Modal para detalhes da inscrição -->
        <div id="seminario-modal" class="seminario-modal">
            <div class="seminario-modal-content">
                <span class="seminario-modal-close">&times;</span>
                <div id="seminario-modal-body"></div>
            </div>
        </div>
        <?php
    }
    
    public function paineis_page() {
        // Processar ações CRUD
        if (isset($_POST['action'])) {
            if ($_POST['action'] == 'create') {
                $data = array(
                    'titulo' => sanitize_text_field($_POST['titulo']),
                    'descricao' => sanitize_textarea_field($_POST['descricao']),
                    'data_painel' => sanitize_text_field($_POST['data_painel']),
                    'hora_inicio' => sanitize_text_field($_POST['hora_inicio']),
                    'hora_fim' => sanitize_text_field($_POST['hora_fim']),
                    'local' => sanitize_text_field($_POST['local']),
                    'palestrantes' => sanitize_textarea_field($_POST['palestrantes']),
                    'mediador' => sanitize_text_field($_POST['mediador']),
                    'imagem_url' => sanitize_url($_POST['imagem_url'])
                );
                $this->database->create_painel($data);
                echo '<div class="notice notice-success"><p>Painel criado com sucesso!</p></div>';
            } elseif ($_POST['action'] == 'update') {
                $data = array(
                    'titulo' => sanitize_text_field($_POST['titulo']),
                    'descricao' => sanitize_textarea_field($_POST['descricao']),
                    'data_painel' => sanitize_text_field($_POST['data_painel']),
                    'hora_inicio' => sanitize_text_field($_POST['hora_inicio']),
                    'hora_fim' => sanitize_text_field($_POST['hora_fim']),
                    'local' => sanitize_text_field($_POST['local']),
                    'palestrantes' => sanitize_textarea_field($_POST['palestrantes']),
                    'mediador' => sanitize_text_field($_POST['mediador']),
                    'imagem_url' => sanitize_url($_POST['imagem_url'])
                );
                $this->database->update_painel(intval($_POST['id']), $data);
                echo '<div class="notice notice-success"><p>Painel atualizado com sucesso!</p></div>';
            }
        }
        
        if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
            $this->database->delete_painel(intval($_GET['id']));
            echo '<div class="notice notice-success"><p>Painel excluído com sucesso!</p></div>';
        }
        
        // Mostrar formulário de edição
        if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
            $this->edit_painel_form(intval($_GET['id']));
            return;
        }
        
        // Mostrar formulário de novo painel
        if (isset($_GET['action']) && $_GET['action'] == 'new') {
            $this->add_painel_form();
            return;
        }
        
        $paineis = $this->database->get_paineis();
        $stats = $this->database->get_estatisticas();
        ?>
        <div class="wrap seminario-admin">
            <div class="seminario-header">
                <h1>🎯 Painéis Temáticos</h1>
                <p>6 painéis oficiais com palestrantes confirmados</p>
            </div>
            
            <!-- Blocos de Estatísticas -->
            <div class="seminario-grid seminario-grid-3" style="margin-bottom: 24px;">
                <div class="seminario-stat card-royal">
                    <div class="seminario-stat-icon">📊</div>
                    <div class="seminario-stat-number"><?php echo count($paineis); ?></div>
                    <div class="seminario-stat-label">Total de Painéis</div>
                </div>
                <div class="seminario-stat card-earth">
                    <div class="seminario-stat-icon">👥</div>
                    <div class="seminario-stat-number">
                        <?php 
                        $painel_popular = isset($stats['inscricoes_por_painel'][0]) ? $stats['inscricoes_por_painel'][0]->total_inscricoes : 0;
                        echo $painel_popular;
                        ?>
                    </div>
                    <div class="seminario-stat-label">Painel Mais Popular</div>
                </div>
                <div class="seminario-stat card-stats">
                    <div class="seminario-stat-icon">⏰</div>
                    <div class="seminario-stat-number" style="font-size: 18px;">
                        <?php 
                        if (isset($stats['horario_popular']->hora_inicio)) {
                            echo date('H:i', strtotime($stats['horario_popular']->hora_inicio));
                        } else {
                            echo 'N/A';
                        }
                        ?>
                    </div>
                    <div class="seminario-stat-label">Horário Mais Procurado</div>
                </div>
            </div>
            
            <div class="seminario-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <h2>Gerenciar Painéis</h2>
                    <a href="?page=seminario-paineis&action=new" class="seminario-btn seminario-btn-success">➕ Novo Painel</a>
                </div>
            </div>
            
            <div class="seminario-grid seminario-grid-2">
                <?php foreach ($paineis as $painel): ?>
                    <div class="seminario-card">
                        <h3><?php echo esc_html($painel->titulo); ?></h3>
                        <p><strong>Data:</strong> <?php echo date('d/m/Y', strtotime($painel->data_painel)); ?></p>
                        <p><strong>Horário:</strong> <?php echo date('H:i', strtotime($painel->hora_inicio)) . ' às ' . date('H:i', strtotime($painel->hora_fim)); ?></p>
                        <p><strong>Local:</strong> <?php echo esc_html($painel->local); ?></p>
                        <p><strong>Palestrantes:</strong> <?php echo esc_html($painel->palestrantes); ?></p>
                        <?php if ($painel->mediador): ?>
                            <p><strong>Mediador:</strong> <?php echo esc_html($painel->mediador); ?></p>
                        <?php endif; ?>
                        <p><?php echo esc_html(substr($painel->descricao, 0, 150)) . '...'; ?></p>
                        
                        <div class="seminario-card-actions" style="margin-top: 16px; display: flex; gap: 8px;">
                            <a href="?page=seminario-paineis&action=edit&id=<?php echo $painel->id; ?>" class="seminario-btn seminario-btn-primary">✏️ Editar</a>
                            <a href="?page=seminario-paineis&action=delete&id=<?php echo $painel->id; ?>" 
                               class="seminario-btn seminario-btn-danger" 
                               onclick="return confirm('Tem certeza que deseja excluir este painel?')">🗑️ Excluir</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
    
    public function conferencistas_page() {
        // Processar ações CRUD
        if (isset($_POST['action'])) {
            if ($_POST['action'] == 'create') {
                $data = array(
                    'nome' => sanitize_text_field($_POST['nome']),
                    'cargo' => sanitize_text_field($_POST['cargo']),
                    'instituicao' => sanitize_text_field($_POST['instituicao']),
                    'pais' => sanitize_text_field($_POST['pais']),
                    'biografia' => sanitize_textarea_field($_POST['biografia']),
                    'foto_url' => sanitize_url($_POST['foto_url']),
                    'paineis_participacao' => sanitize_text_field($_POST['paineis_participacao']),
                    'tipo' => sanitize_text_field($_POST['tipo'])
                );
                $this->database->create_conferencista($data);
                echo '<div class="notice notice-success"><p>Conferencista criado com sucesso!</p></div>';
            } elseif ($_POST['action'] == 'update') {
                $data = array(
                    'nome' => sanitize_text_field($_POST['nome']),
                    'cargo' => sanitize_text_field($_POST['cargo']),
                    'instituicao' => sanitize_text_field($_POST['instituicao']),
                    'pais' => sanitize_text_field($_POST['pais']),
                    'biografia' => sanitize_textarea_field($_POST['biografia']),
                    'foto_url' => sanitize_url($_POST['foto_url']),
                    'paineis_participacao' => sanitize_text_field($_POST['paineis_participacao']),
                    'tipo' => sanitize_text_field($_POST['tipo'])
                );
                $this->database->update_conferencista(intval($_POST['id']), $data);
                echo '<div class="notice notice-success"><p>Conferencista atualizado com sucesso!</p></div>';
            }
        }
        
        if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
            $this->database->delete_conferencista(intval($_GET['id']));
            echo '<div class="notice notice-success"><p>Conferencista excluído com sucesso!</p></div>';
        }
        
        // Mostrar formulário de edição
        if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
            $this->edit_conferencista_form(intval($_GET['id']));
            return;
        }
        
        // Mostrar formulário de novo conferencista
        if (isset($_GET['action']) && $_GET['action'] == 'new') {
            $this->add_conferencista_form();
            return;
        }
        
        $conferencistas = $this->database->get_conferencistas();
        $stats = $this->database->get_estatisticas();
        ?>
        <div class="wrap seminario-admin">
            <div class="seminario-header">
                <h1>🎤 Conferencistas</h1>
                <p>Biografias autênticas das autoridades participantes</p>
            </div>
            
            <!-- Blocos de Estatísticas -->
            <div class="seminario-grid seminario-grid-3" style="margin-bottom: 24px;">
                <div class="seminario-stat card-royal">
                    <div class="seminario-stat-icon">🎤</div>
                    <div class="seminario-stat-number"><?php echo count($conferencistas); ?></div>
                    <div class="seminario-stat-label">Total de Conferencistas</div>
                </div>
                <div class="seminario-stat card-earth">
                    <div class="seminario-stat-icon">✅</div>
                    <div class="seminario-stat-number"><?php echo $stats['conferencistas_confirmados']; ?></div>
                    <div class="seminario-stat-label">Confirmados</div>
                </div>
                <div class="seminario-stat card-stats">
                    <div class="seminario-stat-icon">⏳</div>
                    <div class="seminario-stat-number"><?php echo $stats['conferencistas_pendentes']; ?></div>
                    <div class="seminario-stat-label">Em Espera</div>
                </div>
            </div>
            
            <div class="seminario-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <h2>Gerenciar Conferencistas</h2>
                    <a href="?page=seminario-conferencistas&action=new" class="seminario-btn seminario-btn-success">➕ Novo Conferencista</a>
                </div>
            </div>
            
            <div class="seminario-grid seminario-grid-3">
                <?php foreach ($conferencistas as $conferencista): ?>
                    <div class="seminario-profile">
                        <div class="seminario-conferencista-foto">
                            <?php if ($conferencista->foto_url): 
                                $foto_url = $conferencista->foto_url;
                                // Se for um caminho relativo, converter para URL absoluto
                                if (strpos($foto_url, 'http') !== 0 && strpos($foto_url, 'assets/') === 0) {
                                    $foto_url = plugins_url($foto_url, dirname(__FILE__) . '/../seminario-sistema-completo.php');
                                }
                            ?>
                                <img src="<?php echo esc_url($foto_url); ?>" alt="<?php echo esc_attr($conferencista->nome); ?>">
                            <?php else: ?>
                                <div style="display: flex; align-items: center; justify-content: center; height: 100%; background: #f0f0f0; color: #666;">
                                    Foto não disponível
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="seminario-profile-info">
                            <h3><?php echo esc_html($conferencista->nome); ?></h3>
                            <div class="seminario-profile-cargo"><?php echo esc_html($conferencista->cargo); ?></div>
                            <div class="seminario-profile-instituicao"><?php echo esc_html($conferencista->instituicao); ?></div>
                            <div class="seminario-profile-pais"><?php echo esc_html($conferencista->pais); ?></div>
                            <div style="margin-top: 8px; color: var(--seminario-gray); font-size: 14px;"><?php echo esc_html(substr($conferencista->biografia, 0, 150)) . '...'; ?></div>
                            
                            <div class="seminario-card-actions" style="margin-top: 16px; display: flex; gap: 8px;">
                                <a href="?page=seminario-conferencistas&action=edit&id=<?php echo $conferencista->id; ?>" class="seminario-btn seminario-btn-primary">✏️ Editar</a>
                                <a href="?page=seminario-conferencistas&action=delete&id=<?php echo $conferencista->id; ?>" 
                                   class="seminario-btn seminario-btn-danger" 
                                   onclick="return confirm('Tem certeza que deseja excluir este conferencista?')">🗑️ Excluir</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
    
    public function observadores_page() {
        // Processar ações CRUD
        if (isset($_POST['action'])) {
            if ($_POST['action'] == 'create') {
                $data = array(
                    'nome' => sanitize_text_field($_POST['nome']),
                    'cargo' => sanitize_text_field($_POST['cargo']),
                    'instituicao' => sanitize_text_field($_POST['instituicao']),
                    'pais' => sanitize_text_field($_POST['pais']),
                    'biografia' => sanitize_textarea_field($_POST['biografia']),
                    'foto_url' => sanitize_url($_POST['foto_url']),
                    'area_especialidade' => sanitize_text_field($_POST['area_especialidade']),
                    'status' => sanitize_text_field($_POST['status'])
                );
                $this->database->create_observador($data);
                echo '<div class="notice notice-success"><p>Observador criado com sucesso!</p></div>';
            } elseif ($_POST['action'] == 'update') {
                $data = array(
                    'nome' => sanitize_text_field($_POST['nome']),
                    'cargo' => sanitize_text_field($_POST['cargo']),
                    'instituicao' => sanitize_text_field($_POST['instituicao']),
                    'pais' => sanitize_text_field($_POST['pais']),
                    'biografia' => sanitize_textarea_field($_POST['biografia']),
                    'foto_url' => sanitize_url($_POST['foto_url']),
                    'area_especialidade' => sanitize_text_field($_POST['area_especialidade']),
                    'status' => sanitize_text_field($_POST['status'])
                );
                $this->database->update_observador(intval($_POST['id']), $data);
                echo '<div class="notice notice-success"><p>Observador atualizado com sucesso!</p></div>';
            }
        }
        
        if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
            $this->database->delete_observador(intval($_GET['id']));
            echo '<div class="notice notice-success"><p>Observador excluído com sucesso!</p></div>';
        }
        
        // Mostrar formulário de edição
        if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
            $this->edit_observador_form(intval($_GET['id']));
            return;
        }
        
        // Mostrar formulário de novo observador
        if (isset($_GET['action']) && $_GET['action'] == 'new') {
            $this->add_observador_form();
            return;
        }
        
        $observadores = $this->database->get_observadores();
        ?>
        <div class="wrap seminario-admin">
            <div class="seminario-header">
                <h1>👁️ Observadores Internacionais</h1>
                <p>Acadêmicos de 8 países</p>
            </div>
            
            <div class="seminario-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <h2>Gerenciar Observadores</h2>
                    <a href="?page=seminario-observadores&action=new" class="seminario-btn seminario-btn-success">➕ Novo Observador</a>
                </div>
            </div>
            
            <div class="seminario-grid seminario-grid-3">
                <?php foreach ($observadores as $observador): ?>
                    <div class="seminario-profile">
                        <div class="seminario-observador-foto">
                            <?php if ($observador->foto_url): 
                                $foto_url = $observador->foto_url;
                                // Se for um caminho relativo, converter para URL absoluto
                                if (strpos($foto_url, 'http') !== 0 && strpos($foto_url, 'assets/') === 0) {
                                    $foto_url = plugins_url($foto_url, dirname(__FILE__) . '/../seminario-sistema-completo.php');
                                }
                            ?>
                                <img src="<?php echo esc_url($foto_url); ?>" alt="<?php echo esc_attr($observador->nome); ?>">
                            <?php else: ?>
                                <div style="display: flex; align-items: center; justify-content: center; height: 100%; background: #f0f0f0; color: #666;">
                                    Foto não disponível
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="seminario-profile-info">
                            <h3><?php echo esc_html($observador->nome); ?></h3>
                            <div class="seminario-profile-cargo"><?php echo esc_html($observador->cargo); ?></div>
                            <div class="seminario-profile-instituicao"><?php echo esc_html($observador->instituicao); ?></div>
                            <div class="seminario-profile-pais"><?php echo esc_html($observador->pais); ?></div>
                            <div style="margin-top: 8px; color: var(--seminario-gray); font-size: 14px;"><?php echo esc_html(substr($observador->biografia, 0, 150)) . '...'; ?></div>
                            
                            <div class="seminario-card-actions" style="margin-top: 16px; display: flex; gap: 8px;">
                                <a href="?page=seminario-observadores&action=edit&id=<?php echo $observador->id; ?>" class="seminario-btn seminario-btn-primary">✏️ Editar</a>
                                <a href="?page=seminario-observadores&action=delete&id=<?php echo $observador->id; ?>" 
                                   class="seminario-btn seminario-btn-danger" 
                                   onclick="return confirm('Tem certeza que deseja excluir este observador?')">🗑️ Excluir</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
    
    public function configuracoes_page() {
        if (isset($_POST['submit'])) {
            $this->database->update_configuracao('evento_nome', sanitize_text_field($_POST['evento_nome']));
            $this->database->update_configuracao('evento_data_inicio', sanitize_text_field($_POST['evento_data_inicio']));
            $this->database->update_configuracao('evento_data_fim', sanitize_text_field($_POST['evento_data_fim']));
            $this->database->update_configuracao('inscricoes_abertas', intval($_POST['inscricoes_abertas']));
            $this->database->update_configuracao('limite_paineis_por_pessoa', intval($_POST['limite_paineis_por_pessoa']));
            $this->database->update_configuracao('email_confirmacao', intval($_POST['email_confirmacao']));
            $this->database->update_configuracao('organizacao_principal', sanitize_text_field($_POST['organizacao_principal']));
            
            echo '<div class="notice notice-success"><p>Configurações salvas com sucesso!</p></div>';
        }
        
        $evento_nome = $this->database->get_configuracao('evento_nome');
        $evento_data_inicio = $this->database->get_configuracao('evento_data_inicio');
        $evento_data_fim = $this->database->get_configuracao('evento_data_fim');
        $inscricoes_abertas = $this->database->get_configuracao('inscricoes_abertas');
        $limite_paineis = $this->database->get_configuracao('limite_paineis_por_pessoa');
        $email_confirmacao = $this->database->get_configuracao('email_confirmacao');
        $organizacao_principal = $this->database->get_configuracao('organizacao_principal');
        ?>
        <div class="wrap seminario-admin">
            <div class="seminario-header">
                <h1>⚙️ Configurações do Seminário</h1>
                <p>Configuração principal do evento</p>
            </div>
            
            <div class="seminario-card">
                <form method="post" action="">
                <table class="form-table">
                    <tr>
                        <th scope="row">Nome do Evento</th>
                        <td>
                            <input type="text" name="evento_nome" value="<?php echo esc_attr($evento_nome); ?>" class="regular-text" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Data de Início</th>
                        <td>
                            <input type="date" name="evento_data_inicio" value="<?php echo esc_attr($evento_data_inicio); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Data de Término</th>
                        <td>
                            <input type="date" name="evento_data_fim" value="<?php echo esc_attr($evento_data_fim); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Inscrições Abertas</th>
                        <td>
                            <label>
                                <input type="checkbox" name="inscricoes_abertas" value="1" <?php checked($inscricoes_abertas, 1); ?> />
                                Permitir novas inscrições
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Limite de Painéis por Pessoa</th>
                        <td>
                            <input type="number" name="limite_paineis_por_pessoa" value="<?php echo esc_attr($limite_paineis); ?>" min="1" max="10" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Email de Confirmação</th>
                        <td>
                            <label>
                                <input type="checkbox" name="email_confirmacao" value="1" <?php checked($email_confirmacao, 1); ?> />
                                Enviar email de confirmação automático
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Organização Principal</th>
                        <td>
                            <input type="text" name="organizacao_principal" value="<?php echo esc_attr($organizacao_principal); ?>" class="regular-text" />
                        </td>
                    </tr>
                </table>
                
                <?php submit_button('Salvar Configurações'); ?>
            </form>
        </div>
        <?php
    }
    
    public function add_inscricao_form() {
        $paineis = $this->database->get_paineis();
        ?>
        <div class="wrap seminario-admin">
            <div class="seminario-header">
                <h1>➕ Nova Inscrição</h1>
                <p>Adicionar nova inscrição no sistema</p>
            </div>
            
            <div class="seminario-card">
                <form method="post" action="">
                    <input type="hidden" name="action" value="create">
                    
                    <div class="seminario-form-grid">
                        <div class="seminario-form-group">
                            <label for="nome">Nome Completo *</label>
                            <input type="text" id="nome" name="nome" required class="seminario-input">
                        </div>
                        
                        <div class="seminario-form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" required class="seminario-input">
                        </div>
                        
                        <div class="seminario-form-group">
                            <label for="telefone">Telefone</label>
                            <input type="tel" id="telefone" name="telefone" class="seminario-input">
                        </div>
                        
                        <div class="seminario-form-group">
                            <label for="pais">País</label>
                            <input type="text" id="pais" name="pais" class="seminario-input">
                        </div>
                        
                        <div class="seminario-form-group">
                            <label for="cidade">Cidade</label>
                            <input type="text" id="cidade" name="cidade" class="seminario-input">
                        </div>
                        
                        <div class="seminario-form-group">
                            <label for="instituicao">Instituição</label>
                            <input type="text" id="instituicao" name="instituicao" class="seminario-input">
                        </div>
                    </div>
                    
                    <div class="seminario-form-group">
                        <label for="cargo">Cargo/Função</label>
                        <input type="text" id="cargo" name="cargo" class="seminario-input">
                    </div>
                    
                    <div class="seminario-form-group">
                        <label for="area_interesse">Área de Interesse</label>
                        <textarea id="area_interesse" name="area_interesse" rows="3" class="seminario-input"></textarea>
                    </div>
                    
                    <div class="seminario-form-group">
                        <label>Painéis de Interesse</label>
                        <div class="seminario-checkbox-grid">
                            <?php foreach ($paineis as $painel): ?>
                                <label class="seminario-checkbox-item">
                                    <input type="checkbox" name="paineis_escolhidos[]" value="<?php echo $painel->id; ?>">
                                    <strong><?php echo esc_html($painel->titulo); ?></strong><br>
                                    <small><?php echo date('d/m/Y H:i', strtotime($painel->data_painel . ' ' . $painel->hora_inicio)); ?></small>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="seminario-form-group">
                        <label for="necessidades_especiais">Necessidades Especiais</label>
                        <textarea id="necessidades_especiais" name="necessidades_especiais" rows="3" class="seminario-input" placeholder="Descreva qualquer necessidade especial"></textarea>
                    </div>
                    
                    <div class="seminario-form-actions">
                        <button type="submit" class="seminario-btn seminario-btn-success">💾 Salvar Inscrição</button>
                        <a href="?page=seminario-inscricoes" class="seminario-btn seminario-btn-secondary">↩️ Voltar</a>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
    
    public function edit_inscricao_form($id) {
        $inscricao = $this->database->get_inscricao($id);
        $paineis = $this->database->get_paineis();
        $paineis_selecionados = $inscricao ? explode(',', $inscricao->paineis_escolhidos) : array();
        
        if (!$inscricao) {
            echo '<div class="notice notice-error"><p>Inscrição não encontrada!</p></div>';
            return;
        }
        ?>
        <div class="wrap seminario-admin">
            <div class="seminario-header">
                <h1>✏️ Editar Inscrição</h1>
                <p>Modificar dados da inscrição de <?php echo esc_html($inscricao->nome); ?></p>
            </div>
            
            <div class="seminario-card">
                <form method="post" action="">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" value="<?php echo $inscricao->id; ?>">
                    
                    <div class="seminario-form-grid">
                        <div class="seminario-form-group">
                            <label for="nome">Nome Completo *</label>
                            <input type="text" id="nome" name="nome" value="<?php echo esc_attr($inscricao->nome); ?>" required class="seminario-input">
                        </div>
                        
                        <div class="seminario-form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" value="<?php echo esc_attr($inscricao->email); ?>" required class="seminario-input">
                        </div>
                        
                        <div class="seminario-form-group">
                            <label for="telefone">Telefone</label>
                            <input type="tel" id="telefone" name="telefone" value="<?php echo esc_attr($inscricao->telefone); ?>" class="seminario-input">
                        </div>
                        
                        <div class="seminario-form-group">
                            <label for="pais">País</label>
                            <input type="text" id="pais" name="pais" value="<?php echo esc_attr($inscricao->pais); ?>" class="seminario-input">
                        </div>
                        
                        <div class="seminario-form-group">
                            <label for="cidade">Cidade</label>
                            <input type="text" id="cidade" name="cidade" value="<?php echo esc_attr($inscricao->cidade); ?>" class="seminario-input">
                        </div>
                        
                        <div class="seminario-form-group">
                            <label for="instituicao">Instituição</label>
                            <input type="text" id="instituicao" name="instituicao" value="<?php echo esc_attr($inscricao->instituicao); ?>" class="seminario-input">
                        </div>
                    </div>
                    
                    <div class="seminario-form-group">
                        <label for="cargo">Cargo/Função</label>
                        <input type="text" id="cargo" name="cargo" value="<?php echo esc_attr($inscricao->cargo); ?>" class="seminario-input">
                    </div>
                    
                    <div class="seminario-form-group">
                        <label for="area_interesse">Área de Interesse</label>
                        <textarea id="area_interesse" name="area_interesse" rows="3" class="seminario-input"><?php echo esc_textarea($inscricao->area_interesse); ?></textarea>
                    </div>
                    
                    <div class="seminario-form-group">
                        <label>Painéis de Interesse</label>
                        <div class="seminario-checkbox-grid">
                            <?php foreach ($paineis as $painel): ?>
                                <label class="seminario-checkbox-item">
                                    <input type="checkbox" name="paineis_escolhidos[]" value="<?php echo $painel->id; ?>" <?php checked(in_array($painel->id, $paineis_selecionados)); ?>>
                                    <strong><?php echo esc_html($painel->titulo); ?></strong><br>
                                    <small><?php echo date('d/m/Y H:i', strtotime($painel->data_painel . ' ' . $painel->hora_inicio)); ?></small>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="seminario-form-group">
                        <label for="necessidades_especiais">Necessidades Especiais</label>
                        <textarea id="necessidades_especiais" name="necessidades_especiais" rows="3" class="seminario-input"><?php echo esc_textarea($inscricao->necessidades_especiais); ?></textarea>
                    </div>
                    
                    <div class="seminario-form-actions">
                        <button type="submit" class="seminario-btn seminario-btn-success">💾 Atualizar Inscrição</button>
                        <a href="?page=seminario-inscricoes" class="seminario-btn seminario-btn-secondary">↩️ Voltar</a>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
    
    public function add_painel_form() {
        ?>
        <div class="wrap seminario-admin">
            <div class="seminario-header">
                <h1>🎯 Novo Painel Temático</h1>
                <p>Criar novo painel para o seminário</p>
            </div>
            
            <div class="seminario-card">
                <form method="post" action="">
                    <input type="hidden" name="action" value="create">
                    
                    <div class="seminario-form-group">
                        <label for="titulo">Título do Painel *</label>
                        <input type="text" id="titulo" name="titulo" required class="seminario-input">
                    </div>
                    
                    <div class="seminario-form-group">
                        <label for="descricao">Descrição *</label>
                        <textarea id="descricao" name="descricao" rows="4" required class="seminario-input" placeholder="Descreva o objetivo e conteúdo do painel"></textarea>
                    </div>
                    
                    <div class="seminario-form-grid">
                        <div class="seminario-form-group">
                            <label for="data_painel">Data do Painel *</label>
                            <input type="date" id="data_painel" name="data_painel" required class="seminario-input">
                        </div>
                        
                        <div class="seminario-form-group">
                            <label for="hora_inicio">Hora de Início *</label>
                            <input type="time" id="hora_inicio" name="hora_inicio" required class="seminario-input">
                        </div>
                        
                        <div class="seminario-form-group">
                            <label for="hora_fim">Hora de Término *</label>
                            <input type="time" id="hora_fim" name="hora_fim" required class="seminario-input">
                        </div>
                        
                        <div class="seminario-form-group">
                            <label for="local">Local *</label>
                            <input type="text" id="local" name="local" required class="seminario-input" placeholder="Ex: Auditório Principal UFMGp">
                        </div>
                    </div>
                    
                    <div class="seminario-form-group">
                        <label for="palestrantes">Palestrantes *</label>
                        <textarea id="palestrantes" name="palestrantes" rows="3" required class="seminario-input" placeholder="Liste os palestrantes do painel"></textarea>
                    </div>
                    
                    <div class="seminario-form-group">
                        <label for="mediador">Mediador</label>
                        <input type="text" id="mediador" name="mediador" class="seminario-input" placeholder="Nome do mediador (opcional)">
                    </div>
                    
                    <div class="seminario-form-group">
                        <label for="imagem_url">Imagem do Painel</label>
                        <div class="seminario-image-upload-wrapper">
                            <input type="url" id="imagem_url" name="imagem_url" class="seminario-input seminario-image-input" placeholder="URL da imagem ou use os botões abaixo">
                            <div class="seminario-image-buttons" style="margin-top: 8px; display: flex; gap: 8px; align-items: center;">
                                <button type="button" class="seminario-btn seminario-btn-primary seminario-upload-image-btn" data-target="imagem_url">
                                    📁 Biblioteca de Mídia
                                </button>
                                <select class="seminario-input" style="flex: 1;" onchange="document.getElementById('imagem_url').value = this.value;">
                                    <option value="">Ou selecionar imagem pré-definida...</option>
                                    <option value="<?php echo plugins_url('assets/imgs/paineis/Academic_equality_panel_meeting_7d13c25e.png', dirname(__FILE__)); ?>">Estatuto da Igualdade Racial</option>
                                    <option value="<?php echo plugins_url('assets/imgs/paineis/Black_territory_quilombo_panel_a905cc96.png', dirname(__FILE__)); ?>">Território Negro e Quilombola</option>
                                    <option value="<?php echo plugins_url('assets/imgs/paineis/Education_anti-racism_panel_meeting_4c5ca787.png', dirname(__FILE__)); ?>">Educação e Combate ao Racismo</option>
                                    <option value="<?php echo plugins_url('assets/imgs/paineis/Black_population_health_panel_b8072568.png', dirname(__FILE__)); ?>">Saúde da População Negra</option>
                                    <option value="<?php echo plugins_url('assets/imgs/paineis/Work_and_income_panel_418fe218.png', dirname(__FILE__)); ?>">Trabalho e Renda</option>
                                    <option value="<?php echo plugins_url('assets/imgs/paineis/Communication_justice_panel_meeting_effe32b3.png', dirname(__FILE__)); ?>">Comunicação e Justiça</option>
                                </select>
                                <button type="button" class="seminario-btn seminario-btn-secondary seminario-remove-image-btn" data-target="imagem_url">
                                    🗑️ Remover
                                </button>
                            </div>
                            <div id="imagem_url_preview" class="seminario-image-preview"></div>
                        </div>
                    </div>
                    
                    <div class="seminario-form-actions">
                        <button type="submit" class="seminario-btn seminario-btn-success">💾 Salvar Painel</button>
                        <a href="?page=seminario-paineis" class="seminario-btn seminario-btn-secondary">↩️ Voltar</a>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
    
    public function edit_painel_form($id) {
        $painel = $this->database->get_painel($id);
        
        if (!$painel) {
            echo '<div class="notice notice-error"><p>Painel não encontrado!</p></div>';
            return;
        }
        ?>
        <div class="wrap seminario-admin">
            <div class="seminario-header">
                <h1>✏️ Editar Painel</h1>
                <p>Modificar painel: <?php echo esc_html($painel->titulo); ?></p>
            </div>
            
            <div class="seminario-card">
                <form method="post" action="">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" value="<?php echo $painel->id; ?>">
                    
                    <div class="seminario-form-group">
                        <label for="titulo">Título do Painel *</label>
                        <input type="text" id="titulo" name="titulo" value="<?php echo esc_attr($painel->titulo); ?>" required class="seminario-input">
                    </div>
                    
                    <div class="seminario-form-group">
                        <label for="descricao">Descrição *</label>
                        <textarea id="descricao" name="descricao" rows="4" required class="seminario-input"><?php echo esc_textarea($painel->descricao); ?></textarea>
                    </div>
                    
                    <div class="seminario-form-grid">
                        <div class="seminario-form-group">
                            <label for="data_painel">Data do Painel *</label>
                            <input type="date" id="data_painel" name="data_painel" value="<?php echo esc_attr($painel->data_painel); ?>" required class="seminario-input">
                        </div>
                        
                        <div class="seminario-form-group">
                            <label for="hora_inicio">Hora de Início *</label>
                            <input type="time" id="hora_inicio" name="hora_inicio" value="<?php echo esc_attr($painel->hora_inicio); ?>" required class="seminario-input">
                        </div>
                        
                        <div class="seminario-form-group">
                            <label for="hora_fim">Hora de Término *</label>
                            <input type="time" id="hora_fim" name="hora_fim" value="<?php echo esc_attr($painel->hora_fim); ?>" required class="seminario-input">
                        </div>
                        
                        <div class="seminario-form-group">
                            <label for="local">Local *</label>
                            <input type="text" id="local" name="local" value="<?php echo esc_attr($painel->local); ?>" required class="seminario-input">
                        </div>
                    </div>
                    
                    <div class="seminario-form-group">
                        <label for="palestrantes">Palestrantes *</label>
                        <textarea id="palestrantes" name="palestrantes" rows="3" required class="seminario-input"><?php echo esc_textarea($painel->palestrantes); ?></textarea>
                    </div>
                    
                    <div class="seminario-form-group">
                        <label for="mediador">Mediador</label>
                        <input type="text" id="mediador" name="mediador" value="<?php echo esc_attr($painel->mediador); ?>" class="seminario-input">
                    </div>
                    
                    <div class="seminario-form-group">
                        <label for="imagem_url">Imagem do Painel</label>
                        <div class="seminario-image-upload-wrapper">
                            <input type="url" id="imagem_url" name="imagem_url" value="<?php echo esc_attr($painel->imagem_url); ?>" class="seminario-input seminario-image-input" placeholder="URL da imagem ou use os botões abaixo">
                            <div class="seminario-image-buttons" style="margin-top: 8px; display: flex; gap: 8px; align-items: center;">
                                <button type="button" class="seminario-btn seminario-btn-primary seminario-upload-image-btn" data-target="imagem_url">
                                    📁 Biblioteca de Mídia
                                </button>
                                <select class="seminario-input" style="flex: 1;" onchange="document.getElementById('imagem_url').value = this.value;">
                                    <option value="">Ou selecionar imagem pré-definida...</option>
                                    <option value="<?php echo plugins_url('assets/imgs/paineis/Academic_equality_panel_meeting_7d13c25e.png', dirname(__FILE__)); ?>" <?php selected($painel->imagem_url, plugins_url('assets/imgs/paineis/Academic_equality_panel_meeting_7d13c25e.png', dirname(__FILE__))); ?>>Estatuto da Igualdade Racial</option>
                                    <option value="<?php echo plugins_url('assets/imgs/paineis/Black_territory_quilombo_panel_a905cc96.png', dirname(__FILE__)); ?>" <?php selected($painel->imagem_url, plugins_url('assets/imgs/paineis/Black_territory_quilombo_panel_a905cc96.png', dirname(__FILE__))); ?>>Território Negro e Quilombola</option>
                                    <option value="<?php echo plugins_url('assets/imgs/paineis/Education_anti-racism_panel_meeting_4c5ca787.png', dirname(__FILE__)); ?>" <?php selected($painel->imagem_url, plugins_url('assets/imgs/paineis/Education_anti-racism_panel_meeting_4c5ca787.png', dirname(__FILE__))); ?>>Educação e Combate ao Racismo</option>
                                    <option value="<?php echo plugins_url('assets/imgs/paineis/Black_population_health_panel_b8072568.png', dirname(__FILE__)); ?>" <?php selected($painel->imagem_url, plugins_url('assets/imgs/paineis/Black_population_health_panel_b8072568.png', dirname(__FILE__))); ?>>Saúde da População Negra</option>
                                    <option value="<?php echo plugins_url('assets/imgs/paineis/Work_and_income_panel_418fe218.png', dirname(__FILE__)); ?>" <?php selected($painel->imagem_url, plugins_url('assets/imgs/paineis/Work_and_income_panel_418fe218.png', dirname(__FILE__))); ?>>Trabalho e Renda</option>
                                    <option value="<?php echo plugins_url('assets/imgs/paineis/Communication_justice_panel_meeting_effe32b3.png', dirname(__FILE__)); ?>" <?php selected($painel->imagem_url, plugins_url('assets/imgs/paineis/Communication_justice_panel_meeting_effe32b3.png', dirname(__FILE__))); ?>>Comunicação e Justiça</option>
                                </select>
                                <button type="button" class="seminario-btn seminario-btn-secondary seminario-remove-image-btn" data-target="imagem_url">
                                    🗑️ Remover
                                </button>
                            </div>
                            <div id="imagem_url_preview" class="seminario-image-preview"></div>
                        </div>
                    </div>
                    
                    <div class="seminario-form-actions">
                        <button type="submit" class="seminario-btn seminario-btn-success">💾 Atualizar Painel</button>
                        <a href="?page=seminario-paineis" class="seminario-btn seminario-btn-secondary">↩️ Voltar</a>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
    
    public function add_conferencista_form() {
        ?>
        <div class="wrap seminario-admin">
            <div class="seminario-header">
                <h1>🎤 Novo Conferencista</h1>
                <p>Adicionar novo conferencista ao seminário</p>
            </div>
            
            <div class="seminario-card">
                <form method="post" action="">
                    <input type="hidden" name="action" value="create">
                    
                    <div class="seminario-form-grid">
                        <div class="seminario-form-group">
                            <label for="nome">Nome Completo *</label>
                            <input type="text" id="nome" name="nome" required class="seminario-input">
                        </div>
                        
                        <div class="seminario-form-group">
                            <label for="cargo">Cargo *</label>
                            <input type="text" id="cargo" name="cargo" required class="seminario-input">
                        </div>
                        
                        <div class="seminario-form-group">
                            <label for="instituicao">Instituição *</label>
                            <input type="text" id="instituicao" name="instituicao" required class="seminario-input">
                        </div>
                        
                        <div class="seminario-form-group">
                            <label for="pais">País *</label>
                            <input type="text" id="pais" name="pais" required class="seminario-input">
                        </div>
                    </div>
                    
                    <div class="seminario-form-group">
                        <label for="biografia">Biografia *</label>
                        <textarea id="biografia" name="biografia" rows="4" required class="seminario-input" placeholder="Descrição profissional e acadêmica"></textarea>
                    </div>
                    
                    <div class="seminario-form-grid">
                        <div class="seminario-form-group">
                            <label for="foto_url">Foto do Conferencista</label>
                            <div class="seminario-image-upload-wrapper">
                                <input type="url" id="foto_url" name="foto_url" class="seminario-input seminario-image-input" placeholder="URL da foto ou use os botões abaixo">
                                <div class="seminario-image-buttons" style="margin-top: 8px; display: flex; gap: 8px; align-items: center;">
                                    <button type="button" class="seminario-btn seminario-btn-primary seminario-upload-image-btn" data-target="foto_url">
                                        📁 Biblioteca de Mídia
                                    </button>
                                    <select class="seminario-input" style="flex: 1;" onchange="document.getElementById('foto_url').value = this.value;">
                                        <option value="">Ou selecionar foto pré-definida...</option>
                                        <option value="<?php echo plugins_url('assets/imgs/Ana Celia Silva.jpeg', dirname(__FILE__)); ?>">Ana Celia Silva</option>
                                        <option value="<?php echo plugins_url('assets/imgs/Antônio Liberac.jpeg', dirname(__FILE__)); ?>">Antônio Liberac</option>
                                        <option value="<?php echo plugins_url('assets/imgs/Conceição Evaristo.jpeg', dirname(__FILE__)); ?>">Conceição Evaristo</option>
                                        <option value="<?php echo plugins_url('assets/imgs/Matilde Ribeiro.jpeg', dirname(__FILE__)); ?>">Matilde Ribeiro</option>
                                        <option value="<?php echo plugins_url('assets/imgs/Nilma Lino Gomes.jpeg', dirname(__FILE__)); ?>">Nilma Lino Gomes</option>
                                    </select>
                                    <button type="button" class="seminario-btn seminario-btn-secondary seminario-remove-image-btn" data-target="foto_url">
                                        🗑️ Remover
                                    </button>
                                </div>
                                <div id="foto_url_preview" class="seminario-image-preview"></div>
                            </div>
                        </div>
                        
                        <div class="seminario-form-group">
                            <label for="paineis_participacao">Painéis de Participação</label>
                            <input type="text" id="paineis_participacao" name="paineis_participacao" class="seminario-input" placeholder="IDs dos painéis (ex: 1,3,5)">
                        </div>
                    </div>
                    
                    <div class="seminario-form-group">
                        <label for="tipo">Tipo *</label>
                        <select id="tipo" name="tipo" required class="seminario-input">
                            <option value="conferencista">Conferencista</option>
                            <option value="ministra">Ministra</option>
                            <option value="palestrante">Palestrante</option>
                        </select>
                    </div>
                    
                    <div class="seminario-form-actions">
                        <button type="submit" class="seminario-btn seminario-btn-success">💾 Salvar Conferencista</button>
                        <a href="?page=seminario-conferencistas" class="seminario-btn seminario-btn-secondary">↩️ Voltar</a>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
    
    public function edit_conferencista_form($id) {
        $conferencista = $this->database->get_conferencista($id);
        
        if (!$conferencista) {
            echo '<div class="notice notice-error"><p>Conferencista não encontrado!</p></div>';
            return;
        }
        ?>
        <div class="wrap seminario-admin">
            <div class="seminario-header">
                <h1>✏️ Editar Conferencista</h1>
                <p>Modificar dados de <?php echo esc_html($conferencista->nome); ?></p>
            </div>
            
            <div class="seminario-card">
                <form method="post" action="">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" value="<?php echo $conferencista->id; ?>">
                    
                    <div class="seminario-form-grid">
                        <div class="seminario-form-group">
                            <label for="nome">Nome Completo *</label>
                            <input type="text" id="nome" name="nome" value="<?php echo esc_attr($conferencista->nome); ?>" required class="seminario-input">
                        </div>
                        
                        <div class="seminario-form-group">
                            <label for="cargo">Cargo *</label>
                            <input type="text" id="cargo" name="cargo" value="<?php echo esc_attr($conferencista->cargo); ?>" required class="seminario-input">
                        </div>
                        
                        <div class="seminario-form-group">
                            <label for="instituicao">Instituição *</label>
                            <input type="text" id="instituicao" name="instituicao" value="<?php echo esc_attr($conferencista->instituicao); ?>" required class="seminario-input">
                        </div>
                        
                        <div class="seminario-form-group">
                            <label for="pais">País *</label>
                            <input type="text" id="pais" name="pais" value="<?php echo esc_attr($conferencista->pais); ?>" required class="seminario-input">
                        </div>
                    </div>
                    
                    <div class="seminario-form-group">
                        <label for="biografia">Biografia *</label>
                        <textarea id="biografia" name="biografia" rows="4" required class="seminario-input"><?php echo esc_textarea($conferencista->biografia); ?></textarea>
                    </div>
                    
                    <div class="seminario-form-grid">
                        <div class="seminario-form-group">
                            <label for="foto_url">Foto do Conferencista</label>
                            <div class="seminario-image-upload-wrapper">
                                <input type="url" id="foto_url" name="foto_url" value="<?php echo esc_attr($conferencista->foto_url); ?>" class="seminario-input seminario-image-input" placeholder="URL da foto ou use os botões abaixo">
                                <div class="seminario-image-buttons" style="margin-top: 8px; display: flex; gap: 8px; align-items: center;">
                                    <button type="button" class="seminario-btn seminario-btn-primary seminario-upload-image-btn" data-target="foto_url">
                                        📁 Biblioteca de Mídia
                                    </button>
                                    <select class="seminario-input" style="flex: 1;" onchange="document.getElementById('foto_url').value = this.value;">
                                        <option value="">Ou selecionar foto pré-definida...</option>
                                        <option value="<?php echo plugins_url('assets/imgs/Ana Celia Silva.jpeg', dirname(__FILE__)); ?>" <?php selected($conferencista->foto_url, plugins_url('assets/imgs/Ana Celia Silva.jpeg', dirname(__FILE__))); ?>>Ana Celia Silva</option>
                                        <option value="<?php echo plugins_url('assets/imgs/Antônio Liberac.jpeg', dirname(__FILE__)); ?>" <?php selected($conferencista->foto_url, plugins_url('assets/imgs/Antônio Liberac.jpeg', dirname(__FILE__))); ?>>Antônio Liberac</option>
                                        <option value="<?php echo plugins_url('assets/imgs/Conceição Evaristo.jpeg', dirname(__FILE__)); ?>" <?php selected($conferencista->foto_url, plugins_url('assets/imgs/Conceição Evaristo.jpeg', dirname(__FILE__))); ?>>Conceição Evaristo</option>
                                        <option value="<?php echo plugins_url('assets/imgs/Deise Benedito.jpeg', dirname(__FILE__)); ?>" <?php selected($conferencista->foto_url, plugins_url('assets/imgs/Deise Benedito.jpeg', dirname(__FILE__))); ?>>Deise Benedito</option>
                                        <option value="<?php echo plugins_url('assets/imgs/Edna Roland (Inglaterra).jpeg', dirname(__FILE__)); ?>" <?php selected($conferencista->foto_url, plugins_url('assets/imgs/Edna Roland (Inglaterra).jpeg', dirname(__FILE__))); ?>>Edna Roland (Inglaterra)</option>
                                        <option value="<?php echo plugins_url('assets/imgs/Henrique Antunes Cunha Junior.jpeg', dirname(__FILE__)); ?>" <?php selected($conferencista->foto_url, plugins_url('assets/imgs/Henrique Antunes Cunha Junior.jpeg', dirname(__FILE__))); ?>>Henrique Antunes Cunha Junior</option>
                                        <option value="<?php echo plugins_url('assets/imgs/Hélio Santos.jpeg', dirname(__FILE__)); ?>" <?php selected($conferencista->foto_url, plugins_url('assets/imgs/Hélio Santos.jpeg', dirname(__FILE__))); ?>>Hélio Santos</option>
                                        <option value="<?php echo plugins_url('assets/imgs/Iolanda de Oliveira.jpeg', dirname(__FILE__)); ?>" <?php selected($conferencista->foto_url, plugins_url('assets/imgs/Iolanda de Oliveira.jpeg', dirname(__FILE__))); ?>>Iolanda de Oliveira</option>
                                        <option value="<?php echo plugins_url('assets/imgs/Matilde Ribeiro.jpeg', dirname(__FILE__)); ?>" <?php selected($conferencista->foto_url, plugins_url('assets/imgs/Matilde Ribeiro.jpeg', dirname(__FILE__))); ?>>Matilde Ribeiro</option>
                                        <option value="<?php echo plugins_url('assets/imgs/Nilma Lino Gomes.jpeg', dirname(__FILE__)); ?>" <?php selected($conferencista->foto_url, plugins_url('assets/imgs/Nilma Lino Gomes.jpeg', dirname(__FILE__))); ?>>Nilma Lino Gomes</option>
                                        <option value="<?php echo plugins_url('assets/imgs/Petronilha Beatriz Gonçalves e Silva.jpeg', dirname(__FILE__)); ?>" <?php selected($conferencista->foto_url, plugins_url('assets/imgs/Petronilha Beatriz Gonçalves e Silva.jpeg', dirname(__FILE__))); ?>>Petronilha Beatriz Gonçalves e Silva</option>
                                        <option value="<?php echo plugins_url('assets/imgs/Rachel de Oliveira.jpeg', dirname(__FILE__)); ?>" <?php selected($conferencista->foto_url, plugins_url('assets/imgs/Rachel de Oliveira.jpeg', dirname(__FILE__))); ?>>Rachel de Oliveira</option>
                                        <option value="<?php echo plugins_url('assets/imgs/Carlos Rosero (Colombia).jpeg', dirname(__FILE__)); ?>" <?php selected($conferencista->foto_url, plugins_url('assets/imgs/Carlos Rosero (Colombia).jpeg', dirname(__FILE__))); ?>>Carlos Rosero (Colombia)</option>
                                        <option value="<?php echo plugins_url('assets/imgs/José Nafafé (Guiné Bissau).jpeg', dirname(__FILE__)); ?>" <?php selected($conferencista->foto_url, plugins_url('assets/imgs/José Nafafé (Guiné Bissau).jpeg', dirname(__FILE__))); ?>>José Nafafé (Guiné Bissau)</option>
                                        <option value="<?php echo plugins_url('assets/imgs/Frederico Pita (Argentina).jpeg', dirname(__FILE__)); ?>" <?php selected($conferencista->foto_url, plugins_url('assets/imgs/Frederico Pita (Argentina).jpeg', dirname(__FILE__))); ?>>Frederico Pita (Argentina)</option>
                                        <option value="<?php echo plugins_url('assets/imgs/Siphiwe Baleka.jpeg', dirname(__FILE__)); ?>" <?php selected($conferencista->foto_url, plugins_url('assets/imgs/Siphiwe Baleka.jpeg', dirname(__FILE__))); ?>>Siphiwe Baleka</option>
                                    </select>
                                    <button type="button" class="seminario-btn seminario-btn-secondary seminario-remove-image-btn" data-target="foto_url">
                                        🗑️ Remover
                                    </button>
                                </div>
                                <div id="foto_url_preview" class="seminario-image-preview"></div>
                            </div>
                        </div>
                        
                        <div class="seminario-form-group">
                            <label for="paineis_participacao">Painéis de Participação</label>
                            <input type="text" id="paineis_participacao" name="paineis_participacao" value="<?php echo esc_attr($conferencista->paineis_participacao); ?>" class="seminario-input">
                        </div>
                    </div>
                    
                    <div class="seminario-form-group">
                        <label for="tipo">Tipo *</label>
                        <select id="tipo" name="tipo" required class="seminario-input">
                            <option value="conferencista" <?php selected($conferencista->tipo, 'conferencista'); ?>>Conferencista</option>
                            <option value="ministra" <?php selected($conferencista->tipo, 'ministra'); ?>>Ministra</option>
                            <option value="palestrante" <?php selected($conferencista->tipo, 'palestrante'); ?>>Palestrante</option>
                        </select>
                    </div>
                    
                    <div class="seminario-form-actions">
                        <button type="submit" class="seminario-btn seminario-btn-success">💾 Atualizar Conferencista</button>
                        <a href="?page=seminario-conferencistas" class="seminario-btn seminario-btn-secondary">↩️ Voltar</a>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
    
    public function add_observador_form() {
        ?>
        <div class="wrap seminario-admin">
            <div class="seminario-header">
                <h1>👁️ Novo Observador Internacional</h1>
                <p>Adicionar novo observador ao seminário</p>
            </div>
            
            <div class="seminario-card">
                <form method="post" action="">
                    <input type="hidden" name="action" value="create">
                    
                    <div class="seminario-form-grid">
                        <div class="seminario-form-group">
                            <label for="nome">Nome Completo *</label>
                            <input type="text" id="nome" name="nome" required class="seminario-input">
                        </div>
                        
                        <div class="seminario-form-group">
                            <label for="cargo">Cargo *</label>
                            <input type="text" id="cargo" name="cargo" required class="seminario-input">
                        </div>
                        
                        <div class="seminario-form-group">
                            <label for="instituicao">Instituição *</label>
                            <input type="text" id="instituicao" name="instituicao" required class="seminario-input">
                        </div>
                        
                        <div class="seminario-form-group">
                            <label for="pais">País *</label>
                            <input type="text" id="pais" name="pais" required class="seminario-input">
                        </div>
                    </div>
                    
                    <div class="seminario-form-group">
                        <label for="biografia">Biografia *</label>
                        <textarea id="biografia" name="biografia" rows="4" required class="seminario-input" placeholder="Descrição acadêmica e profissional"></textarea>
                    </div>
                    
                    <div class="seminario-form-grid">
                        <div class="seminario-form-group">
                            <label for="foto_url">Foto</label>
                            <select id="foto_url" name="foto_url" class="seminario-input">
                                <option value="">Selecionar foto...</option>
                                <option value="<?php echo plugins_url('assets/imgs/Ana Celia Silva.jpeg', dirname(__FILE__)); ?>">Ana Celia Silva</option>
                                <option value="<?php echo plugins_url('assets/imgs/Antônio Liberac.jpeg', dirname(__FILE__)); ?>">Antônio Liberac</option>
                                <option value="<?php echo plugins_url('assets/imgs/Conceição Evaristo.jpeg', dirname(__FILE__)); ?>">Conceição Evaristo</option>
                                <option value="<?php echo plugins_url('assets/imgs/Deise Benedito.jpeg', dirname(__FILE__)); ?>">Deise Benedito</option>
                                <option value="<?php echo plugins_url('assets/imgs/Edna Roland (Inglaterra).jpeg', dirname(__FILE__)); ?>">Edna Roland (Inglaterra)</option>
                                <option value="<?php echo plugins_url('assets/imgs/Henrique Antunes Cunha Junior.jpeg', dirname(__FILE__)); ?>">Henrique Antunes Cunha Junior</option>
                                <option value="<?php echo plugins_url('assets/imgs/Hélio Santos.jpeg', dirname(__FILE__)); ?>">Hélio Santos</option>
                                <option value="<?php echo plugins_url('assets/imgs/Iolanda de Oliveira.jpeg', dirname(__FILE__)); ?>">Iolanda de Oliveira</option>
                                <option value="<?php echo plugins_url('assets/imgs/Matilde Ribeiro.jpeg', dirname(__FILE__)); ?>">Matilde Ribeiro</option>
                                <option value="<?php echo plugins_url('assets/imgs/Nilma Lino Gomes.jpeg', dirname(__FILE__)); ?>">Nilma Lino Gomes</option>
                                <option value="<?php echo plugins_url('assets/imgs/Petronilha Beatriz Gonçalves e Silva.jpeg', dirname(__FILE__)); ?>">Petronilha Beatriz Gonçalves e Silva</option>
                                <option value="<?php echo plugins_url('assets/imgs/Rachel de Oliveira.jpeg', dirname(__FILE__)); ?>">Rachel de Oliveira</option>
                                <option value="<?php echo plugins_url('assets/imgs/Carlos Rosero (Colombia).jpeg', dirname(__FILE__)); ?>">Carlos Rosero (Colombia)</option>
                                <option value="<?php echo plugins_url('assets/imgs/José Nafafé (Guiné Bissau).jpeg', dirname(__FILE__)); ?>">José Nafafé (Guiné Bissau)</option>
                                <option value="<?php echo plugins_url('assets/imgs/Frederico Pita (Argentina).jpeg', dirname(__FILE__)); ?>">Frederico Pita (Argentina)</option>
                                <option value="<?php echo plugins_url('assets/imgs/Siphiwe Baleka.jpeg', dirname(__FILE__)); ?>">Siphiwe Baleka</option>
                            </select>
                        </div>
                        
                        <div class="seminario-form-group">
                            <label for="area_especialidade">Área de Especialidade</label>
                            <input type="text" id="area_especialidade" name="area_especialidade" class="seminario-input" placeholder="Ex: Direitos Humanos, Política Internacional">
                        </div>
                    </div>
                    
                    <div class="seminario-form-group">
                        <label for="status">Status *</label>
                        <select id="status" name="status" required class="seminario-input">
                            <option value="ativo">Ativo</option>
                            <option value="confirmado">Confirmado</option>
                            <option value="pendente">Pendente</option>
                        </select>
                    </div>
                    
                    <div class="seminario-form-actions">
                        <button type="submit" class="seminario-btn seminario-btn-success">💾 Salvar Observador</button>
                        <a href="?page=seminario-observadores" class="seminario-btn seminario-btn-secondary">↩️ Voltar</a>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
    
    public function edit_observador_form($id) {
        $observador = $this->database->get_observador($id);
        
        if (!$observador) {
            echo '<div class="notice notice-error"><p>Observador não encontrado!</p></div>';
            return;
        }
        ?>
        <div class="wrap seminario-admin">
            <div class="seminario-header">
                <h1>✏️ Editar Observador</h1>
                <p>Modificar dados de <?php echo esc_html($observador->nome); ?></p>
            </div>
            
            <div class="seminario-card">
                <form method="post" action="">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" value="<?php echo $observador->id; ?>">
                    
                    <div class="seminario-form-grid">
                        <div class="seminario-form-group">
                            <label for="nome">Nome Completo *</label>
                            <input type="text" id="nome" name="nome" value="<?php echo esc_attr($observador->nome); ?>" required class="seminario-input">
                        </div>
                        
                        <div class="seminario-form-group">
                            <label for="cargo">Cargo *</label>
                            <input type="text" id="cargo" name="cargo" value="<?php echo esc_attr($observador->cargo); ?>" required class="seminario-input">
                        </div>
                        
                        <div class="seminario-form-group">
                            <label for="instituicao">Instituição *</label>
                            <input type="text" id="instituicao" name="instituicao" value="<?php echo esc_attr($observador->instituicao); ?>" required class="seminario-input">
                        </div>
                        
                        <div class="seminario-form-group">
                            <label for="pais">País *</label>
                            <input type="text" id="pais" name="pais" value="<?php echo esc_attr($observador->pais); ?>" required class="seminario-input">
                        </div>
                    </div>
                    
                    <div class="seminario-form-group">
                        <label for="biografia">Biografia *</label>
                        <textarea id="biografia" name="biografia" rows="4" required class="seminario-input"><?php echo esc_textarea($observador->biografia); ?></textarea>
                    </div>
                    
                    <div class="seminario-form-grid">
                        <div class="seminario-form-group">
                            <label for="foto_url">Foto do Observador</label>
                            <div class="seminario-image-upload-wrapper">
                                <input type="url" id="foto_url" name="foto_url" value="<?php echo esc_attr($observador->foto_url); ?>" class="seminario-input seminario-image-input" placeholder="URL da foto ou use os botões abaixo">
                                <div class="seminario-image-buttons" style="margin-top: 8px; display: flex; gap: 8px; align-items: center;">
                                    <button type="button" class="seminario-btn seminario-btn-primary seminario-upload-image-btn" data-target="foto_url">
                                        📁 Biblioteca de Mídia
                                    </button>
                                    <select class="seminario-input" style="flex: 1;" onchange="document.getElementById('foto_url').value = this.value;">
                                        <option value="">Ou selecionar foto pré-definida...</option>
                                        <option value="<?php echo plugins_url('assets/imgs/Ana Celia Silva.jpeg', dirname(__FILE__)); ?>" <?php selected($observador->foto_url, plugins_url('assets/imgs/Ana Celia Silva.jpeg', dirname(__FILE__))); ?>>Ana Celia Silva</option>
                                        <option value="<?php echo plugins_url('assets/imgs/Antônio Liberac.jpeg', dirname(__FILE__)); ?>" <?php selected($observador->foto_url, plugins_url('assets/imgs/Antônio Liberac.jpeg', dirname(__FILE__))); ?>>Antônio Liberac</option>
                                        <option value="<?php echo plugins_url('assets/imgs/Conceição Evaristo.jpeg', dirname(__FILE__)); ?>" <?php selected($observador->foto_url, plugins_url('assets/imgs/Conceição Evaristo.jpeg', dirname(__FILE__))); ?>>Conceição Evaristo</option>
                                        <option value="<?php echo plugins_url('assets/imgs/Deise Benedito.jpeg', dirname(__FILE__)); ?>" <?php selected($observador->foto_url, plugins_url('assets/imgs/Deise Benedito.jpeg', dirname(__FILE__))); ?>>Deise Benedito</option>
                                        <option value="<?php echo plugins_url('assets/imgs/Edna Roland (Inglaterra).jpeg', dirname(__FILE__)); ?>" <?php selected($observador->foto_url, plugins_url('assets/imgs/Edna Roland (Inglaterra).jpeg', dirname(__FILE__))); ?>>Edna Roland (Inglaterra)</option>
                                        <option value="<?php echo plugins_url('assets/imgs/Henrique Antunes Cunha Junior.jpeg', dirname(__FILE__)); ?>" <?php selected($observador->foto_url, plugins_url('assets/imgs/Henrique Antunes Cunha Junior.jpeg', dirname(__FILE__))); ?>>Henrique Antunes Cunha Junior</option>
                                        <option value="<?php echo plugins_url('assets/imgs/Hélio Santos.jpeg', dirname(__FILE__)); ?>" <?php selected($observador->foto_url, plugins_url('assets/imgs/Hélio Santos.jpeg', dirname(__FILE__))); ?>>Hélio Santos</option>
                                        <option value="<?php echo plugins_url('assets/imgs/Iolanda de Oliveira.jpeg', dirname(__FILE__)); ?>" <?php selected($observador->foto_url, plugins_url('assets/imgs/Iolanda de Oliveira.jpeg', dirname(__FILE__))); ?>>Iolanda de Oliveira</option>
                                        <option value="<?php echo plugins_url('assets/imgs/Matilde Ribeiro.jpeg', dirname(__FILE__)); ?>" <?php selected($observador->foto_url, plugins_url('assets/imgs/Matilde Ribeiro.jpeg', dirname(__FILE__))); ?>>Matilde Ribeiro</option>
                                        <option value="<?php echo plugins_url('assets/imgs/Nilma Lino Gomes.jpeg', dirname(__FILE__)); ?>" <?php selected($observador->foto_url, plugins_url('assets/imgs/Nilma Lino Gomes.jpeg', dirname(__FILE__))); ?>>Nilma Lino Gomes</option>
                                        <option value="<?php echo plugins_url('assets/imgs/Petronilha Beatriz Gonçalves e Silva.jpeg', dirname(__FILE__)); ?>" <?php selected($observador->foto_url, plugins_url('assets/imgs/Petronilha Beatriz Gonçalves e Silva.jpeg', dirname(__FILE__))); ?>>Petronilha Beatriz Gonçalves e Silva</option>
                                        <option value="<?php echo plugins_url('assets/imgs/Rachel de Oliveira.jpeg', dirname(__FILE__)); ?>" <?php selected($observador->foto_url, plugins_url('assets/imgs/Rachel de Oliveira.jpeg', dirname(__FILE__))); ?>>Rachel de Oliveira</option>
                                        <option value="<?php echo plugins_url('assets/imgs/Carlos Rosero (Colombia).jpeg', dirname(__FILE__)); ?>" <?php selected($observador->foto_url, plugins_url('assets/imgs/Carlos Rosero (Colombia).jpeg', dirname(__FILE__))); ?>>Carlos Rosero (Colombia)</option>
                                        <option value="<?php echo plugins_url('assets/imgs/José Nafafé (Guiné Bissau).jpeg', dirname(__FILE__)); ?>" <?php selected($observador->foto_url, plugins_url('assets/imgs/José Nafafé (Guiné Bissau).jpeg', dirname(__FILE__))); ?>>José Nafafé (Guiné Bissau)</option>
                                        <option value="<?php echo plugins_url('assets/imgs/Frederico Pita (Argentina).jpeg', dirname(__FILE__)); ?>" <?php selected($observador->foto_url, plugins_url('assets/imgs/Frederico Pita (Argentina).jpeg', dirname(__FILE__))); ?>>Frederico Pita (Argentina)</option>
                                        <option value="<?php echo plugins_url('assets/imgs/Siphiwe Baleka.jpeg', dirname(__FILE__)); ?>" <?php selected($observador->foto_url, plugins_url('assets/imgs/Siphiwe Baleka.jpeg', dirname(__FILE__))); ?>>Siphiwe Baleka</option>
                                    </select>
                                    <button type="button" class="seminario-btn seminario-btn-secondary seminario-remove-image-btn" data-target="foto_url">
                                        🗑️ Remover
                                    </button>
                                </div>
                                <div id="foto_url_preview" class="seminario-image-preview"></div>
                            </div>
                        </div>
                        
                        <div class="seminario-form-group">
                            <label for="area_especialidade">Área de Especialidade</label>
                            <input type="text" id="area_especialidade" name="area_especialidade" value="<?php echo esc_attr($observador->area_especialidade); ?>" class="seminario-input">
                        </div>
                    </div>
                    
                    <div class="seminario-form-group">
                        <label for="status">Status *</label>
                        <select id="status" name="status" required class="seminario-input">
                            <option value="ativo" <?php selected($observador->status, 'ativo'); ?>>Ativo</option>
                            <option value="confirmado" <?php selected($observador->status, 'confirmado'); ?>>Confirmado</option>
                            <option value="pendente" <?php selected($observador->status, 'pendente'); ?>>Pendente</option>
                        </select>
                    </div>
                    
                    <div class="seminario-form-actions">
                        <button type="submit" class="seminario-btn seminario-btn-success">💾 Atualizar Observador</button>
                        <a href="?page=seminario-observadores" class="seminario-btn seminario-btn-secondary">↩️ Voltar</a>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
    
    public function locais_page() {
        // Processar ações CRUD
        if (isset($_POST['action'])) {
            if ($_POST['action'] == 'create') {
                $data = array(
                    'nome' => sanitize_text_field($_POST['nome']),
                    'endereco' => sanitize_textarea_field($_POST['endereco']),
                    'capacidade' => intval($_POST['capacidade']),
                    'descricao' => sanitize_textarea_field($_POST['descricao']),
                    'latitude' => sanitize_text_field($_POST['latitude']),
                    'longitude' => sanitize_text_field($_POST['longitude']),
                    'recursos' => sanitize_textarea_field($_POST['recursos']),
                    'contato' => sanitize_text_field($_POST['contato']),
                    'status' => sanitize_text_field($_POST['status'])
                );
                $this->database->create_local($data);
                echo '<div class="notice notice-success"><p>Local criado com sucesso!</p></div>';
            } elseif ($_POST['action'] == 'update') {
                $data = array(
                    'nome' => sanitize_text_field($_POST['nome']),
                    'endereco' => sanitize_textarea_field($_POST['endereco']),
                    'capacidade' => intval($_POST['capacidade']),
                    'descricao' => sanitize_textarea_field($_POST['descricao']),
                    'latitude' => sanitize_text_field($_POST['latitude']),
                    'longitude' => sanitize_text_field($_POST['longitude']),
                    'recursos' => sanitize_textarea_field($_POST['recursos']),
                    'contato' => sanitize_text_field($_POST['contato']),
                    'status' => sanitize_text_field($_POST['status'])
                );
                $this->database->update_local(intval($_POST['id']), $data);
                echo '<div class="notice notice-success"><p>Local atualizado com sucesso!</p></div>';
            }
        }
        
        if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
            $this->database->delete_local(intval($_GET['id']));
            echo '<div class="notice notice-success"><p>Local excluído com sucesso!</p></div>';
        }
        
        // Mostrar formulário de edição
        if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
            $this->edit_local_form(intval($_GET['id']));
            return;
        }
        
        // Mostrar formulário de novo local
        if (isset($_GET['action']) && $_GET['action'] == 'new') {
            $this->add_local_form();
            return;
        }
        
        $locais = $this->database->get_locais();
        ?>
        <div class="wrap seminario-admin">
            <div class="seminario-header">
                <h1>📍 Gestão de Locais</h1>
                <p>Gerencie os locais onde ocorrerão os painéis do seminário</p>
            </div>
            
            <div class="seminario-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <h2>Locais Cadastrados</h2>
                    <a href="?page=seminario-locais&action=new" class="seminario-btn seminario-btn-success">➕ Novo Local</a>
                </div>
            </div>
            
            <?php if (empty($locais)): ?>
                <div class="seminario-card">
                    <div class="seminario-empty-state">
                        <p>📍 Nenhum local cadastrado ainda.</p>
                        <p>Comece adicionando os locais onde ocorrerão os painéis do seminário.</p>
                        <a href="?page=seminario-locais&action=new" class="seminario-btn seminario-btn-primary">Adicionar Primeiro Local</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="seminario-grid seminario-grid-2">
                    <?php foreach ($locais as $local): ?>
                        <div class="seminario-card">
                            <h3><?php echo esc_html($local->nome); ?></h3>
                            
                            <div style="margin: 12px 0;">
                                <p><strong>📍 Endereço:</strong> <?php echo esc_html($local->endereco); ?></p>
                                <p><strong>👥 Capacidade:</strong> <?php echo esc_html($local->capacidade); ?> pessoas</p>
                                
                                <?php if ($local->recursos): ?>
                                    <p><strong>🛠️ Recursos:</strong> <?php echo esc_html($local->recursos); ?></p>
                                <?php endif; ?>
                                
                                <?php if ($local->contato): ?>
                                    <p><strong>📞 Contato:</strong> <?php echo esc_html($local->contato); ?></p>
                                <?php endif; ?>
                                
                                <p><strong>Status:</strong> 
                                    <span class="<?php echo $local->status === 'ativo' ? 'status-ativo' : 'status-inativo'; ?>">
                                        <?php echo esc_html(ucfirst($local->status)); ?>
                                    </span>
                                </p>
                            </div>
                            
                            <?php if ($local->descricao): ?>
                                <p style="color: var(--seminario-gray); font-size: 14px; margin-top: 8px;">
                                    <?php echo esc_html(substr($local->descricao, 0, 150)) . (strlen($local->descricao) > 150 ? '...' : ''); ?>
                                </p>
                            <?php endif; ?>
                            
                            <div class="seminario-card-actions" style="margin-top: 16px; display: flex; gap: 8px;">
                                <a href="?page=seminario-locais&action=edit&id=<?php echo $local->id; ?>" class="seminario-btn seminario-btn-primary">✏️ Editar</a>
                                <a href="?page=seminario-locais&action=delete&id=<?php echo $local->id; ?>" 
                                   class="seminario-btn seminario-btn-danger" 
                                   onclick="return confirm('Tem certeza que deseja excluir este local?')">🗑️ Excluir</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
    
    public function add_local_form() {
        ?>
        <div class="wrap seminario-admin">
            <div class="seminario-header">
                <h1>📍 Novo Local</h1>
                <p>Adicionar novo local para os painéis do seminário</p>
            </div>
            
            <div class="seminario-card">
                <form method="post" action="">
                    <input type="hidden" name="action" value="create">
                    
                    <div class="seminario-form-grid">
                        <div class="seminario-form-group">
                            <label for="nome">Nome do Local *</label>
                            <input type="text" id="nome" name="nome" required class="seminario-input" placeholder="Ex: Auditório Principal UFMG">
                        </div>
                        
                        <div class="seminario-form-group">
                            <label for="capacidade">Capacidade *</label>
                            <input type="number" id="capacidade" name="capacidade" required class="seminario-input" placeholder="Ex: 200">
                        </div>
                    </div>
                    
                    <div class="seminario-form-group">
                        <label for="endereco">Endereço Completo *</label>
                        <textarea id="endereco" name="endereco" rows="3" required class="seminario-input" placeholder="Endereço completo com CEP"></textarea>
                    </div>
                    
                    <div class="seminario-form-group">
                        <label for="descricao">Descrição</label>
                        <textarea id="descricao" name="descricao" rows="3" class="seminario-input" placeholder="Descrição do local, características especiais"></textarea>
                    </div>
                    
                    <div class="seminario-form-grid">
                        <div class="seminario-form-group">
                            <label for="latitude">Latitude</label>
                            <input type="text" id="latitude" name="latitude" class="seminario-input" placeholder="Ex: -19.9281">
                        </div>
                        
                        <div class="seminario-form-group">
                            <label for="longitude">Longitude</label>
                            <input type="text" id="longitude" name="longitude" class="seminario-input" placeholder="Ex: -43.9419">
                        </div>
                    </div>
                    
                    <div class="seminario-form-group">
                        <label for="recursos">Recursos Disponíveis</label>
                        <textarea id="recursos" name="recursos" rows="3" class="seminario-input" placeholder="Ex: Projetor, Sistema de som, Wi-Fi, Ar condicionado"></textarea>
                    </div>
                    
                    <div class="seminario-form-grid">
                        <div class="seminario-form-group">
                            <label for="contato">Contato</label>
                            <input type="text" id="contato" name="contato" class="seminario-input" placeholder="Telefone ou e-mail do responsável">
                        </div>
                        
                        <div class="seminario-form-group">
                            <label for="status">Status *</label>
                            <select id="status" name="status" required class="seminario-input">
                                <option value="ativo">Ativo</option>
                                <option value="manutencao">Em Manutenção</option>
                                <option value="inativo">Inativo</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="seminario-form-actions">
                        <button type="submit" class="seminario-btn seminario-btn-success">💾 Salvar Local</button>
                        <a href="?page=seminario-locais" class="seminario-btn seminario-btn-secondary">↩️ Voltar</a>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
    
    public function edit_local_form($id) {
        $local = $this->database->get_local($id);
        
        if (!$local) {
            echo '<div class="notice notice-error"><p>Local não encontrado!</p></div>';
            return;
        }
        ?>
        <div class="wrap seminario-admin">
            <div class="seminario-header">
                <h1>✏️ Editar Local</h1>
                <p>Modificar dados de <?php echo esc_html($local->nome); ?></p>
            </div>
            
            <div class="seminario-card">
                <form method="post" action="">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" value="<?php echo $local->id; ?>">
                    
                    <div class="seminario-form-grid">
                        <div class="seminario-form-group">
                            <label for="nome">Nome do Local *</label>
                            <input type="text" id="nome" name="nome" value="<?php echo esc_attr($local->nome); ?>" required class="seminario-input">
                        </div>
                        
                        <div class="seminario-form-group">
                            <label for="capacidade">Capacidade *</label>
                            <input type="number" id="capacidade" name="capacidade" value="<?php echo esc_attr($local->capacidade); ?>" required class="seminario-input">
                        </div>
                    </div>
                    
                    <div class="seminario-form-group">
                        <label for="endereco">Endereço Completo *</label>
                        <textarea id="endereco" name="endereco" rows="3" required class="seminario-input"><?php echo esc_textarea($local->endereco); ?></textarea>
                    </div>
                    
                    <div class="seminario-form-group">
                        <label for="descricao">Descrição</label>
                        <textarea id="descricao" name="descricao" rows="3" class="seminario-input"><?php echo esc_textarea($local->descricao); ?></textarea>
                    </div>
                    
                    <div class="seminario-form-grid">
                        <div class="seminario-form-group">
                            <label for="latitude">Latitude</label>
                            <input type="text" id="latitude" name="latitude" value="<?php echo esc_attr($local->latitude); ?>" class="seminario-input">
                        </div>
                        
                        <div class="seminario-form-group">
                            <label for="longitude">Longitude</label>
                            <input type="text" id="longitude" name="longitude" value="<?php echo esc_attr($local->longitude); ?>" class="seminario-input">
                        </div>
                    </div>
                    
                    <div class="seminario-form-group">
                        <label for="recursos">Recursos Disponíveis</label>
                        <textarea id="recursos" name="recursos" rows="3" class="seminario-input"><?php echo esc_textarea($local->recursos); ?></textarea>
                    </div>
                    
                    <div class="seminario-form-grid">
                        <div class="seminario-form-group">
                            <label for="contato">Contato</label>
                            <input type="text" id="contato" name="contato" value="<?php echo esc_attr($local->contato); ?>" class="seminario-input">
                        </div>
                        
                        <div class="seminario-form-group">
                            <label for="status">Status *</label>
                            <select id="status" name="status" required class="seminario-input">
                                <option value="ativo" <?php selected($local->status, 'ativo'); ?>>Ativo</option>
                                <option value="manutencao" <?php selected($local->status, 'manutencao'); ?>>Em Manutenção</option>
                                <option value="inativo" <?php selected($local->status, 'inativo'); ?>>Inativo</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="seminario-form-actions">
                        <button type="submit" class="seminario-btn seminario-btn-success">💾 Atualizar Local</button>
                        <a href="?page=seminario-locais" class="seminario-btn seminario-btn-secondary">↩️ Voltar</a>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
}
