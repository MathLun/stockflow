Catalog Module

Objetivo

Gerenciar os produtos do sistema.

Responsabilidades

- Criar produtos
- Atualizar produtos
- Buscar produtos
- Excluir produtos

Endpoints

GET /api/products
POST /api/products
GET /api/products/{id}
PUT /api/products/{id}
DELETE /api/products/{id}

Regras de negócio

- SKU deve ser único.
- Nome é obrigatório.
- Preço deve ser positivo.
- Produto inicia ativo por padrão.

Testes

- Happy Path
- Error Tests
