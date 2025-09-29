<?php
/**
 * Plugin Name: II Seminário Internacional Pró-Reparações - Sistema Completo
 * Plugin URI: https://reparacoeshistoricas.org
 * Description: Sistema completo de gestão do II Seminário Internacional Pró-Reparações com CRUD completo, dados reais e gestão de todos os aspectos do evento.
 * Version: 3.0.0
 * Author: Equipe Seminário Reparações
 * License: GPL v2 or later
 * Text Domain: seminario-sistema-completo
 */

// Prevenir acesso direto
if (!defined('ABSPATH')) {
    exit;
}

// Definir constantes
define('SEMINARIO_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SEMINARIO_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('SEMINARIO_VERSION', '3.0.0');

/**
 * Classe principal do plugin
 */
class SeminarioSistemaCompleto {
    
    public function __construct() {
        // Carregar classes sempre que o plugin for inicializado
        $this->load_classes();
        
        add_action('init', array($this, 'init'));
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    public function init() {
        // Inicializar componentes
        new SeminarioDatabase();
        new SeminarioAdmin();
        new SeminarioAjax();
        new SeminarioShortcodes();
        
        // Carregar assets
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }
    
    private function load_classes() {
        require_once SEMINARIO_PLUGIN_PATH . 'includes/class-database.php';
        require_once SEMINARIO_PLUGIN_PATH . 'includes/class-admin.php';
        require_once SEMINARIO_PLUGIN_PATH . 'includes/class-ajax.php';
        require_once SEMINARIO_PLUGIN_PATH . 'includes/class-shortcodes.php';
    }
    
    public function enqueue_frontend_scripts() {
        wp_enqueue_style('seminario-frontend', SEMINARIO_PLUGIN_URL . 'assets/frontend.css', array(), SEMINARIO_VERSION);
        wp_enqueue_script('seminario-frontend', SEMINARIO_PLUGIN_URL . 'assets/frontend.js', array('jquery'), SEMINARIO_VERSION, true);
        
        wp_localize_script('seminario-frontend', 'seminarioAjax', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('seminario_nonce'),
            'messages' => array(
                'success' => 'Inscrição realizada com sucesso!',
                'error' => 'Erro ao processar inscrição. Tente novamente.',
                'processing' => 'Processando...'
            )
        ));
    }
    
    public function enqueue_admin_scripts($hook) {
        if (strpos($hook, 'seminario') !== false) {
            // Modern Afro-Brazilian UI Framework
            wp_enqueue_style('seminario-modern', SEMINARIO_PLUGIN_URL . 'assets/seminario-modern.css', array(), SEMINARIO_VERSION);
            
            // Media upload script
            wp_enqueue_script('seminario-media-upload', SEMINARIO_PLUGIN_URL . 'assets/media-upload.js', array('jquery'), SEMINARIO_VERSION, true);
            wp_enqueue_media();
            
            wp_localize_script('seminario-media-upload', 'seminarioAdmin', array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('seminario_admin_nonce'),
                'messages' => array(
                    'confirmDelete' => 'Tem certeza que deseja excluir este item?',
                    'success' => 'Operação realizada com sucesso!',
                    'error' => 'Erro ao processar operação.'
                )
            ));
            
            // Inline CSS para correções críticas
            wp_add_inline_style('seminario-modern', '
                .wp-admin .notice {
                    margin: 5px 0 15px;
                }
                .seminario-admin {
                    background: #f1f1f1;
                }
                .seminario-stat {
                    min-height: 120px;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    text-align: center;
                }
            ');
        }
    }
    
    public function activate() {
        $database = new SeminarioDatabase();
        $database->create_tables();
        $database->insert_initial_data();
        flush_rewrite_rules();
    }
    
    public function deactivate() {
        flush_rewrite_rules();
    }
}

// Inicializar plugin
new SeminarioSistemaCompleto();
