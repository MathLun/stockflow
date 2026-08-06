# Feature Testing — Garantindo a confiabilidade da API

## Introdução

Após concluir a implementação do CRUD do módulo Product Catalog, o próximo passo foi garantir que todas as funcionalidades realmente se comportassem conforme esperado.

Em vez de depender apenas de testes manuais através de ferramentas como Postman ou Insomnia, a Sprint 01 também incluiu a criação de Feature Tests, permitindo validar automaticamente o comportamento da API sempre que novas alterações forem realizadas.

Essa decisão faz parte da estratégia de construir um software preparado para evoluir de forma segura.

---

## Por que escrever testes automatizados?

À medida que um sistema cresce, aumenta também o risco de uma nova alteração quebrar funcionalidades já existentes.

Os testes automatizados reduzem esse risco ao verificar continuamente se os comportamentos esperados continuam válidos.

Além de aumentar a confiança durante o desenvolvimento, eles também servem como documentação executável da aplicação, descrevendo como cada endpoint deve responder em diferentes cenários.

---

## O que são Feature Tests?

No Laravel, os Feature Tests permitem validar o comportamento completo da aplicação.

Ao executar um teste, a requisição percorre o mesmo fluxo utilizado em produção:

- Roteamento.
- Validação.
- Controller.
- Services.
- Model.
- Banco de dados.
- Resposta HTTP.

Essa abordagem garante que diferentes camadas da aplicação funcionem corretamente em conjunto.

---

## Cenários testados

Durante a Sprint 01 foram implementados testes para os principais fluxos do módulo Product.

Entre eles:

**Operações de sucesso**

- Listar produtos.
- Criar um produto.
- Buscar um produto por identificador.
- Atualizar um produto.
- Remover um produto.

Cenários de erro

Também foram adicionados testes para situações que podem ocorrer durante o uso da API.

Como exemplo:

- Tentativa de cadastrar dois produtos utilizando o mesmo SKU.
- Busca por um produto inexistente.
- Atualização de um produto inexistente.
- Remoção de um produto inexistente.

Esses cenários ajudam a garantir que a aplicação responda corretamente mesmo diante de entradas inválidas ou recursos inexistentes.

---

## Benefícios obtidos

A adoção de testes automatizados desde a primeira sprint trouxe diversas vantagens para o projeto.

Entre elas:

- Maior confiança durante refatorações.
- Identificação rápida de regressões.
- Documentação dos comportamentos esperados.
- Facilidade para evoluir o sistema.
- Processo de desenvolvimento mais previsível.

Com essa base estabelecida, novos módulos poderão ser implementados mantendo o mesmo padrão de qualidade.

---

## Considerações finais

Os Feature Tests marcaram o encerramento técnico da Sprint 01.

Além de validar todas as operações do módulo Product Catalog, eles estabeleceram uma prática que continuará presente nas próximas etapas do desenvolvimento.

Mais do que verificar se o código funciona hoje, os testes garantem que ele continue funcionando conforme o StockFlow evolui.
