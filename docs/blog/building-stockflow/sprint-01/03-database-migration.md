# Database Migration — Construindo a primeira estrutura do banco de dados

## Introdução

Após definir o domínio da entidade Product, o próximo passo foi transformar esse modelo conceitual em uma estrutura persistente no banco de dados.

No Laravel, essa responsabilidade é atribuída às Migrations, que permitem versionar o esquema do banco de dados juntamente com o código da aplicação. Dessa forma, qualquer alteração estrutural passa a fazer parte do histórico do projeto, garantindo rastreabilidade e facilitando a colaboração entre desenvolvedores.

Nesta etapa da Sprint 01, foi criada a primeira migration do StockFlow: a tabela "products".

---

## O papel das Migrations

Uma migration representa uma mudança na estrutura do banco de dados.

Em vez de criar tabelas manualmente através de ferramentas gráficas ou comandos SQL executados diretamente no banco, todas as modificações passam a ser descritas em código.

Essa abordagem oferece diversos benefícios:

- Versionamento da estrutura do banco de dados.
- Facilidade para criar ambientes de desenvolvimento.
- Reprodutibilidade entre diferentes máquinas.
- Histórico completo das alterações estruturais.
- Maior segurança durante a evolução da aplicação.

As migrations tornam o banco de dados parte integrante do processo de desenvolvimento.

---

## Estrutura da tabela "products"

Com base na modelagem do domínio, foram definidos os campos necessários para representar um produto na primeira versão do StockFlow.

A estrutura inicial contempla:

- Identificador único ("id").
- Nome do produto.
- SKU único para identificação comercial.
- Descrição opcional.
- Preço.
- Indicador de produto ativo.
- Datas de criação e atualização.

Esses atributos atendem ao escopo da Sprint 01 e estabelecem uma base consistente para futuras evoluções.

---

## Decisões de modelagem

Durante a implementação da migration, algumas decisões foram tomadas para favorecer a evolução do sistema.

**Identificador**

Foi utilizado um identificador automático como chave primária da tabela, simplificando o relacionamento com futuras entidades.

**SKU único**

O campo SKU recebeu uma restrição de unicidade, impedindo que dois produtos sejam cadastrados com o mesmo código.

Essa regra protege a integridade dos dados e evita ambiguidades na identificação dos produtos.

**Status do produto**

Foi incluído um campo booleano para indicar se o produto está ativo.

Essa decisão permite implementar futuramente estratégias como desativação lógica de produtos, sem necessidade de removê-los imediatamente da base de dados.

**Timestamps**

Os campos "created_at" e "updated_at" foram mantidos para registrar automaticamente quando cada produto foi criado e modificado.

Essas informações são úteis tanto para auditoria quanto para futuras funcionalidades do sistema.

---

## Pensando na evolução

Embora a tabela seja simples nesta primeira versão, ela foi construída considerando a evolução do domínio.

Nas próximas sprints, novas estruturas poderão se relacionar com "products", como:

- Categorias.
- Fornecedores.
- Movimentações de estoque.
- Inventários.
- Histórico de alterações.

Esse crescimento incremental evita complexidade desnecessária nas primeiras etapas do projeto e permite que o banco acompanhe naturalmente a evolução da aplicação.

---

## Conclusão

A primeira migration do StockFlow representa mais do que a criação de uma tabela.

Ela estabelece a base de persistência sobre a qual todo o restante do sistema será construído.

Ao utilizar migrations como parte do fluxo de desenvolvimento, a estrutura do banco de dados passa a evoluir junto com o código da aplicação, mantendo um histórico claro das decisões tomadas durante cada sprint.

No próximo artigo, veremos como essa estrutura foi utilizada para implementar o CRUD completo do módulo Product Catalog, expondo suas operações por meio de uma API REST construída com Laravel.
