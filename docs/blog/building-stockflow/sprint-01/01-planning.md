# Sprint 01 — Planejamento

## Introdução

Todo projeto de software começa muito antes da primeira linha de código.

Antes de implementar o módulo Product Catalog, foi necessário definir um objetivo claro para a primeira sprint, estabelecer os limites da entrega e identificar quais funcionalidades realmente eram essenciais para o início do StockFlow.

Esse planejamento teve um papel importante para evitar a implementação de funcionalidades desnecessárias e manter o foco na construção de uma base sólida para o sistema.

---

## Objetivo da Sprint

O principal objetivo da Sprint 01 foi desenvolver o primeiro módulo funcional do StockFlow: o Product Catalog.

Esse módulo seria responsável por centralizar o gerenciamento de produtos, servindo como base para os demais módulos que serão desenvolvidos nas próximas sprints, como Categorias, Estoque e Fornecedores.

---

## Escopo da Sprint

Durante o planejamento, foram definidas as seguintes entregas:

- Implementação da entidade "Product".
- Criação da estrutura da tabela "products".
- Desenvolvimento do CRUD completo.
- Validação de dados utilizando Form Requests.
- Criação da API REST.
- Escrita de Feature Tests.
- Documentação técnica.
- Pull Request.
- Versionamento da entrega.
- Publicação da Release "v0.1.0".

Esses itens representaram o escopo da sprint e serviram como critério para considerar a entrega concluída.

---

## Decisões Técnicas

Algumas decisões foram tomadas antes do início da implementação para manter consistência ao longo do projeto.

### Entre elas:

- Utilizar Laravel como framework principal.
- Seguir uma arquitetura baseada em Services para centralizar regras de negócio.
- Manter os Controllers responsáveis apenas pela orquestração das requisições.
- Validar entradas através de Form Requests.
- Utilizar Eloquent ORM para persistência.
- Escrever testes automatizados para validar os principais fluxos da API.
- Utilizar Conventional Commits para manter um histórico organizado.
- Trabalhar com Feature Branches, Pull Requests, Tags e Releases para cada sprint.

---

## Resultado Esperado

Ao final da Sprint 01, o StockFlow deveria possuir um módulo de gerenciamento de produtos totalmente funcional, testado e documentado, estabelecendo uma base consistente para o desenvolvimento das próximas funcionalidades do sistema.

Esse planejamento definiu não apenas o que seria entregue, mas também o padrão de desenvolvimento que continuará sendo seguido ao longo da evolução do projeto.
