# VetCare — Relatório de Correções

## Objetivo

Este documento registra os problemas encontrados no sistema VetCare, as correções aplicadas e os itens que permaneceram preservados durante a reforma geral.

O projeto continua sendo uma aplicação PHP com MySQL/MariaDB executada localmente no XAMPP.

---

## Credenciais preservadas

- **Usuário:** `admin`
- **Senha:** `admin123`

A senha padrão da avaliação prática não foi alterada.

---

## Doze erros identificados e resolvidos

### 1. Conexão incompatível com o banco

**Problema:** a conexão era feita com `mysqli`, enquanto o projeto utiliza `PDO`.

**Correção:** substituição da conexão por PDO, com banco `vetcare`, porta configurada e tratamento de erro.

**Arquivos envolvidos:**

- `config/database.php`

### 2. Nome incorreto do banco de dados

**Problema:** o banco estava configurado como `vetcare.sql`, em vez de `vetcare`.

**Correção:** configuração do nome correto do banco.

**Arquivo envolvido:**

- `config/database.php`

### 3. URLs fixas impediam o acesso em pastas diferentes

**Problema:** links absolutos apontavam apenas para `/vetcare_manutencao/...`.

**Correção:** criação de funções para calcular a raiz pública da aplicação conforme o diretório real no XAMPP.

**Arquivos envolvidos:**

- `config/auth.php`
- `includes/header.php`
- `login.php`
- `logout.php`

### 4. Login podia revelar qual usuário existe

**Problema:** usuário inexistente e senha incorreta produziam mensagens diferentes.

**Correção:** uso de uma única mensagem genérica para credenciais inválidas.

**Arquivo envolvido:**

- `login.php`

### 5. Sessão não era regenerada após o login

**Problema:** o ID da sessão permanecia igual após autenticação.

**Correção:** uso de `session_regenerate_id(true)` após validar o usuário.

**Arquivo envolvido:**

- `login.php`

### 6. Painel contabilizava animais inativos

**Problema:** a consulta não filtrava animais pelo campo `ativo`.

**Correção:** contagem somente dos animais ativos.

**Arquivo envolvido:**

- `index.php`

### 7. Busca de tutores considerava apenas a primeira palavra

**Problema:** o valor da busca era dividido e somente a primeira parte era usada.

**Correção:** pesquisa pelo texto completo informado pelo usuário.

**Arquivo envolvido:**

- `tutores/index.php`

### 8. Cálculo da idade ignorava mês e dia

**Problema:** a idade era calculada pela diferença entre anos, sem considerar aniversário.

**Correção:** cálculo pela diferença real entre a data de nascimento e a data atual.

**Arquivo envolvido:**

- `animais/index.php`

### 9. Filtro de status de consultas não era aplicado

**Problema:** a seleção era exibida, mas a consulta SQL não recebia o filtro.

**Correção:** filtro com consulta preparada e parâmetro seguro.

**Arquivo envolvido:**

- `consultas/index.php`

### 10. Exclusões não tinham confirmação nem padronização

**Problema:** registros eram excluídos diretamente por links GET.

**Correção preparada:** exclusão com validação do identificador e arquivos organizados. O escopo atual não inclui ainda a troca completa para modal de confirmação de exclusão.

**Arquivos envolvidos:**

- `tutores/excluir.php`
- `animais/excluir.php`
- `consultas/excluir.php`
- `tutores/index.php`
- `animais/index.php`
- `consultas/index.php`

### 11. Botão Sair não solicitava confirmação

**Problema:** o usuário perdia a sessão imediatamente ao clicar em Sair.

**Correção:** criação de modal com título, mensagem e botões definidos pela identidade visual.

**Título exibido:** `Deseja sair?`

**Mensagem exibida:** `Tem certeza de que deseja encerrar sua sessão no VetCare?`

**Arquivo envolvido:**

- `includes/header.php`

### 12. Logout não limpava completamente os dados da sessão

**Problema:** a sessão era destruída, mas os dados e o cookie não eram tratados completamente.

**Correção:** limpeza de `$_SESSION`, remoção do cookie e destruição da sessão.

**Arquivo envolvido:**

- `logout.php`

---

## Melhorias de interface implementadas

