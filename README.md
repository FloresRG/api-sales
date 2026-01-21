# PRUEBA TÉCNICA – API DE VENTAS

## OBJETIVO GENERAL
Desarrollar una API REST que permita:
- Listar productos existentes
- Crear una venta con su detalle
- Listar ventas realizadas
- Eliminar una venta

El enfoque es evaluar lógica de negocio, relaciones y uso correcto de Laravel.

---

## CONTEXTO DEL PROYECTO

El proyecto YA CUENTA CON:
- Laravel 11 configurado como API
- Migraciones ejecutables
- Seeders de productos cargados
- Modelos base: Product, Sale, SaleDetail
- Rutas API habilitadas

php artisan migrate
php artisan db:seed

IMPORTANTE:
- Los productos ya existen
- NO se debe crear, editar ni eliminar productos
- Solo se deben consumir

---

## ALCANCE DE LA PRUEBA

El entrevistado debe implementar ÚNICAMENTE:
- Listado de productos (solo lectura)
- Creación de una venta con su detalle
- Listado de ventas
- Eliminación de una venta

NO ES NECESARIO:
- Autenticación
- Usar un usuario estatico de ejemplo
---

## PASO 1 – LISTAR PRODUCTOS (OBLIGATORIO)

ENDPOINT:
GET /api/products

QUÉ DEBE HACER:
- Retornar todos los productos existentes
- Mostrar id, nombre, precio y stock

QUÉ NO ES OBLIGATORIO:
- No crear productos
- No modificar productos
- No eliminar productos

RESPUESTA ESPERADA (EJEMPLO DE RESPUESTA):
[
  {
    "id": 1,
    "name": "Laptop Lenovo",
    "price": 7500,
    "stock": 10
  }
]

---

## PASO 2 – CREAR UNA VENTA CON DETALLE, USAR UN USUARIO ESTATICO EJEMPLO (user_id = 1)

ENDPOINT:
POST /api/sales

REQUEST ESPERADO:
{
  "user_id": 1,
  "items": [
    { "product_id": 1, "quantity": 2 },
    { "product_id": 3, "quantity": 1 }
  ]
}

QUÉ DEBE HACER:
- Validar que los productos existan
- Validar que el stock sea suficiente
- Crear el registro de la venta
- Crear el detalle de la venta
- Calcular subtotal por producto
- Calcular total de la venta
- Descontar el stock de los productos


RESPUESTA ESPERADA:
{
  "message": "Venta creada correctamente",
  "sale_id": 1,
  "total": 16500
}

---

## PASO 3 – LISTAR VENTAS

ENDPOINT:
GET /api/sales

QUÉ DEBE HACER:
- Listar todas las ventas registradas
- Incluir:
  - Fecha de la venta
  - Total
  - Detalle de productos vendidos (solo en la respuesta, no es obligatorio mostrarla)

RESPUESTA ESPERADA (EJEMPLO):
[
  {
    "id": 1,
    "total": 16500,
    "created_at": "2026-01-21",
    "details": [
      {
        "product": "Laptop Lenovo",
        "quantity": 2,
        "subtotal": 15000
      }
    ]
  }
]

---

## PASO 4 – ELIMINAR UNA VENTA

ENDPOINT:
DELETE /api/sales/{id}

QUÉ DEBE HACER:
- Eliminar la venta seleccionada

RESPUESTA ESPERADA:
{
  "message": "Venta eliminada correctamente"
}

---

## BUENAS PRÁCTICAS (NO OBLIGATORIAS, PERO SUMAN)

- Código limpio y legible
- Uso correcto de relaciones Eloquent
- Manejo básico de errores
- Separación de lógica si se desea (Services)

---


---

## CRITERIOS DE EVALUACIÓN

- Lista correctamente los productos
- Implementa creación de ventas con detalle
- Usa transacciones
- Calcula totales correctamente
- Descuenta stock
- Lista ventas con detalle
- Elimina ventas correctamente
- Código claro y mantenible

---

## RESULTADO ESPERADO FINAL

Al finalizar la prueba, la API debe permitir:
- Consultar productos existentes
- Registrar ventas
- Consultar ventas
- Eliminar ventas

Con datos consistentes y sin errores.

---

FRASE IDEAL DEL CANDIDATO:
“Los productos ya existen y solo los consumo.  
La venta se crea usando transacciones, guardando su detalle y descontando el stock.”
