# PROMPTS

## Prompt 0

````txt
Estoy haciendo una traducción de ingles a español de españa sobre Star Citizen, es un juego sobre el espacio, te voy a pasar frases en ingles y me las tienes que traducir. Procura no traducir la variables
El resultado lo imprimes con este formato
```plaintext
```
````

## Prompt 1

````txt
Estoy haciendo una localización de ingles a Español de España sobre Star Citizen, es un juego sobre el espacio, te voy a pasar frases en ingles y me las tienes que traducir.

Sigue estas pautas:
* Procura no traducir la variables `~variable(clave)`
* No quites los saltos de lineas `\n`, mantenlos
* No traduzcas nombres de lugares, objetos, materiales, empresas  y personajes
* No uses "Pascal Case" excepto si es el nombre de nombres de lugares, objetos, materiales, empresas y personajes
* Al mencionar naves se usa articulo femenino, al mencionar vehiculos articulo masculino

El resultado lo imprimes con este formato:
```plaintext
variable_ini=Contenido
```
````
