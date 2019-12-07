# Teste Alpes One

## Instalação

```bash 
git clone https://github.com/lucashtc/desafio-alpes-one 
```

### Instale as dependencias
```bash
composer install
```

### Execute o servidor embutido do Laravel
```bash
php artisan serve
``` 
## endpoints
### [GET]
api/busca/?veiculo={tipo do veiculo}&marca={marca}particular-origem/revenda-origem/novo-estado/seminovo-estado&registrosPagina={20}  
Exemplo: localhost:8000/busca/?veiculo=carro&marca=fiat&particular-origem&revenda-origem&novo-estado&seminovo-estado/&registrosPagina=100

### Parametros
- particular-origem string
- revenda-origem string
- novo-estado  string
- seminovo-estado string
- veiculo string('carro','caminhao','moto'|obrigatorio)
- estado string('seminovo','novo')
- modelo string
- ano string(formato yyyy-yyyy)
- preco string(formato int-int)
- financiamento string('com financiamento','sem financiamento')

### [GET]
api/detalhes/?id=id_do_carro  
Exemplo: api/detalhes/?id=bmw-320i-2008-2009--2237820