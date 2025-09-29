<?php
class SeminarioDatabase {
    
    public function create_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        // Tabela de inscrições
        $sql_inscricoes = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}seminario_inscricoes (
            id int(11) NOT NULL AUTO_INCREMENT,
            nome varchar(255) NOT NULL,
            email varchar(255) NOT NULL,
            telefone varchar(50),
            pais varchar(100),
            cidade varchar(100),
            instituicao varchar(255),
            cargo varchar(255),
            area_interesse text,
            necessidades_especiais text,
            paineis_escolhidos text,
            data_inscricao datetime DEFAULT CURRENT_TIMESTAMP,
            status varchar(20) DEFAULT 'ativo',
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        // Tabela de painéis
        $sql_paineis = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}seminario_paineis (
            id int(11) NOT NULL AUTO_INCREMENT,
            titulo varchar(255) NOT NULL,
            descricao text,
            data_painel date,
            hora_inicio time,
            hora_fim time,
            local varchar(255),
            palestrantes text,
            mediador varchar(255),
            imagem_url varchar(500),
            vagas_disponiveis int(11) DEFAULT 100,
            status varchar(20) DEFAULT 'ativo',
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        // Tabela de conferencistas
        $sql_conferencistas = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}seminario_conferencistas (
            id int(11) NOT NULL AUTO_INCREMENT,
            nome varchar(255) NOT NULL,
            cargo varchar(255),
            instituicao varchar(255),
            pais varchar(100),
            biografia text,
            foto_url varchar(500),
            paineis_participacao text,
            tipo varchar(50) DEFAULT 'conferencista',
            status varchar(20) DEFAULT 'ativo',
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        // Tabela de observadores
        $sql_observadores = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}seminario_observadores (
            id int(11) NOT NULL AUTO_INCREMENT,
            nome varchar(255) NOT NULL,
            cargo varchar(255),
            instituicao varchar(255),
            pais varchar(100),
            biografia text,
            foto_url varchar(500),
            area_especialidade varchar(255),
            status varchar(20) DEFAULT 'ativo',
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        // Tabela de configurações
        $sql_configuracoes = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}seminario_configuracoes (
            id int(11) NOT NULL AUTO_INCREMENT,
            chave varchar(100) NOT NULL UNIQUE,
            valor text,
            descricao text,
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        // Tabela de locais
        $sql_locais = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}seminario_locais (
            id int(11) NOT NULL AUTO_INCREMENT,
            nome varchar(255) NOT NULL,
            endereco text,
            capacidade int(11),
            descricao text,
            latitude decimal(10,8),
            longitude decimal(11,8),
            recursos text,
            contato varchar(255),
            status varchar(50) DEFAULT 'ativo',
            data_criacao timestamp DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_inscricoes);
        dbDelta($sql_paineis);
        dbDelta($sql_conferencistas);
        dbDelta($sql_observadores);
        dbDelta($sql_configuracoes);
        dbDelta($sql_locais);
    }
    
    public function insert_initial_data() {
        $this->insert_paineis();
        $this->insert_conferencistas();
        $this->insert_observadores();
        $this->insert_configuracoes();
    }
    
    private function insert_paineis() {
        global $wpdb;
        
        $paineis = array(
            array(
                'titulo' => 'Estatuto da Igualdade Racial no Estado de Minas Gerais',
                'descricao' => 'Discussões sobre a Lei nº 25.150, de 14/01/2025, que institui o Estatuto da Igualdade Racial no Estado de Minas Gerais com participação das Ministras Macaé Evaristo e Anielle Franco.',
                'data_painel' => '2025-11-10',
                'hora_inicio' => '13:20:00',
                'hora_fim' => '14:50:00',
                'local' => 'Assembleia Legislativa de Minas Gerais',
                'palestrantes' => 'Ministra Macaé Evaristo, Ministra Anielle Franco, Deputada Federal Dandara Tonantzin, Deputada Leninha Alves, Deputada Andreia de Jesus, Deputada Ana Paula Siqueira',
                'mediador' => 'Autoridades da ALMG'
            ),
            array(
                'titulo' => 'Reparações e justiça fiscal - o pagamento da dívida racial e o conserto dos elevadores quebrados',
                'descricao' => 'Painel sobre reparações econômicas e justiça fiscal para a população negra, discutindo metodologias para cálculo da dívida racial e políticas de redistribuição.',
                'data_painel' => '2025-11-10',
                'hora_inicio' => '15:00:00',
                'hora_fim' => '17:30:00',
                'local' => 'Assembleia Legislativa de Minas Gerais',
                'palestrantes' => 'Eliane Barbosa da Conceição, Mireille Fanon Mendès-France, Ndongo Samba Sylla',
                'mediador' => 'Douglas Belchior - Instituto Peregum'
            ),
            array(
                'titulo' => 'Reparações - Enfrentando o contexto internacional hostil do neocolonialismo',
                'descricao' => 'Análise do contexto internacional: guerras, ajustes estruturais, hiperimperialismo versus sinais promissores da multipolaridade e do Sul Global.',
                'data_painel' => '2025-11-11',
                'hora_inicio' => '09:30:00',
                'hora_fim' => '12:00:00',
                'local' => 'Assembleia Legislativa de Minas Gerais',
                'palestrantes' => 'Ndongo Samba Sylla, Koulsy Lamko, Marcelo d\'Agostini',
                'mediador' => 'Maria da Consolação Rocha'
            ),
            array(
                'titulo' => 'A luta pró-reparações nos países credores: Portugal, Espanha e Inglaterra',
                'descricao' => 'Discussão sobre as estratégias de luta por reparações nos países europeus colonizadores, incluindo experiências e propostas de políticas.',
                'data_painel' => '2025-11-11',
                'hora_inicio' => '13:40:00',
                'hora_fim' => '15:00:00',
                'local' => 'Assembleia Legislativa de Minas Gerais',
                'palestrantes' => 'Luzia Moniz (Portugal), Conceição Queiroz (Portugal)',
                'mediador' => 'A definir'
            ),
            array(
                'titulo' => 'Panafricanismo e a luta pró-reparações no Caribe e na Colômbia: lições a tirar',
                'descricao' => 'Experiências de reparações no Caribe e Colômbia, incluindo a participação do Ministro Carlos Rosero e estratégias de implementação de políticas reparatórias.',
                'data_painel' => '2025-11-12',
                'hora_inicio' => '09:30:00',
                'hora_fim' => '12:00:00',
                'local' => 'Auditório da Faculdade de Engenharia da UFMG',
                'palestrantes' => 'Carlos Rosero (Colômbia), David Comissiong, Eric Phillips, Agustin Lao-Montes',
                'mediador' => 'A definir'
            ),
            array(
                'titulo' => 'Panafricanismo e a luta pró-reparações em Angola, Moçambique e São Tomé e Príncipe',
                'descricao' => 'Experiências africanas de reparações e panafricanismo nos países lusófonos, trazendo perspectivas continentais sobre políticas de reparação histórica.',
                'data_painel' => '2025-11-12',
                'hora_inicio' => '13:20:00',
                'hora_fim' => '16:00:00',
                'local' => 'Auditório da Faculdade de Engenharia da UFMG',
                'palestrantes' => 'Elizabeth Cruz, Paulo Gamba, Conceição Queiroz, Maria das Neves, José Lingna Nafafé (Guiné Bissau)',
                'mediador' => 'A definir'
            )
        );
        
        foreach ($paineis as $painel) {
            $wpdb->insert(
                $wpdb->prefix . 'seminario_paineis',
                $painel
            );
        }
    }
    
    private function insert_conferencistas() {
        global $wpdb;
        
        $conferencistas = array(
            array(
                'nome' => 'Anielle Franco',
                'cargo' => 'Ministra da Igualdade Racial',
                'instituicao' => 'Ministério da Igualdade Racial - Brasil',
                'pais' => 'Brasil',
                'biografia' => 'Anielle Franco é jornalista, ativista e política brasileira. Irmã da vereadora Marielle Franco, assassinada em 2018, Anielle se tornou uma das principais vozes na luta por justiça e igualdade racial no Brasil. Como Ministra da Igualdade Racial, lidera políticas públicas para combater o racismo estrutural.',
                'foto_url' => plugins_url('assets/imgs/image_1759046662766.png', dirname(__FILE__) . '/../seminario-sistema-completo.php'),
                'paineis_participacao' => '1',
                'tipo' => 'ministra'
            ),
            array(
                'nome' => 'Macaé Evaristo',
                'cargo' => 'Ministra dos Direitos Humanos',
                'instituicao' => 'Ministério dos Direitos Humanos - Brasil',
                'pais' => 'Brasil',
                'biografia' => 'Macaé Evaristo é educadora, pesquisadora e política brasileira. Doutora em Educação, foi Secretária de Estado de Educação de Minas Gerais e tem ampla experiência em políticas públicas educacionais e de direitos humanos.',
                'foto_url' => plugins_url('assets/imgs/image_1759046690819.png', dirname(__FILE__) . '/../seminario-sistema-completo.php'),
                'paineis_participacao' => '1',
                'tipo' => 'ministra'
            ),
            array(
                'nome' => 'Mireille Fanon Mendès-France',
                'cargo' => 'Consultora em Direito e Co-presidenta da Fundação Frantz Fanon',
                'instituicao' => 'Fundação Frantz Fanon - França',
                'pais' => 'França',
                'biografia' => 'Mireille Fanon-Mendès-France é jurista e ativista francesa, filha do renomado pensador Frantz Fanon. Consultora em Direito, lecionou no âmbito da Educação Nacional e trabalhou na UNESCO e na Assembleia Nacional francesa. Foi perita independente na ONU e fundou a Fundação Frantz Fanon, da qual é atualmente co-presidenta.',
                'foto_url' => plugins_url('assets/imgs/image_1759046717057.png', dirname(__FILE__) . '/../seminario-sistema-completo.php'),
                'paineis_participacao' => '2',
                'tipo' => 'conferencista'
            ),
            array(
                'nome' => 'Ndongo Samba Sylla',
                'cargo' => 'Economista responsável pela pesquisa e políticas do IDEAS para a Região África',
                'instituicao' => 'IDEAS - International Development Economics Associates',
                'pais' => 'Senegal',
                'biografia' => 'Economista no escritório de Dakar/Senegal da Fundação Rosa Luxemburg (2012-2023) e Conselheiro Técnico na Presidência do Senegal (2006-2009). Atualmente, é o economista responsável pela pesquisa e pelas políticas do IDEAS para a Região África. Especialista em neocolonialismo monetário, autor de "The Fair Trade Scandal" e "A Última Moeda Colonial da África".',
                'foto_url' => plugins_url('assets/imgs/image_1759046741469.png', dirname(__FILE__) . '/../seminario-sistema-completo.php'),
                'paineis_participacao' => '2,3',
                'tipo' => 'conferencista'
            ),
            array(
                'nome' => 'Eliane Barbosa da Conceição',
                'cargo' => 'Doutora em Administração e Diretora da Plataforma Justa',
                'instituicao' => 'UNILAB - Universidade da Integração Internacional da Lusofonia Afro-Brasileira',
                'pais' => 'Brasil',
                'biografia' => 'Doutora em Administração pela Fundação Getúlio Vargas, Professora da Universidade da Integração Internacional da Lusofonia Afro-Brasileira e Diretora da Plataforma Justa. Especialista em políticas públicas e justiça fiscal, pesquisadora das desigualdades raciais no Brasil.',
                'foto_url' => plugins_url('assets/imgs/image_1759046793175.png', dirname(__FILE__) . '/../seminario-sistema-completo.php'),
                'paineis_participacao' => '2',
                'tipo' => 'conferencista'
            ),
            array(
                'nome' => 'Carlos Rosero',
                'cargo' => 'Ministro da Igualdade e Equidade da Colômbia',
                'instituicao' => 'Ministério da Igualdade e Equidade - Colômbia',
                'pais' => 'Colômbia',
                'biografia' => 'Antropólogo e líder comunitário de Buenaventura. Ministro da Igualdade e Equidade da Colômbia, após a saída da vice-presidente Francia Márquez. Vasta experiência na defesa dos direitos das comunidades afro-colombianas. Trabalhou ativamente na criação da Lei 70, que adotou o procedimento para o reconhecimento do direito à propriedade coletiva das "terras comunitárias negras".',
                'foto_url' => plugins_url('assets/imgs/image_1759046767615.png', dirname(__FILE__) . '/../seminario-sistema-completo.php'),
                'paineis_participacao' => '5',
                'tipo' => 'ministro'
            ),
            array(
                'nome' => 'Luzia Moniz',
                'cargo' => 'Jornalista, Socióloga e Cofundadora da PADEMA',
                'instituicao' => 'PADEMA - Plataforma para o Desenvolvimento da Mulher Africana',
                'pais' => 'Portugal',
                'biografia' => 'Jornalista, socióloga e ativista nascida em Angola, que fez de Portugal sua terra de adoção. Cofundadora da PADEMA (Plataforma para o Desenvolvimento da Mulher Africana). Liderou o Desk África da Agência Angola Press (ANGOP) em Luanda por cinco anos, coordenando o noticiário africano. Em 1989, foi transferida para Portugal como delegada da mesma agência.',
                'foto_url' => '',
                'paineis_participacao' => '4',
                'tipo' => 'conferencista'
            ),
            array(
                'nome' => 'Conceição Queiroz',
                'cargo' => 'Grande Repórter e Escritora',
                'instituicao' => 'TVI e CNN Portugal',
                'pais' => 'Portugal',
                'biografia' => 'Jornalista moçambicana que se destaca em Portugal pela sua coragem e compromisso com a verdade. Desde 1994, atua como grande repórter na TVI e na CNN Portugal, tendo realizado coberturas em diversos países africanos. Seu trabalho investigativo já foi reconhecido com mais de 20 prémios. Autora de livros como "Serviço de Urgência", "Os Meninos da Jamba" e "A Vida Privada das Elites do Estado Novo".',
                'foto_url' => '',
                'paineis_participacao' => '4,6',
                'tipo' => 'conferencista'
            ),
            array(
                'nome' => 'José Lingna Nafafé',
                'cargo' => 'Professor Catedrático Associado de História',
                'instituicao' => 'Universidade de Londres',
                'pais' => 'Guiné Bissau',
                'biografia' => 'Historiador guineense, residente em Londres. Professor Catedrático Associado de História. Autor do livro "Lourenço da Silva Mendonça e o Movimento Negro Atlântico Abolicionista no século XVII", publicado em 2022 pela Cambridge University Press. Especialista em história das Irmandades do Rosário e reparações do Vaticano.',
                'foto_url' => '',
                'paineis_participacao' => '6',
                'tipo' => 'conferencista'
            )
        );
        
        foreach ($conferencistas as $conferencista) {
            $wpdb->insert(
                $wpdb->prefix . 'seminario_conferencistas',
                $conferencista
            );
        }
    }
    
    private function insert_observadores() {
        global $wpdb;
        
        $observadores = array(
            array(
                'nome' => 'Angela Davis',
                'cargo' => 'Professora Emérita',
                'instituicao' => 'Universidade da Califórnia',
                'pais' => 'Estados Unidos',
                'biografia' => 'Angela Davis é uma das mais importantes ativistas pelos direitos civis e professora emérita da Universidade da Califórnia. Símbolo da luta contra o racismo e o sistema prisional, é autora de diversas obras sobre feminismo, abolicionismo e justiça social.',
                'foto_url' => '',
                'area_especialidade' => 'Direitos Civis, Abolicionismo Prisional'
            ),
            array(
                'nome' => 'Frederico Pita',
                'cargo' => 'Cientista Político e Ativista Afro-argentino',
                'instituicao' => 'CLACSO - Conselho Latino-Americano de Ciências Sociais',
                'pais' => 'Argentina',
                'biografia' => 'Representante do CLACSO. Cientista político formado pela Universidade de Buenos Aires e ativista afro-argentina. Fundador da Diáspora Africana da Argentina (DIAFAR). Membro da Articulação Regional de Afrodescendentes das Américas e do Caribe (ARAAC). Nascido em Buenos Aires em 1979, o ativismo pela causa afro-argentina faz parte da história de sua família.',
                'foto_url' => '',
                'area_especialidade' => 'Ciências Sociais, Movimento Afro-latino-americano'
            ),
            array(
                'nome' => 'Nilma Lino Gomes',
                'cargo' => 'Pedagoga e Ex-Ministra da Igualdade Racial',
                'instituicao' => 'UNILAB - Universidade da Integração Internacional da Lusofonia Afro-Brasileira',
                'pais' => 'Brasil',
                'biografia' => 'Pedagoga e pesquisadora mineira, primeira mulher negra a reitorar uma universidade federal no Brasil (UNILAB, 2013) e ex‑ministra da Igualdade Racial (2015‑2016). Referência nacional em educação das relações étnico-raciais.',
                'foto_url' => '',
                'area_especialidade' => 'Educação, Relações Étnico-raciais'
            ),
            array(
                'nome' => 'Zezito de Araújo',
                'cargo' => 'Educador Popular e Militante',
                'instituicao' => 'Movimento de Direitos Humanos',
                'pais' => 'Brasil',
                'biografia' => 'Educador popular e militante dos direitos humanos. Ativista histórico do movimento negro brasileiro com ampla experiência em educação popular e formação política.',
                'foto_url' => '',
                'area_especialidade' => 'Educação Popular, Direitos Humanos'
            ),
            array(
                'nome' => 'Conceição Evaristo',
                'cargo' => 'Escritora, Poetisa e Ensaísta',
                'instituicao' => 'Literatura Brasileira',
                'pais' => 'Brasil',
                'biografia' => 'Escritora, poetisa e ensaísta brasileira. Uma das principais vozes da literatura afro-brasileira contemporânea, desenvolve o conceito de "escrevivência" em suas obras. Doutora em Literatura Comparada pela UFF.',
                'foto_url' => '',
                'area_especialidade' => 'Literatura Afro-brasileira, Crítica Literária'
            ),
            array(
                'nome' => 'Ivair Augusto Alves dos Santos',
                'cargo' => 'Babalawo, Professor e Ativista',
                'instituicao' => 'Movimento de Direitos Humanos',
                'pais' => 'Brasil',
                'biografia' => 'Babalawo, professor e ativista dos direitos humanos. Líder religioso do candomblé e defensor da liberdade religiosa e dos direitos das comunidades tradicionais de matriz africana.',
                'foto_url' => '',
                'area_especialidade' => 'Religiões Afro-brasileiras, Direitos Humanos'
            ),
            array(
                'nome' => 'Henrique Antunes Cunha Junior',
                'cargo' => 'Engenheiro, Sociólogo e Professor Titular',
                'instituicao' => 'Universidade Federal do Ceará',
                'pais' => 'Brasil',
                'biografia' => 'Engenheiro, sociólogo, historiador e professor titular da Universidade Federal do Ceará. Ativista e pesquisador renomado em estudos sobre negritude, urbanismo africano e educação da população negra no Brasil.',
                'foto_url' => '',
                'area_especialidade' => 'Negritude, Urbanismo Africano, Educação'
            ),
            array(
                'nome' => 'Ana Célia Silva',
                'cargo' => 'Pedagoga e Professora Titular Aposentada',
                'instituicao' => 'UNEB - Universidade do Estado da Bahia',
                'pais' => 'Brasil',
                'biografia' => 'Pedagoga, professora titular aposentada da UNEB e referência nacional na luta antirracista, especialmente por suas pesquisas sobre representações do negro nos livros didáticos desde os anos 1980.',
                'foto_url' => '',
                'area_especialidade' => 'Educação Antirracista, Análise de Livros Didáticos'
            )
        );
        
        foreach ($observadores as $observador) {
            $wpdb->insert(
                $wpdb->prefix . 'seminario_observadores',
                $observador
            );
        }
    }
    
    private function insert_configuracoes() {
        global $wpdb;
        
        $configuracoes = array(
            array(
                'chave' => 'evento_nome',
                'valor' => 'II Seminário Internacional Pró-Reparações: Um Projeto de Nação',
                'descricao' => 'Nome oficial do evento'
            ),
            array(
                'chave' => 'evento_subtitulo',
                'valor' => 'I Encontro do Foro Popular Pró-Reparações - Ciclo de Debates na Assembleia Legislativa',
                'descricao' => 'Subtítulo oficial do evento'
            ),
            array(
                'chave' => 'evento_data_inicio',
                'valor' => '2025-11-10',
                'descricao' => 'Data de início do evento'
            ),
            array(
                'chave' => 'evento_data_fim',
                'valor' => '2025-11-14',
                'descricao' => 'Data de término do evento'
            ),
            array(
                'chave' => 'inscricoes_abertas',
                'valor' => '1',
                'descricao' => 'Status das inscrições (1 = abertas, 0 = fechadas)'
            ),
            array(
                'chave' => 'limite_paineis_por_pessoa',
                'valor' => '3',
                'descricao' => 'Número máximo de painéis por inscrição'
            ),
            array(
                'chave' => 'email_confirmacao',
                'valor' => '1',
                'descricao' => 'Enviar email de confirmação (1 = sim, 0 = não)'
            ),
            array(
                'chave' => 'organizacao_principal',
                'valor' => 'Coletivo Minas Pró-Reparações',
                'descricao' => 'Organização principal do evento'
            ),
            array(
                'chave' => 'orcamento_total',
                'valor' => '829500.00',
                'descricao' => 'Orçamento total do seminário em reais'
            ),
            array(
                'chave' => 'contexto_ancestral',
                'valor' => '330 anos de Zumbi dos Palmares - Centenário de Frantz Fanon, Malcolm X e Carlos Moura - 50 anos de Independência de Angola - Em repúdio pelo 2º Ano do Genocídio do Povo Palestino',
                'descricao' => 'Contexto histórico e ancestral do evento'
            ),
            array(
                'chave' => 'site_oficial',
                'valor' => 'reparacoeshistoricas.org',
                'descricao' => 'Site oficial do movimento'
            )
        );
        
        foreach ($configuracoes as $config) {
            $wpdb->replace(
                $wpdb->prefix . 'seminario_configuracoes',
                $config
            );
        }
    }
    
    // Métodos CRUD para inscrições
    public function get_inscricoes($search = '', $limit = 20, $offset = 0) {
        global $wpdb;
        
        $where = '';
        if (!empty($search)) {
            $search = '%' . $wpdb->esc_like($search) . '%';
            $where = $wpdb->prepare(" WHERE nome LIKE %s OR email LIKE %s OR pais LIKE %s", $search, $search, $search);
        }
        
        $sql = "SELECT * FROM {$wpdb->prefix}seminario_inscricoes{$where} ORDER BY data_inscricao DESC LIMIT %d OFFSET %d";
        return $wpdb->get_results($wpdb->prepare($sql, $limit, $offset));
    }
    
    public function get_inscricao($id) {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}seminario_inscricoes WHERE id = %d", $id));
    }
    
    public function create_inscricao($data) {
        global $wpdb;
        return $wpdb->insert($wpdb->prefix . 'seminario_inscricoes', $data);
    }
    
    public function update_inscricao($id, $data) {
        global $wpdb;
        return $wpdb->update($wpdb->prefix . 'seminario_inscricoes', $data, array('id' => $id));
    }
    
    public function delete_inscricao($id) {
        global $wpdb;
        return $wpdb->delete($wpdb->prefix . 'seminario_inscricoes', array('id' => $id));
    }
    
    // Métodos CRUD para painéis
    public function get_paineis() {
        global $wpdb;
        return $wpdb->get_results("SELECT * FROM {$wpdb->prefix}seminario_paineis ORDER BY data_painel, hora_inicio");
    }
    
    public function get_painel($id) {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}seminario_paineis WHERE id = %d", $id));
    }
    
    public function create_painel($data) {
        global $wpdb;
        return $wpdb->insert($wpdb->prefix . 'seminario_paineis', $data);
    }
    
    public function update_painel($id, $data) {
        global $wpdb;
        return $wpdb->update($wpdb->prefix . 'seminario_paineis', $data, array('id' => $id));
    }
    
    public function delete_painel($id) {
        global $wpdb;
        return $wpdb->delete($wpdb->prefix . 'seminario_paineis', array('id' => $id));
    }
    
    // Métodos CRUD para conferencistas
    public function get_conferencistas() {
        global $wpdb;
        return $wpdb->get_results("SELECT * FROM {$wpdb->prefix}seminario_conferencistas ORDER BY nome");
    }
    
    public function get_conferencista($id) {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}seminario_conferencistas WHERE id = %d", $id));
    }
    
    public function create_conferencista($data) {
        global $wpdb;
        return $wpdb->insert($wpdb->prefix . 'seminario_conferencistas', $data);
    }
    
    public function update_conferencista($id, $data) {
        global $wpdb;
        return $wpdb->update($wpdb->prefix . 'seminario_conferencistas', $data, array('id' => $id));
    }
    
    public function delete_conferencista($id) {
        global $wpdb;
        return $wpdb->delete($wpdb->prefix . 'seminario_conferencistas', array('id' => $id));
    }
    
    // Métodos CRUD para observadores
    public function get_observadores() {
        global $wpdb;
        return $wpdb->get_results("SELECT * FROM {$wpdb->prefix}seminario_observadores ORDER BY nome");
    }
    
    public function get_observador($id) {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}seminario_observadores WHERE id = %d", $id));
    }
    
    public function create_observador($data) {
        global $wpdb;
        return $wpdb->insert($wpdb->prefix . 'seminario_observadores', $data);
    }
    
    public function update_observador($id, $data) {
        global $wpdb;
        return $wpdb->update($wpdb->prefix . 'seminario_observadores', $data, array('id' => $id));
    }
    
    public function delete_observador($id) {
        global $wpdb;
        return $wpdb->delete($wpdb->prefix . 'seminario_observadores', array('id' => $id));
    }
    
    // Métodos CRUD para locais
    public function get_locais() {
        global $wpdb;
        return $wpdb->get_results("SELECT * FROM {$wpdb->prefix}seminario_locais ORDER BY nome");
    }
    
    public function get_local($id) {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}seminario_locais WHERE id = %d", $id));
    }
    
    public function create_local($data) {
        global $wpdb;
        return $wpdb->insert($wpdb->prefix . 'seminario_locais', $data);
    }
    
    public function update_local($id, $data) {
        global $wpdb;
        return $wpdb->update($wpdb->prefix . 'seminario_locais', $data, array('id' => $id));
    }
    
    public function delete_local($id) {
        global $wpdb;
        return $wpdb->delete($wpdb->prefix . 'seminario_locais', array('id' => $id));
    }
    
    // Métodos para configurações
    public function get_configuracao($chave) {
        global $wpdb;
        $result = $wpdb->get_var($wpdb->prepare("SELECT valor FROM {$wpdb->prefix}seminario_configuracoes WHERE chave = %s", $chave));
        return $result;
    }
    
    public function update_configuracao($chave, $valor) {
        global $wpdb;
        return $wpdb->replace($wpdb->prefix . 'seminario_configuracoes', array(
            'chave' => $chave,
            'valor' => $valor
        ));
    }
    
    // Métodos para estatísticas
    public function get_estatisticas() {
        global $wpdb;
        
        $stats = array();
        
        // Total de inscrições
        $stats['total_inscricoes'] = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}seminario_inscricoes");
        
        // Inscrições hoje
        $stats['inscricoes_hoje'] = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}seminario_inscricoes WHERE DATE(data_inscricao) = CURDATE()");
        
        // Inscrições esta semana
        $stats['inscricoes_semana'] = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}seminario_inscricoes WHERE YEARWEEK(data_inscricao) = YEARWEEK(NOW())");
        
        // Estatísticas por painel
        $stats['inscricoes_por_painel'] = $wpdb->get_results("
            SELECT p.titulo, COUNT(i.id) as total_inscricoes 
            FROM {$wpdb->prefix}seminario_paineis p 
            LEFT JOIN {$wpdb->prefix}seminario_inscricoes i ON FIND_IN_SET(p.id, i.paineis_escolhidos)
            GROUP BY p.id, p.titulo 
            ORDER BY total_inscricoes DESC
        ");
        
        // Conferencistas por status
        $stats['conferencistas_confirmados'] = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}seminario_conferencistas WHERE tipo = 'conferencista'");
        $stats['conferencistas_pendentes'] = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}seminario_conferencistas WHERE tipo = 'palestrante'");
        
        // Observadores por status
        $stats['observadores_confirmados'] = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}seminario_observadores WHERE status = 'confirmado'");
        $stats['observadores_pendentes'] = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}seminario_observadores WHERE status = 'pendente'");
        
        // Horário com mais inscrições
        $stats['horario_popular'] = $wpdb->get_row("
            SELECT p.hora_inicio, p.titulo, COUNT(i.id) as total_inscricoes
            FROM {$wpdb->prefix}seminario_paineis p 
            LEFT JOIN {$wpdb->prefix}seminario_inscricoes i ON FIND_IN_SET(p.id, i.paineis_escolhidos)
            GROUP BY p.hora_inicio, p.titulo
            ORDER BY total_inscricoes DESC
            LIMIT 1
        ");
        
        // País mais comum
        $stats['pais_mais_comum'] = $wpdb->get_var("SELECT pais FROM {$wpdb->prefix}seminario_inscricoes GROUP BY pais ORDER BY COUNT(*) DESC LIMIT 1");
        
        // Total de painéis
        $stats['total_paineis'] = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}seminario_paineis");
        
        // Total de conferencistas
        $stats['total_conferencistas'] = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}seminario_conferencistas");
        
        // Total de observadores
        $stats['total_observadores'] = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}seminario_observadores");
        
        return $stats;
    }
    
    // Métodos de verificação de duplicados
    public function verificar_duplicado_inscricao($email, $excluir_id = 0) {
        global $wpdb;
        $query = "SELECT COUNT(*) FROM {$wpdb->prefix}seminario_inscricoes WHERE email = %s";
        $params = array($email);
        
        if ($excluir_id > 0) {
            $query .= " AND id != %d";
            $params[] = $excluir_id;
        }
        
        return $wpdb->get_var($wpdb->prepare($query, $params)) > 0;
    }
    
    public function verificar_duplicado_conferencista($nome, $excluir_id = 0) {
        global $wpdb;
        $query = "SELECT COUNT(*) FROM {$wpdb->prefix}seminario_conferencistas WHERE nome = %s";
        $params = array($nome);
        
        if ($excluir_id > 0) {
            $query .= " AND id != %d";
            $params[] = $excluir_id;
        }
        
        return $wpdb->get_var($wpdb->prepare($query, $params)) > 0;
    }
    
    public function verificar_duplicado_observador($nome, $excluir_id = 0) {
        global $wpdb;
        $query = "SELECT COUNT(*) FROM {$wpdb->prefix}seminario_observadores WHERE nome = %s";
        $params = array($nome);
        
        if ($excluir_id > 0) {
            $query .= " AND id != %d";
            $params[] = $excluir_id;
        }
        
        return $wpdb->get_var($wpdb->prepare($query, $params)) > 0;
    }
}
