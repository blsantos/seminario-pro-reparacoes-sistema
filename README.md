# Plugin WordPress - II Seminário Internacional Pró-Reparações

Sistema completo de gestão para o II Seminário Internacional Pró-Reparações: Um Projeto de Nação.

## 📋 Sobre o Projeto

Plugin WordPress desenvolvido para gerenciar todas as atividades do II Seminário Internacional Pró-Reparações, evento focado em reparações históricas no Brasil.

**Evento:** 10-14 de Novembro de 2025  
**Local:** Belo Horizonte - MG  
**Site:** [reparacoeshistoricas.org](https://reparacoeshistoricas.org)

## ✨ Funcionalidades

### 🎯 Painel Administrativo
- **Dashboard** com estatísticas em tempo real
- **Gestão de Inscrições** com busca e filtros
- **Painéis Temáticos** com informações completas
- **Conferencistas** com biografias e dados reais
- **Observadores Internacionais** com perfis detalhados
- **Configurações** do evento

### 🌐 Frontend
- **Formulário de inscrição** responsivo e funcional
- **Lista de painéis** com design moderno
- **Perfis de conferencistas** com fotos e biografias
- **Lista de observadores** internacionais

### ⚙️ Recursos Técnicos
- CRUD completo para todas as entidades
- AJAX sem recarregamento de página
- Validações client-side e server-side
- Email de confirmação automático
- Design responsivo
- Segurança WordPress (nonces, sanitização)

## 🚀 Instalação

1. Faça download do plugin
2. Faça upload para `/wp-content/plugins/`
3. Ative o plugin no WordPress Admin
4. Acesse o menu "Seminário" no painel administrativo

## 📊 Estrutura do Banco de Dados

O plugin cria 5 tabelas:
- `wp_seminario_inscricoes` - Inscrições do público
- `wp_seminario_paineis` - Painéis temáticos
- `wp_seminario_conferencistas` - Dados dos conferencistas
- `wp_seminario_observadores` - Observadores internacionais
- `wp_seminario_configuracoes` - Configurações do sistema

## 🎪 Painéis Temáticos

1. **Reparações e justiça fiscal**
2. **Enfrentando o contexto internacional hostil**
3. **Comunicação e soberania digital**
4. **Perpetuação das oligarquias**
5. **Panafricanismo no Caribe e Colômbia**
6. **Panafricanismo em Angola, Moçambique e São Tomé**

## 🎤 Conferencistas

- **Anielle Franco** - Ministra da Igualdade Racial
- **Macaé Evaristo** - Ministra dos Direitos Humanos
- **Mireille Fanon Mendès-France** - Presidente da Fundação Frantz Fanon
- **Ndongo Samba Sylla** - Economista senegalês
- **Eliane Barbosa da Conceição** - Professora UNILAB

## 👁️ Observadores Internacionais

- **Angela Davis** - Universidade da Califórnia
- **Achille Mbembe** - Universidade de Witwatersrand
- **Boaventura de Sousa Santos** - Universidade de Coimbra
- **Kimberlé Crenshaw** - Universidade de Columbia e UCLA

## 🔧 Shortcodes

- `[seminario_inscricao]` - Formulário de inscrição
- `[seminario_paineis]` - Lista de painéis
- `[seminario_conferencistas]` - Lista de conferencistas
- `[seminario_observadores]` - Lista de observadores

## 📁 Estrutura de Arquivos

```
seminario-sistema-completo/
├── seminario-sistema-completo.php    # Arquivo principal
├── includes/
│   ├── class-database.php            # Métodos CRUD
│   ├── class-admin.php               # Interface administrativa
│   ├── class-ajax.php                # Requisições AJAX
│   └── class-shortcodes.php          # Shortcodes frontend
├── assets/
│   ├── style.css                     # CSS completo
│   └── script.js                     # JavaScript funcional
└── templates/                        # Templates (futuro)
```

## 🛠️ Desenvolvimento

### Requisitos
- WordPress 5.0+
- PHP 7.4+
- MySQL 5.7+

### Tecnologias Utilizadas
- PHP (WordPress APIs)
- JavaScript (jQuery)
- CSS3 (Grid, Flexbox)
- AJAX
- MySQL

## 📝 Licença

Este projeto é desenvolvido para o Coletivo Minas Pró-Reparações e organizações parceiras.

## 🤝 Contribuição

Para contribuir com o projeto:
1. Faça um fork do repositório
2. Crie uma branch para sua feature
3. Commit suas mudanças
4. Faça push para a branch
5. Abra um Pull Request

## 📞 Suporte

Para suporte e dúvidas sobre o plugin, entre em contato através do site [reparacoeshistoricas.org](https://reparacoeshistoricas.org).

---

**Desenvolvido para a causa das reparações históricas no Brasil** 🇧🇷
