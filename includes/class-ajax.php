<?php
class SeminarioAjax {
    
    private $database;
    
    public function __construct() {
        $this->database = new SeminarioDatabase();
        
        // AJAX para usuários logados e não logados
        add_action('wp_ajax_seminario_submit_inscricao', array($this, 'submit_inscricao'));
        add_action('wp_ajax_nopriv_seminario_submit_inscricao', array($this, 'submit_inscricao'));
        
        add_action('wp_ajax_seminario_get_inscricao_details', array($this, 'get_inscricao_details'));
    }
    
    public function submit_inscricao() {
        // Verificar nonce
        if (!wp_verify_nonce($_POST['nonce'], 'seminario_nonce')) {
            wp_die('Erro de segurança');
        }
        
        // Validar dados
        $nome = sanitize_text_field($_POST['nome']);
        $email = sanitize_email($_POST['email']);
        $telefone = sanitize_text_field($_POST['telefone']);
        $pais = sanitize_text_field($_POST['pais']);
        $cidade = sanitize_text_field($_POST['cidade']);
        $instituicao = sanitize_text_field($_POST['instituicao']);
        $cargo = sanitize_text_field($_POST['cargo']);
        $area_interesse = sanitize_textarea_field($_POST['area_interesse']);
        $necessidades_especiais = sanitize_textarea_field($_POST['necessidades_especiais']);
        $paineis_escolhidos = isset($_POST['paineis_escolhidos']) ? implode(',', array_map('intval', $_POST['paineis_escolhidos'])) : '';
        
        // Validações
        if (empty($nome) || empty($email)) {
            wp_send_json_error('Nome e email são obrigatórios');
        }
        
        if (!is_email($email)) {
            wp_send_json_error('Email inválido');
        }
        
        // Verificar limite de painéis
        $limite_paineis = $this->database->get_configuracao('limite_paineis_por_pessoa');
        if (!empty($paineis_escolhidos)) {
            $paineis_array = explode(',', $paineis_escolhidos);
            if (count($paineis_array) > $limite_paineis) {
                wp_send_json_error("Você pode escolher no máximo {$limite_paineis} painéis");
            }
        }
        
        // Verificar se inscrições estão abertas
        $inscricoes_abertas = $this->database->get_configuracao('inscricoes_abertas');
        if (!$inscricoes_abertas) {
            wp_send_json_error('As inscrições estão fechadas no momento');
        }
        
        // Criar inscrição
        $data = array(
            'nome' => $nome,
            'email' => $email,
            'telefone' => $telefone,
            'pais' => $pais,
            'cidade' => $cidade,
            'instituicao' => $instituicao,
            'cargo' => $cargo,
            'area_interesse' => $area_interesse,
            'necessidades_especiais' => $necessidades_especiais,
            'paineis_escolhidos' => $paineis_escolhidos,
            'data_inscricao' => current_time('mysql')
        );
        
        $result = $this->database->create_inscricao($data);
        
        if ($result) {
            // Enviar email de confirmação se habilitado
            $email_confirmacao = $this->database->get_configuracao('email_confirmacao');
            if ($email_confirmacao) {
                $this->send_confirmation_email($email, $nome);
            }
            
            wp_send_json_success('Inscrição realizada com sucesso!');
        } else {
            wp_send_json_error('Erro ao processar inscrição. Tente novamente.');
        }
    }
    
    public function get_inscricao_details() {
        if (!wp_verify_nonce($_POST['nonce'], 'seminario_admin_nonce')) {
            wp_die('Erro de segurança');
        }
        
        $id = intval($_POST['id']);
        $inscricao = $this->database->get_inscricao($id);
        
        if (!$inscricao) {
            wp_send_json_error('Inscrição não encontrada');
        }
        
        // Buscar nomes dos painéis
        $paineis_nomes = array();
        if ($inscricao->paineis_escolhidos) {
            $paineis_ids = explode(',', $inscricao->paineis_escolhidos);
            $paineis = $this->database->get_paineis();
            foreach ($paineis_ids as $painel_id) {
                foreach ($paineis as $painel) {
                    if ($painel->id == $painel_id) {
                        $paineis_nomes[] = $painel->titulo;
                        break;
                    }
                }
            }
        }
        
        $html = '<h2>Detalhes da Inscrição</h2>';
        $html .= '<div class="seminario-inscricao-details">';
        $html .= '<div class="seminario-detail-row"><strong>Nome:</strong> ' . esc_html($inscricao->nome) . '</div>';
        $html .= '<div class="seminario-detail-row"><strong>Email:</strong> ' . esc_html($inscricao->email) . '</div>';
        $html .= '<div class="seminario-detail-row"><strong>Telefone:</strong> ' . esc_html($inscricao->telefone) . '</div>';
        $html .= '<div class="seminario-detail-row"><strong>País:</strong> ' . esc_html($inscricao->pais) . '</div>';
        $html .= '<div class="seminario-detail-row"><strong>Cidade:</strong> ' . esc_html($inscricao->cidade) . '</div>';
        $html .= '<div class="seminario-detail-row"><strong>Instituição:</strong> ' . esc_html($inscricao->instituicao) . '</div>';
        $html .= '<div class="seminario-detail-row"><strong>Cargo:</strong> ' . esc_html($inscricao->cargo) . '</div>';
        $html .= '<div class="seminario-detail-row"><strong>Área de Interesse:</strong> ' . esc_html($inscricao->area_interesse) . '</div>';
        
        if (!empty($paineis_nomes)) {
            $html .= '<div class="seminario-detail-row"><strong>Painéis Escolhidos:</strong><br>';
            foreach ($paineis_nomes as $painel_nome) {
                $html .= '• ' . esc_html($painel_nome) . '<br>';
            }
            $html .= '</div>';
        }
        
        if ($inscricao->necessidades_especiais) {
            $html .= '<div class="seminario-detail-row"><strong>Necessidades Especiais:</strong> ' . esc_html($inscricao->necessidades_especiais) . '</div>';
        }
        
        $html .= '<div class="seminario-detail-row"><strong>Data da Inscrição:</strong> ' . date('d/m/Y H:i:s', strtotime($inscricao->data_inscricao)) . '</div>';
        $html .= '</div>';
        
        wp_send_json_success($html);
    }
    
    private function send_confirmation_email($email, $nome) {
        $subject = 'Confirmação de Inscrição - II Seminário Internacional Pró-Reparações';
        
        $message = "Olá {$nome},\n\n";
        $message .= "Sua inscrição no II Seminário Internacional Pró-Reparações foi realizada com sucesso!\n\n";
        $message .= "Dados do evento:\n";
        $message .= "• Data: 10 a 14 de Novembro de 2025\n";
        $message .= "• Local: Belo Horizonte - MG\n";
        $message .= "• Organização: Coletivo Minas Pró-Reparações\n\n";
        $message .= "Em breve você receberá mais informações sobre a programação e logística do evento.\n\n";
        $message .= "Atenciosamente,\n";
        $message .= "Equipe Organizadora";
        
        wp_mail($email, $subject, $message);
    }
}
