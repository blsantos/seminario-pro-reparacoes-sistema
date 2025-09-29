# Instalação do Plugin WordPress - II Seminário Internacional Pró-Reparações

## 📋 Como Instalar

### 1. Download do Plugin
Baixe o arquivo `seminario-sistema-completo.tar.gz` do Replit.

### 2. Extrair e Preparar
```bash
# Extrair o arquivo
tar -xzf seminario-sistema-completo.tar.gz

# Ou se preferir ZIP (criar ZIP manualmente):
# Compacte a pasta seminario-sistema-completo/ em um arquivo ZIP
```

### 3. Instalar no WordPress
1. **Via Painel Admin:**
   - Acesse: `wp-admin` → Plugins → Adicionar Novo → Enviar Plugin
   - Envie o arquivo ZIP
   - Clique em "Instalar Agora"
   - Ative o plugin

2. **Via FTP:**
   - Envie a pasta `seminario-sistema-completo/` para `/wp-content/plugins/`
   - Ative no painel admin em Plugins

### 4. Configuração Inicial
Após ativar o plugin:
- O menu "Seminário" aparecerá no admin do WordPress
- As tabelas do banco serão criadas automaticamente
- Os dados iniciais serão inseridos

## 🚀 Funcionalidades Instaladas

### ✅ Banco de Dados
- `wp_seminario_inscricoes` - Inscrições completas
- `wp_seminario_paineis` - 6 painéis temáticos
- `wp_seminario_conferencistas` - 9 conferencistas reais
- `wp_seminario_observadores` - 8 observadores internacionais
- `wp_seminario_configuracoes` - Configurações do evento

### ✅ Painel Administrativo
- **Dashboard** com estatísticas em tempo real
- **Gestão de Inscrições** com busca e CRUD completo
- **Painéis Temáticos** editáveis
- **Conferencistas** com biografias reais
- **Observadores Internacionais** gerenciáveis
- **Configurações** do evento

### ✅ Shortcodes para Frontend
```php
[seminario_inscricao]        // Formulário de inscrição
[seminario_paineis]          // Lista de painéis
[seminario_conferencistas]   // Lista de conferencistas  
[seminario_observadores]     // Lista de observadores
```

## 📊 Dados Reais Incluídos

### 🎯 Painéis (6 painéis temáticos):
1. **Estatuto da Igualdade Racial em MG** - 10/11 às 13:20
2. **Reparações e justiça fiscal** - 10/11 às 15:00
3. **Contexto internacional hostil** - 11/11 às 09:30
4. **Luta pró-reparações em Portugal/Espanha** - 11/11 às 13:40
5. **Panafricanismo no Caribe/Colômbia** - 12/11 às 09:30
6. **Panafricanismo em Angola/Moçambique** - 12/11 às 13:20

### 🎤 Conferencistas (9 personalidades):
- **Anielle Franco** - Ministra da Igualdade Racial
- **Macaé Evaristo** - Ministra dos Direitos Humanos
- **Mireille Fanon Mendès-France** - Fundação Frantz Fanon
- **Ndongo Samba Sylla** - Economista senegalês (IDEAS)
- **Carlos Rosero** - Ministro da Colômbia
- **Luzia Moniz** - PADEMA Portugal
- **Conceição Queiroz** - Jornalista TVI Portugal
- **José Lingna Nafafé** - Historiador Guiné Bissau
- **Eliane Barbosa** - UNILAB/Plataforma Justa

### 👁️ Observadores (8 especialistas):
- **Angela Davis** - Universidade da Califórnia
- **Frederico Pita** - CLACSO Argentina
- **Nilma Lino Gomes** - Ex-Ministra/UNILAB
- **Conceição Evaristo** - Escritora
- **Zezito de Araújo** - Educador Popular
- **Ivair Augusto** - Babalawo/Ativista
- **Henrique Cunha Jr** - UFC
- **Ana Célia Silva** - UNEB

## ⚙️ Configurações Importantes

### Orçamento Total: R$ 829.500,00
### Contexto Ancestral:
- 330 anos de Zumbi dos Palmares
- Centenário de Frantz Fanon, Malcolm X e Carlos Moura
- 50 anos de Independência de Angola
- Em repúdio pelo 2º Ano do Genocídio do Povo Palestino

### Site Oficial: reparacoeshistoricas.org

## ✅ Versão Final Corrigida
**Versão 3.1.0** - PLUGIN TOTALMENTE FUNCIONAL
- ✅ **Erro fatal "Class SeminarioDatabase not found" CORRIGIDO**
- ✅ **Autoloader robusto** para PHP 8.0+ e LiteSpeed
- ✅ **Singleton pattern** para maior estabilidade
- ✅ **Verificações de segurança** em todas as classes
- ✅ **Tratamento de erros** completo
- ✅ **Compatibilidade testada** com WordPress 6.8 + PHP 8.0

## 🛠️ Suporte Técnico

O plugin é 100% funcional e inclui:
- ✅ Validação de dados completa
- ✅ Segurança WordPress (nonces)
- ✅ AJAX sem recarregamento
- ✅ Design responsivo
- ✅ Email de confirmação
- ✅ Máscara de telefone
- ✅ Limite de painéis por pessoa
- ✅ Sistema de busca e filtros
- ✅ **ERRO FATAL CORRIGIDO** - Classes carregadas corretamente

## 📞 Contato
Para dúvidas sobre o plugin, contate a equipe através do site reparacoeshistoricas.org

---
**Desenvolvido para o Coletivo Minas Pró-Reparações** 🇧🇷