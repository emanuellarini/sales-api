FORMAT: 1A

# Document

# Salesmen [/api/vendedores]
Salesman resource representation.

## Display a listing of Salesmen. [GET /api/vendedores]


+ Response 200 (application/json)
    + Body

            [
                {
                    "id": 1,
                    "name": "Dummy",
                    "email": "dummy@dummy.com.br",
                    "commission": "R$ 1.110,00"
                }
            ]

+ Response 422 (application/json)
    + Body

            {
                "message": "Error while fetching records."
            }

## Store a new Salesmen. [POST /api/vendedores]


+ Request (application/json)
    + Body

            {
                "name": "Dummy",
                "email": "dummy@dummy.com.br"
            }

+ Response 200 (application/json)
    + Body

            {
                "id": 1,
                "name": "Dummy",
                "email": "dummy@dummy.com.br"
            }

+ Response 422 (application/json)
    + Body

            {
                "message": "Error while creating a Salesman."
            }

# Sales [/api/vendas]
Sale resource representation.

## Display a salesman's listing of Sales. [POST /api/vendas]


+ Response 200 (application/json)
    + Body

            [
                {
                    "id": 1,
                    "name": "Dummy",
                    "email": "dummy@dummy.com.br",
                    "amount": "R$ 100,00",
                    "commission": "R$ 8,50",
                    "date": "15/01/2017 22:10:00"
                }
            ]

+ Response 422 (application/json)
    + Body

            {
                "error": [
                    "Error while fetching records."
                ]
            }

## Store a Saleman's new Sale. [POST /api/vendas]


+ Request (application/json)
    + Body

            {
                "vendedor": 1,
                "valor": 100
            }

+ Response 200 (application/json)
    + Body

            {
                "id": 1,
                "name": "Dummy",
                "email": "dummy@dummy.com.br",
                "amount": "R$ 100,00",
                "commission": "R$ 8,50",
                "date": "15/01/2017 22:10:00"
            }

+ Response 422 (application/json)
    + Body

            {
                "error": "Error while fetching records."
            }