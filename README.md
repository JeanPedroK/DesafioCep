
---

# Calculadora de Distância entre CEPs

Esta aplicação foi desenvolvida para calcular a distância entre dois CEPs cadastrados, utilizando a [BrasilAPI](https://www.brasilapi.com.br). Além disso, os resultados calculados, assim como os CEPs consultados, são armazenados em um banco de dados. A funcionalidade também permite a importação de arquivos `.CSV` contendo pares de CEPs (CepOrigem, CepDestino) para processamento em lote.

## Funcionalidades

- **Cálculo de Distância**: Determina a distância entre dois CEPs utilizando a fórmula de Haversine, com base nas coordenadas geográficas fornecidas pela BrasilAPI.
- **Armazenamento de Resultados**: Os resultados das consultas são salvos em um banco de dados para consultas futuras.
- **Importação de CSV**: Suporte para importação de arquivos `.CSV` com múltiplos pares de CEPs para cálculo e armazenamento em massa.

## Pré-requisitos

Antes de começar, certifique-se de ter o [Docker](https://www.docker.com/get-started) instalado em sua máquina.

## Como Executar

1. Clone este repositório em sua máquina local:
    ```bash
    git clone https://github.com/JeanPedroK/DesafioCep.git
    ```
2. Acesse o diretório do projeto:
    ```bash
    cd DesafioCep
    ```
3. Execute o comando abaixo para iniciar os serviços usando Docker Compose:
    ```bash
    docker compose up -d
    ```
4. Acesse a aplicação em seu navegador:
    ```
    http://localhost:9090/
    ```

## Como Usar

### Interface Web

- **Consulta de Distância**: Preencha os campos de CEP de origem e destino, e clique em "Calcular" para obter a distância.
- **Histórico de Consultas**: Visualize as consultas anteriores, armazenadas no banco de dados.

### Importação de Arquivo CSV

1. Prepare um arquivo `.CSV` com o seguinte formato:
    ```
    CepOrigem,CepDestino
    01001000,02020030
    03030040,04040050
    ```
2. Utilize a interface de importação na aplicação para carregar o arquivo e processar os cálculos em lote.

## Estrutura do Projeto

- **Frontend**: Desenvolvido em Vue.js para interações com o usuário.
- **Backend**: Implementado em PHP, utilizando o microframework Flight.
- **Banco de Dados**: Gerenciado com MySQL.

---

