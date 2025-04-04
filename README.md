# 🎟️ Sistema de Venda de Ingressos com CodeIgniter 4

Este projeto é uma plataforma completa para gerenciamento e venda de ingressos para eventos como conferências, cursos, seminários, encontros e muito mais. Desenvolvido com **CodeIgniter 4**, **MySQL**, **Redis** e **MinIO**, o sistema oferece suporte completo a vendas com diferentes métodos de pagamento, gestão de participantes e checkout personalizado.

---

## 🚀 Tecnologias Utilizadas

- PHP 8.1 ou superior
- CodeIgniter 4 (appstarter)
- MySQL 8+
- Redis (cache e filas)
- MinIO (armazenamento de arquivos)
- SweetAlert2 (alertas modernos)
- jQuery + jQuery Mask (formatação de campos)

---

## 🧩 Principais Funcionalidades

### 🛒 Carrinho de Compras
- Adição e remoção de variações de ingressos
- Quantidade e valor total dinâmico
- Participantes vinculados a variações

### 🧍 Cadastro de Participantes
- Nome, e-mail, telefone e campos extras personalizados
- Vínculo direto com o pedido e variação

### 💳 Checkout
- Formulário completo com:
    - Nome completo
    - CPF
    - E-mail
    - Telefone
    - Endereço completo (CEP, rua, número, bairro, cidade, UF)
- Formatações automáticas com jQuery Mask
- Login com Google (via OAuth)
- Opções de pagamento:
    - Cartão de Crédito
    - Pix (com integração via API Pix Sicredi)
    - Pagamento na Entrega

### 🧾 Pedidos e Transações
- Models e migrations para:
    - `pedidos`
    - `itens_pedido`
    - `participantes`
- Slug dinâmico salvo na sessão para controle de redirecionamento
- Status inicial do pedido: `pendente`

---

## 📦 Estrutura de Pastas

```
app/
├── Controllers/
├── Models/
├── Views/
│   └── public/
├── Helpers/
├── Config/
├── Database/
│   └── Migrations/
public/
├── assets/
│   └── css, js, img
```

---

## 📄 Documentação de Releases
Consulte o arquivo [`docs/release-notes.md`](docs/release-notes.md) para ver melhorias por versão, funcionalidades adicionadas e checklist.

---

## ⚙️ Instalação e Atualização

### Instalação com Composer

```bash
composer create-project codeigniter4/appstarter sistema-ingressos
```

### Atualizações do Framework

```bash
composer update
```

Ao atualizar, consulte os [release notes](https://codeigniter.com/user_guide/changelogs/index.html) para ver se há arquivos que precisam ser copiados ou mesclados com os seus arquivos locais.

---

## 🔧 Configuração Inicial

1. Copie o arquivo `.env`:
```bash
cp env .env
```

2. Configure os dados de ambiente no `.env`, como:
    - baseURL
    - Banco de dados
    - Redis
    - MinIO

3. Rode as migrations:
```bash
php spark migrate
```

4. Inicie o servidor:
```bash
php spark serve
```

---

## 🌐 Requisitos do Servidor

- PHP 8.1 ou superior
- Extensões PHP obrigatórias:
    - intl
    - mbstring
    - json
    - mysqlnd (para MySQL)
    - libcurl (para requisições HTTP com CURL)

---

## 📃 Licença
Este projeto está licenciado sob a MIT License.

---

Desenvolvido com ❤️ para facilitar a gestão de eventos e vendas de ingressos.

Baseado no CodeIgniter 4 Starter App.