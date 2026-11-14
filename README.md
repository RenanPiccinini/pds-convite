# PDS Convite 🚀

Sistema completo e profissional de gerenciamento de eventos e confirmação de presença (RSVP) online com integração ao WhatsApp e **sem limite de convites**.

Desenvolvido para **PDS (Piccinini Digital Solutions)** com Laravel 12+, Laravel Breeze, MySQL, Tailwind CSS, Alpine.js, efeitos 3D e Parallax.

---

## 🌟 Principais Recursos

- **♾️ Convites Ilimitados:** Crie eventos e receba centenas ou milhares de confirmações de presença sem limitações de planos.
- **💬 Envio Fácil via WhatsApp:** Links nominais ou de divulgação em grupos com mensagens pré-formatadas em 1 clique.
- **⚡ Confirmação Rápida:** O convidado informa apenas seu nome completo e acompanhantes opcionais, sem necessidade de cadastros demorados.
- **⏳ Contagem Regressiva em Tempo Real:** Cronômetro dinâmico exibindo dias, horas, minutos e segundos até o prazo final de confirmação.
- **📍 Integração com Mapas & Calendários:** Link direto para o Google Maps/Waze e botão para salvar o evento no Google Agenda e iCal.
- **📊 Painel Administrativo:**
  - Visualização em modo **Normal** ou agrupado por **Grupo (Titular + Acompanhantes)**.
  - Adição manual de convidados e acompanhantes.
  - Edição e exclusão com 1 clique.
  - Busca por nome e filtros de ordenação.
  - Exportação completa da lista em formato **Excel / CSV** formatado em UTF-8.
- **🎨 Design Tecnológico & 3D:**
  - Cartões interativos com efeito 3D Tilt.
  - Camadas parallax e orbes de luz neon cyberpunk.
  - Celebração com confetes personalizados nas cores da PDS.
  - Arquivo `.htaccess` configurado tanto na raiz quanto na pasta `/public` para deploy em qualquer hospedagem Apache/cPanel.

---

## 🛠️ Tecnologias Utilizadas

- **PHP 8.2+ / 8.5**
- **Laravel 12+**
- **Laravel Breeze** (Autenticação Blade & Alpine.js)
- **MySQL 8.0+**
- **Tailwind CSS & Vite**
- **Canvas Confetti**

---

## 🚀 Instalação e Execução Local

### 1. Clonar o repositório
```bash
git clone https://github.com/RenanPiccinini/pds-convite.git
cd pds-convite
```

### 2. Instalar dependências PHP e Node
```bash
composer install
npm install
npm run build
```

### 3. Configurar ambiente (.env)
Copie o arquivo `.env.example` para `.env`:
```bash
cp .env.example .env
php artisan key:generate
```

Configure seu banco de dados MySQL no arquivo `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pds_convite
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Executar Migrações e Seeders
```bash
php artisan migrate --seed
```

### 5. Iniciar Servidor
```bash
php artisan serve
```
Acesse em: `http://127.0.0.1:8000`

---

## 🔑 Credenciais Padrão do Administrador

- **E-mail:** `admin@pds.com`
- **Senha:** `password`

---

## 📄 Licença

Propriedade de **Piccinini Digital Solutions (PDS)**. Todos os direitos reservados.
