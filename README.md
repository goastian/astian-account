# Authorization Server [En desarrollo Aun]
Servidor de autorización centralizado usando aouth-sever a través de laravel y laravel passport como capa de abstracción o capa puente.

**Documentacion oficial**
- [Laravel Documentation](https://laravel.com/docs/10.x)
- [Laravel Passport](https://laravel.com/docs/10.x/passport)


### OAuth2
Protocolo de authorizacion que permite conectar aplicaciones seguras en nuestros sistemas, si bien se manejan varios **grant_types** en **oauth2** para authorizar aplicaciones solo se dejaran los metodos seguros:
- **authorization_code**: diponible
- **refresh_token**: disponible
- ~~**client_credential**: por implementar~~
- **code** : PKCE(Proof Key for Code Exchange) Disponible
- ~~**implicit**: Inseguro - no disponible~~
- ~~**password**: Inseguro - integrar capa extra de seguridad para aplicaciones moviles~~

### SCOPES
Los escopes debera registrarse llevando el siguiente standar **PREFIJO_ACCION**, 
- el PREFIJO, este puede llevar el nombre del client, o caulquier otro que lo identifique
- ACCION, debe ser algo relacionado con lo que va a permitir hacer el scope en una sola palara
  - Por ejemplo, si hay un cliente dedicado a la administracion de personal podia ser asi
    - EMPLOYEE  : cuando se coloca solo el prefijo debe ser usado como el rango mas alto en ese cliente donde se este usando ese scope y tendra el control total sin necesida de asignarle mas scope
    - EMPLOYEE_CREATE : permitiria solo crear empleados
    - EMPLOYEE_READ: permitiria leer solo la informacion del empleado
    - EMPLOYEE_UPDATE: perimitiria solo actualizar informacion de un empleado
    - EMPLOYEE_DISABLE: permitiria solo desabilitar empleados 

### Gateways
Permiten desarrollar una gran variedad de aplicaciones monolíticas o micro servicios que se conecten al servidor principal sin necesidad de crear un sistema de autentificación y autorización para cada uno ellos, dando la facilidad para que puedan ser desarrolladas en cualquier lenguaje de programación con diferentes sistemas de administradores de base de datos. Estos Gateway para su mejor uso se pueden implementar a través de middleware en el cliente a desarrollar para dejar procesar la petición o denegarla dependiendo de la respuesta

- `/api/gateway/check-authentication` : 
  - verifica si un usuario esta authenticado
  - Headers
      - **Authorization** : token
  - Errores:
    - **401** : No Authenticado 
  
- `/api/gateway/check-scope` : 
  - verifca la authenticacion de un usuario y authorizacion del cliente a travez de scopes, verifica si almenos un **scope** esta presente, este gateway replican la funcionalidad de los [middlware de laravel](https://laravel.com/docs/10.x/passport#check-for-any-scopes)
  - Headers
    - **Authorization** : token
    - **X-SCOPES** : 'scope1 scope2 scope3 scope-N'
  - Errores:
    - **401** : No Authenticado
    - **403** : No Authorizado
  
- `/api/gateway/check-scopes` : 
  - igual al anterior pero verifica si todos los scopes estan prensentes antes de procesar la peticion, este gateway replican la funcionalidad del este [middlware de laravel](https://laravel.com/docs/10.x/passport#check-for-all-scopes)
  - Headers
    - **Authorization** : token
    - **X-SCOPES** : 'scope1 scope2 scope3 scope-N'
  - Errores:
    - **401** : No Authenticado
    - **403** : No Authorizado
  
- `/api/gateway/check-client-credentials` :
  - verifica si el usuario authorizado a travez del grant_type client_credentials este autorizado, el header X-SCOPES es opcional, este gateway replican la funcionalidad de este [middlware de laravel](https://laravel.com/docs/10.x/passport#client-credentials-grant-tokens)
  - Headers
    - **Authorization** : token
    - **X-SCOPES** : 'scope1 scope2 scope3 scopeN'
  - Errores:
    - **401** : No Authenticado
    - **403** : No Authorizado
  
- `/api/gateway/token-can` : 
  - verifica si un usuario tiene tiene acceso algun scope en el cliente, este gateway replica la funcionalidad este [metdo de laravel](https://laravel.com/docs/10.x/passport#checking-scopes-on-a-token-instance)
  - Headers
    - **Authorization** : token
    - **X-SCOPES** : 'scope'
  - Errores:
    - **401** : No Authenticado
    - **403** : No Authorizado
  
- `/api/gateway/user` :
  - retorna los datos del usuario autenticado en ese momento
  - Headers
    - **Authorization** : token
  - Errores: 
    - **401** : No Authenticado  
  
### Solicitar codigo de Authorizacion para consumir un cliente
El cliente para solicitar acceso debera implementar una ruta en su aplicacion con el siguiente codigo: **Ejemplo en laravel**
- Parametros:
  - **redirect_uri** : requerido, ruta en la aplicacion cliente donde desea que redireccione el codigo que sera intercambiado luego por credenciales
  - **state**: requerido, session de cliente que viaja a traves de la petición hasta el retorno del codigo con la finalidad de verificar la autenticidad de la aplicación
```
$request->session()->put('state', $state = Str::random(40));

$query = http_build_query([
    'redirect_uri' => 'cliente.dominio.dev/callback', //puedes usar variables de entorno
    'state' => $state, //requerido
]);

return redirect('auth.dominio.dev/grant-access?' . $query);
```

### Cambiar codigo de authorizacion por tokens (grant_type:authorization_code)
El cliente deberá agregar este codigo en la ruta que agrego en el parametro redirect_uri en la seccion anterior. 
```
/**
 * obteniedo data desde la url
*/
$data = explode('?', $request->state);

/**
 * variable que fue enviada desde el cliente en el parametro state
*/
$transport_state = $data[0];

/**
* Identificador unico del cliente
*/
$client_id = str_replace('id=', '', $data[1]);

/**
* Clave secreta del cliente, la clave ha sido hasheada antes de enviarse
*/
$client_secret = str_replace('secret=', '', $data[2]);

/**
* token temporal valido por 10 Segundos, despues de ese tiempo la peticion sera invalida a si los
* los demas datos sean correctos este token debe enviarse en una cabecera **X-CSRF-TOKEN** y es de un
* solo uso, esto no esta contemplado en el protocolo oauth2, siendo una implementacion extra de seguridad
*/
$csrf = str_replace('csrf=', '', $data[3]);

/**
* procedimiento para obtener el estado de la sesion actual
*/
$state = $request->session()->pull('state');

/**
* verificacion de la sesion enviada con la actual estas deben coicidar para que la peticion 
* pueda realizar
*/
throw_unless(
    strlen($state) > 0 && $state === $transport_state,
    InvalidArgumentException::class,
    'Invalid state value.'
);

/**
* Se realiza una peticion usando el metodo POST hacia el servidor de authorizacion
* 
*/
$response = Http::withHeaders([
    'X-CSRF-TOKEN' => $csrf,
])->asForm()->post('auth.dominio.dev/api/oauth/token', [
    'grant_type' => 'authorization_code',
    'client_id' => $client_id,
    'client_secret' => $client_secret,
    'redirect_uri' => cliente.dominio.dev/callback, //puedes usar variables de entorno
    'code' => $request->code,
]);

/**
* Obtiene las credenciales de autorizacion, esta peticion retornoa los siguientes valores
* token_type: Bearer
* expires_in: tiempo en segundo de expiracion
* access_token: token jwt el cual debe ser usado para realizar peticiones en el Header Authorization
* refresh_token: token que permite renovar el access_token caducado, debe almacenarce en un ligar seguro
* X-CSRF-REFRESH: Header que debe ser enviado junto con el refresh_token en los headers, el X-CSRF-REFRESH es de un solo uso, implementacion adicional de seguridad
*/
return $response->json();
```

### Ronovar token con el refesh_token
```
/**
* Se realiza una peticion usando el metodo POST hacia el servidor de authorizacion con los siguientes
* datos
*/

$response = Http::withHeaders([
    'X-CSRF-REFRESH' => $csrf,
])->asForm()->post('auth.dominio.dev/api/oauth/token', [
    'grant_type' => 'refresh_token', //tipo de authorizacion
    'client_id' => $client_id, //obtenido cuando se genero el code de intercambio por token
    'client_secret' => $client_secret, //obtenido cuando se genero el code de intercambio por token
    'refresh_token' => 'refresh_token', //refresh token obtenido cuand se intercabio el codigo
]);

```