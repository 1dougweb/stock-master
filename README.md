# Sistema de Gestão de Estoque

Sistema de gestão de estoque desenvolvido com Laravel, Livewire e TailwindCSS.

![Dashboard do Sistema de Gestão de Estoque](https://lh3.googleusercontent.com/fife/ALs6j_GZVJFXnFvANNXFVG6R2kfmLXhd7UrLUp3vqsaQT53V88IT_2mEo0vNFFobb0PFgz_5ibnnd_3dEGXg7MBfnnqIyV5bp7S_JCZuepmQIsaJ2yx2h25BeXrcyuzMMPJrW1UT2eJBqNI1Q73x1DEcwUGB4qIwodHHpnVcrCTearJTgoI-yRA0FVNbTAno55qkJGfRcxKytAwBoCjjeGzzet-Xma4mCzDjnLdYS45a2CelxvG_Wuyxfbw3Irepafhhk_1bIgiQvtRNVVF3RLz3RNJULcROvPgH49TTNZelrkAZUJQuVfrUSoXyBPxi7vu1yXQGUsUHRaOhkBR3Lmgbt_zSwej94MxKA23sHPk0eNeagQm3YtCIEVp-oqxYr_rC9oxxKKcOh8RCdZ4Au8EKUwMHV5uOPN2DRA4ZgNQpM6V5giMmUoN0Zn9CR-smgtNn3leeO98MH9gOFkYagsS4ZIBcPa2sEXZNCZANJ4_QBaTW90eHzuhWKds_ZD0APGsPKzSctoS7mipp6BlYnFjyP8Ufnnudds4GTht6aXovskMXNFvgM_o5KDJ0l8zWI9sn1uZ2_gtfsJc3zQSw6D1qTAZAnCsMkRQ8kHRe1hwZHe7Uzz29gruPL8tr3iyjT0wP4mcE2hMi9FMwBSK_JTjACMwcy-YFPRv1HlKWiSB0mRPR8k8i31UIbCagRHzM7ri4sA62C4-GwjzDRH4OTkX8KHnOfpZXRq4pN7bO64z6X5Ogi0_nPYu_eZDVdeQt96Uk8OGk-2RWE7HDZSHwm5tNo5wMehy6RaYPon2onwsbU9p0X5uz0BS_yO29QH1wzltxjShzggZbS0Uc8-3HwdCvjNurWO1StqnPZ5GEN0JDZuaa6vYSq1H0084BYHkGBX4G84VJoB-wVUHebCp-zr4RV8ozkw4U5KI1u9WgrURoPab_a9vJGOZq1DVRBeSz98p815m_2NzC7TwfPbJ0C0h356Ib5gWQQKy-v3WFMQmHB3_Wew6DwH2uvJ877aqTyMqmhgQ27aCr5H5YP3u-P8r8-DvehWu6mgLHWfDvN9Tb8FAQvXFKxhhnCE1Hl63hQMmBju8kUrJ2a2Dp2AoQyCA8hrXHm1I1uj4RiRkF909-mtgixWuYuAtpm3ucjYdJ56IJknNpJlMcMREYadGYlib-QXHQl55AEjI0aU2jOiDoR9r23WIkpiJEkFhB71-ajKfTtKDZPeGnmNZM9M6Yr3w87DmdrbArM34-d92wgcJQFfOXDDm1RvfLARJJ1B2QRoMYY3efEiRbe2dsdOgLT3wxdR1A2UWQtBqXCzi1vSLzfJXcpm4IJYwFJc9bkS2P9GLxQTPclLwIbirYx-Hzj_wfe1TpuuqxTnffs9hGyCPjlBz_or8YJqtek18iWV3Jr7Hi2apGQhSOrCVi5MFmyHHWGiE-InbtV7Z0eaMohjcWBdPNHJf-y1uP85kOANB7k4MvYM-1603ORfu9Bv_OT8b2lkMf8dIDkuYIBYmy7bUqJAcLY3suD-HpOtTXAzg212RoRqETEL1S0F5vPqmX0F75SPpULB1oha4GBF5B8yZC6zqRcc3BH1m0ay2qua3IRwVp9iohgay4z_UDoX9i3-Jdbsh_4A=w1920-h919?auditContext=prefetch)

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
