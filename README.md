# Sistema de Gestão de Estoque

Sistema de gestão de estoque desenvolvido com Laravel, Livewire e TailwindCSS.

![Dashboard do Sistema de Gestão de Estoque](public/images/dashboard.png)

## Funcionalidades

- Gestão de Produtos
  - Cadastro com SKU automático
  - Suporte a diferentes unidades de medida (unidade, peso, metragem)
  - Controle de estoque mínimo
  - Categorização de produtos
  - Vinculação com fornecedores

- Gestão de Fornecedores
  - Cadastro completo com dados de contato
  - Histórico de produtos fornecidos

- Gestão de Categorias
  - Organização hierárquica de produtos
  - Facilita a busca e organização

- Solicitação de Material
  - Processo simplificado de requisição
  - Aprovação por níveis hierárquicos
  - Histórico de solicitações

- Movimentação de Estoque
  - Registro de entradas e saídas
  - Rastreabilidade completa
  - Histórico de movimentações

- Dashboard
  - Visão geral do estoque
  - Alertas de estoque baixo
  - Gráficos e indicadores

## Instalação

> **Nota:** Para visualizar corretamente a imagem do dashboard acima, salve a captura de tela fornecida como `public/images/dashboard.png` no projeto.

1. Clone o repositório
```bash
git clone https://github.com/seu-usuario/stock.git
cd stock
```

2. Instale as dependências
```bash
composer install
npm install
```

3. Configure o ambiente
```bash
cp .env.example .env
php artisan key:generate
```

4. Configure o banco de dados no arquivo .env
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=estoque
DB_USERNAME=root
DB_PASSWORD=
```

5. Execute as migrações e seeders
```bash
php artisan migrate --seed
```

6. Compile os assets
```bash
npm run dev
```

7. Inicie o servidor
```bash
php artisan serve
```

8. Acesse o sistema
```
URL: http://localhost:8000
Email: admin@example.com
Senha: password
```

## Requisitos do Sistema

- PHP >= 8.1
- MySQL >= 5.7
- Node.js >= 14.x
- Composer >= 2.0

## Créditos

### Desenvolvedores
- Douglas Rodrigues - Desenvolvedor Full Stack
  - LinkedIn: [linkedin.com/in/douglas-rodrigues-723a8832](https://linkedin.com/in/douglas-rodrigues-723a8832)
  - GitHub: [github.com/1dougweb](https://github.com/1dougweb)

- Douglas Rodrigues - Desenvolvedor Full Stack
  - LinkedIn: [linkedin.com/in/douglas-rodrigues-723a8832](https://www.linkedin.com/in/douglas-rodrigues-723a8832/)

### Tecnologias Utilizadas
- [Laravel](https://laravel.com) - Framework PHP
- [Livewire](https://laravel-livewire.com) - Framework Full-stack para Laravel
- [TailwindCSS](https://tailwindcss.com) - Framework CSS
- [Alpine.js](https://alpinejs.dev) - Framework JavaScript
- [MySQL](https://www.mysql.com) - Banco de Dados

## Licença

Este projeto está licenciado sob a [MIT License](LICENSE).