### Padronização de botões

Foi criado um padrão único para:

- botão verde para ações positivas;
- botão vermelho para ações destrutivas;
- botão cinza para ações neutras;
- altura mínima uniforme;
- tipografia consistente;
- arredondamento padronizado;
- estados de hover e foco;
- alinhamento uniforme dos ícones.

### Padronização de ícones

Foram padronizados SVG acessíveis para:

- `pencil`: editar;
- `trash-2`: excluir;
- `plus`: adicionar;
- `check`: salvar ou confirmar;
- `x`: cancelar ou fechar;
- `search`: consultar ou buscar;
- `logout`: encerrar sessão.

Os ícones possuem tamanho consistente, `aria-hidden`, tooltip por `title` e identificação pelo texto visível ou `aria-label`.

### Paleta aplicada

- **Verde/Turquesa:** `#0D9488`
- **Vermelho:** `#DC2626`
- **Cinza:** `#64748B`
- **Azul-marinho:** `#17243A`

### Confirmação de logout

A confirmação possui:

- fundo escuro com modal centralizado;
- título destacado;
- mensagem clara;
- botão cinza para Cancelar;
- botão verde para confirmar ação segura;
- fechamento pela tecla Escape;
- fechamento pelo clique fora da janela;
- foco automático no botão Cancelar;
- suporte básico a tela de diálogo acessível.

---

## Organização do código

Também foram reorganizados os principais arquivos PHP:

- separação da lógica PHP das estruturas HTML;
- indentação padronizada;
- variáveis com nomes mais claros;
- consultas preparadas;
- labels associadas aos campos pelos atributos `id`;
- formulários organizados em seções;
- redirects sem código comprimido;
- scripts de exclusão com validação do identificador;
- central de suporte com código mais legível;
- listagens com estrutura de tabela mais consistente.

---

## Arquivos principais modificados

- `config/database.php`
- `config/auth.php`
- `includes/header.php`
- `includes/footer.php`
- `assets/style.css`
- `login.php`
- `logout.php`
- `index.php`
- `tutores/index.php`
- `tutores/form.php`
- `tutores/excluir.php`
- `animais/index.php`
- `animais/form.php`
- `animais/excluir.php`
- `consultas/index.php`
- `consultas/form.php`
- `consultas/excluir.php`
- `chamados/index.php`
- `chamados/novo.php`
- `chamados/ver.php`

---

## Itens preservados

Os seguintes itens não foram modificados:

- usuário padrão `admin`;
- senha padrão `admin123`;
- regras gerais de autenticação;
- permissões existentes;
- estrutura das tabelas do banco;
- dados cadastrados;
- credenciais usadas na avaliação prática.

O alerta do Gerenciador de Senhas do Google foi registrado como observação, sem alterar a senha.

---

## Testes realizados

A sintaxe de todos os arquivos PHP foi validada com o PHP do XAMPP.

Resultado registrado:

- **Arquivos PHP validados:** 19
- **Arquivos com erro:** 0

Os itens abaixo devem ser testados no navegador após a execução da aplicação:

- login com `admin` e `admin123`;
- criação e edição de tutores;
- criação e edição de animais;
- criação e edição de consultas;
- exclusões de registros;
- filtros de consultas;
- filtros da central de suporte;
- abertura e atualização de chamados;
- confirmação de logout;
- cancelamento da confirmação de logout;
- encerramento real da sessão;
- navegação em celular e tela pequena;
- persistência dos dados após os cadastros.

---

## Limitação conhecida

As confirmações completas de exclusão ainda não substituíram integralmente as ações diretas GET em todas as páginas. A implementação foi organizada para permitir essa etapa em uma autorização posterior.

Também não foi realizada a migração da senha para hash criptográfico, pois isso poderia alterar a senha padrão usada na avaliação prática.

---

## Resultado atual

O VetCare apresenta:

- código PHP mais organizado;
- autenticação preservada;
- navegação com caminhos mais flexíveis;
- logout com confirmação visual;
- botões com padrão global;
- ícones consistentes e acessíveis;
- paleta visual padronizada;
- formulários e listagens mais claros;
- todos os arquivos PHP sem erro de sintaxe registrado.
