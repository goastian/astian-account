# Authorization Server [Testing]
Servidor de autorización centralizado usando aouth-sever a través de laravel y laravel passport como capa de abstracción o capa puente, implemeta broadcasting usando **laravel-echo-server** y **laravel-echo**, este servidor se desarrollo con la finalidad de facilitar la creación de aplicaciones como micro servicios y que se puedan conectar fácilmente al servidor principal como si fuesen un todo, con este servidor de autentificación y autorización puedes desarrollar micro servicios o monolíticos en cualquier lenguaje de programación y administrador de base de datos, facilitado de esta forma que los desarrolladores creen aplicaciones en el lenguaje de programación que mas dominen permitiendo crear aplicaciones mucho mas complejas. 

**Documentacion oficial**
- [Laravel Documentation](https://laravel.com/docs/10.x)
- [Laravel Passport](https://laravel.com/docs/10.x/passport)

## OAUTH2
Protocolo de authorizacion que permite conectar aplicaciones seguras en nuestros sistemas, si bien se manejan varios **grant_types** en **oauth2** para authorizar aplicaciones solo se dejaran los metodos seguros:
- **code** : PKCE(Proof Key for Code Exchange), response type
- **confidential**: response type para entornos seguros
- **authorization_code**: intercambia codigo por token jwt
- **refresh_token**: renova el token vencido
- **client_credential**: uso de recursos controlados
- ~~**implicit**: Inseguro~~ No disponible
- ~~**password**: Inseguro~~ No disponible
  
## CONFIGURACION
Toda la configuracion de las variable esta especificada en el archivo **.env.example**, todo lo siguiente debe realizar luego de configurar en archivo **.env**

#### INSTALAR LO NECESARIO
Dependecias de composer
```
composer install
```
Dependencias de javascrip
```
npm install
```
Generar llave o identificador de la aplicacion
```
php artisan key:generate
```
Crear las tablas en la BD
```
php artisan migrate
```
Generar llaves de passport para generar tokens a cliente y personal accces token, 
```
php artisan passport:install
```
Despues de ejecutar el comando anterior quedaran dos tipos de clientes
```
Personal access client created successfully.
Client ID: id
Client secret: secret
Password grant client created successfully.
Client ID: id
Client secret: secret

```
En las variables de entonrno en el archivo env deberas agregar los datos del **Personal Access Client**, esto te permitira crear token de acceso para consumir mediante algun cliente como postman u otro.
```
PASSPORT_PERSONAL_ACCESS_CLIENT_ID="id"
PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET="secret"
```

Si es solo para testear puedes ejecutarlo desde el terminal, pero lo recomendable es usarlo a traves de un servidor nginx o apache y asignarle un dominio
```
php artisan serve
```
Los siguientes comandos puedes usar **supervisor** para mantenerlos en ejecucion, pero si solo es para testear puedes usarlos directos en una terminal.
Antes debes configurar  [laraver-echo-server.json](https://github.com/tlaverdure/laravel-echo-server) con los parametros de tu servidory tambien instalarlo, en el link esta la documentacion necesaria

Luego de instalarlo y configurarlo en la raiz del projecto o en donde tu lo haya creado, dentro de esa ruta ejecutas el siguiente comando
```
laravel-echo-server start
```
Esto hara que las colas traten de enviar 3 veces el envento si este falla 2 veces
```
php artisan queue:listen --tries=3
```  
Para terminar, despues de que tengas todo configurado debes, compilar los archivos js y scss con vite
```
npm run build
```



## CREACION DE SCOPES
Los escopes debera registrarse llevando el siguiente standar **PREFIJO_ACCION**, 
- el PREFIJO, este puede llevar el nombre del client, o caulquier otro que lo identifique
- ACCION, debe ser algo relacionado con lo que va a permitir hacer el scope en una sola palara
  - Por ejemplo, si hay un cliente dedicado a la administracion de personal podia ser asi
    - EMPLOYEE  : cuando se coloca solo el prefijo debe ser usado como el rango mas alto en ese cliente donde se este usando ese scope y tendra el control total sin necesida de asignarle mas scope
    - EMPLOYEE_CREATE : permitiria solo crear empleados
    - EMPLOYEE_READ: permitiria leer solo la informacion del empleado
    - EMPLOYEE_UPDATE: perimitiria solo actualizar informacion de un empleado
    - EMPLOYEE_DISABLE: permitiria solo desabilitar empleados 

## GATEWAYS
Permiten desarrollar una gran variedad de aplicaciones monolíticas o micro servicios que se conecten al servidor principal sin necesidad de crear un sistema de autentificación y autorización para cada uno ellos, dando la facilidad para que puedan ser desarrolladas en cualquier lenguaje de programación con diferentes sistemas de administradores de base de datos. Estos Gateway para su mejor uso se pueden implementar a través de middleware en el cliente a desarrollar para dejar procesar la petición o denegarla dependiendo de la respuesta

- `/api/gateway/check-authentication` : 
  - verifica si un usuario esta authenticado
  - Headers
      - **Authorization** : token
  - Success
    - **200**: conexion exitosa
  - Errores:
    - **401** : No Authenticado 
  
- `/api/gateway/check-scope` : 
  - verifca la authenticacion de un usuario y authorizacion del cliente a travez de scopes, verifica si almenos un **scope** esta presente, este gateway replican la funcionalidad de los [middlware de laravel](https://laravel.com/docs/10.x/passport#check-for-any-scopes)
  - Headers
    - **Authorization** : token
    - **X-SCOPES** : 'scope1 scope2 scope3 scope-N'
  - Success
    - **200**: conexion exitosa
  - Errores:
    - **401** : No Authenticado
    - **403** : No Authorizado
  
- `/api/gateway/check-scopes` : 
  - igual al anterior pero verifica si todos los scopes estan prensentes antes de procesar la peticion, este gateway replican la funcionalidad del este [middlware de laravel](https://laravel.com/docs/10.x/passport#check-for-all-scopes)
  - Headers
    - **Authorization** : token
    - **X-SCOPES** : 'scope1 scope2 scope3 scope-N'
  - Success
    - **200**: conexion exitosa
  - Errores:
    - **401** : No Authenticado
    - **403** : No Authorizado
  
- `/api/gateway/check-client-credentials` :
  - verifica si el usuario authorizado a travez del grant_type client_credentials este autorizado, el header X-SCOPES es opcional, este gateway replican la funcionalidad de este [middlware de laravel](https://laravel.com/docs/10.x/passport#client-credentials-grant-tokens)
  - Headers
    - **Authorization** : token
    - **X-SCOPES** : 'scope1 scope2 scope3 scopeN'
  - Success
    - **200**: conexion exitosa
  - Errores:
    - **401** : No Authenticado
    - **403** : No Authorizado
  
- `/api/gateway/token-can` : 
  - verifica si un usuario tiene tiene acceso algun scope en el cliente, este gateway replica la funcionalidad este [metdo de laravel](https://laravel.com/docs/10.x/passport#checking-scopes-on-a-token-instance)
  - Headers
    - **Authorization** : token
    - **X-SCOPES** : 'scope'
  - Success
    - **200**: conexion exitosa
  - Errores:
    - **401** : No Authenticado
    - **403** : No Authorizado
  
- `/api/gateway/user` :
  - retorna los datos del usuario autenticado en ese momento
  - Headers
    - **Authorization** : token
  - Success
    - **201**: conexion exitosa
  - Errores: 
    - **401** : No Authenticado 
- `/api/gateway/logout`
  - Elimina la session actual en el microservicio
  - Headers
    - **Authorization**
  - Success
    - **201** : credenciales revocadas
  - Errores
    - **401**: No authenticado

## RESPONSE TYPE CONFIDENTIAL
#### SOLICITAR CÓDIGO DE AUTORIZACIÓN 
Para usar este metodo primero se debe registrar al cliente en tu panel administrativos en la session clientes, luego en la aplicacion cliente el desarrollador debera crear una ruta con el codigo proporcionado debajo.
- Parametros:
  - **redirect_uri** : URI de tu aplicacion donde recibiras el codigo y lo intercambiaras con el token jwt
  - **state**: session generada en tu aplicacion, su funcion es verificar la autenticidad de la peticion.
  - **response_type**: metodo de respuesta que el cliente procesa para generar un codigo
- Siguiente codigo: **Ejemplo en laravel con php**, debes asignar esta funcion a una URI
```
    /**
     * enevia una solicituda para obtener un codigo
     * @param \Illuminate\Http\Request $request
     * @return response(null, 302)
     */
    public function sendRequestForAuthorization(Request $request)
    {
        $request->session()->put('state', $state = Str::random(40));

        $query = http_build_query([
            'redirect_uri' => 'http://micliente.dominio.dev/callback',
            'state' => $state,
            'response_type' => 'confidential'
        ]);

        return redirect('http://auth.dominio.dev/grant-access?' . $query);
    }
```

#### CAMBIAR CODIGO DE AUTHORIZACION POR TOKENS JWT
El server de authorizacion generará una respuesta con un codigo redireccionando a la URI proporcionada en el primer paso en **redirect_uri**
- El siguiente codigo esta en laravel con php, la funcion le debes asignar a la URI que colocaste en **redirect_uri** cuando solicitaste el codigo por ejemplo en  **http://micliente.dominio.dev/callback**
- **Datos que devulve la peticion**
  - **token_type**: Bearer
  - **expires_in**: tiempo en segundo de expiracion
  - **access_token**: token jwt el cual debe ser usado para realizar peticiones en el header Authorization
  - **refresh_token**: token que permite renovar el access_token caducado, debe almacenarce en un ligar seguro 
  - **X-CSRF-REFRESH**: Header que debe ser enviado junto con el refresh_token en los headers, el X-CSRF-REFRESH es de un solo uso, implementacion adicional de seguridad
```
/**
  * realiza una solicitud para intercambiar el codigo con credenciales
  * @param \Illuminate\Http\Request $request
  * @return response(null, 201)
  */
 public function changeCodeForAuthorization(Request $request)
    {
        /**
         * obteniedo data
         */
        $data = explode('?', $request->state);

        /**
         * csrf token enviado desde el cliente
         */
        $transport_state = $data[0];

        /**
         * identificador unico del cliente
         */
        $client_id = str_replace('id=', '', $data[1]);

        /**
         * secret del client hasheado
         */
        $client_secret = str_replace('secret=', '', $data[2]);

        /**
         * token temporal valido por 10 Segundos despues de ese tiempo
         * la peticion va ser invalida
         */
        $csrf = str_replace('csrf=', '', $data[3]);

        /**
         * session actual
         */
        $state = $request->session()->pull('state');

        /**
         * verificacion de la sesion enviada con la actual
         */
        throw_unless(
            strlen($state) > 0 && $state === $transport_state,
            InvalidArgumentException::class,
            'Invalid state value.'
        );

        /**
         * intercambiando codigo por credenciales para la autorizacion
         */
        $response = Http::withHeaders([
            'X-CSRF-TOKEN' => $csrf,
        ])->asForm()->post('http://auth.dominio.dev/api/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'redirect_uri' => env('APP_URL'),
            'code' => $request->code,
        ]);

        /**
         * recibiras las credenciales, en este punto eres libre de manipular el codigo 
         * para automatizar el manejo de las credenciales
         */
        return $response->json();

    }
```
#### RONOVAR TOKEN CON EL REFESH_TOKEN (valido para code y confidentials)
Este proceso se usa para renovar el token caducado, todos los datos o variables usadas aqui ya se obtubieron en la peticion cuando generaste el codigo, por lo que debieron ser almacenas en un logar seguro.
- Parametros
  - X-CSRF-REFRESH: requerido, retornado en la cabecera de las peticiones
  - grant_type: requerido 
  - client_id: requerido
  - client_secret: solo para response type **confidential**
  - refresh_token: requerido

``` 
public function refreshToken(){

    $response = Http::withHeaders([
        'X-CSRF-REFRESH' => $csrf,
    ])->asForm()->post('auth.dominio.dev/api/oauth/token', [
        'grant_type' => 'refresh_token', //esto queda asi
        'client_id' => $client_id, // identificador del cliente
        'client_secret' => $client_secret, // 
        'refresh_token' => 'refresh_token', // obtenido en la peticion anterior
    ]);
    
    /**
     * genera lo mismo de la peticion de arriba
     */
    return $response->json();
}

```
## RESPONSE TYPE CODE (PKCE)
Este metodo se usa mayormente para entornos donde el cliente no es seguros para generar un cliente de este tipo puede escribir en la terminal la siguiente instruncion `php artisan passport:client --public`, puedes leer en [Laravel Passport](https://laravel.com/docs/10.x/passport#code-grant-pkce) acerca de este metodo, funciona igual pero con algunas mejoras que se incorporaron.

#### SOLICITAR CODIGO
Este metdodo debe ser incorporado en una URI en la aplicacion cliente que el desarrollador quiere implementar.
- Parametros
  - **client_id**: indentificador del cliente
  - **redirect_uri**: URI donde redireccionará cuando genere el codigo
  - **response_type**: tipo de respuesta 
  - **state**: stado del la aplicacion, verifica la autenticidad del sitio
  - **code_challenge**: codigo de desafio para generar el codigo
  - **code_challenge_method**: metodo del codigo de desafio

```
public function pkce(Request $request)
    {
        /**
         * creamos una session
         */
        $request->session()->put('state', $state = Str::random(40));

        /**
         * creamos una propiedad que contendra el code_verifier, el cual
         * se intercambiara con el server de authorizacion
         */
        $request->session()->put(
            'code_verifier', $code_verifier = Str::random(128)
        );

        /**
         * ahora creamos un code challenge
         */
        $codeChallenge = strtr(rtrim(
            base64_encode(hash('sha256', $code_verifier, true))
            , '='), '+/', '-_');

        $query = http_build_query([
            'client_id' => 'client_id',
            'redirect_uri' => 'http://micliente.dominio.dev/callback',
            'response_type' => 'code',
            'state' => $state,
            'code_challenge' => $codeChallenge,
            'code_challenge_method' => 'S256',
        ]);

        return redirect('http://auth.dominio.dev/grant-access?' . $query);

    }
```

#### INTERCAMBIAR CODIGO POR CRDENCIALES
Se intercambia el codigo para obtener crdenciales vbalidas, este metodo que se proporciona debera ser empleadp en la URI que se porporciono en **redirect_uri**

- **Datos que devulve la peticion**
  - **token_type**: Bearer
  - **expires_in**: tiempo en segundo de expiracion
  - **access_token**: token jwt el cual debe ser usado para realizar peticiones en el header Authorization
  - **refresh_token**: token que permite renovar el access_token caducado, debe almacenarce en un ligar seguro 
  - **X-CSRF-REFRESH**: Header que debe ser enviado junto con el refresh_token en los headers, el X-CSRF-REFRESH es de un solo uso, implementacion adicional de seguridad
```
public function callback(Request $request)
    {
        /**
         * get paramas
         */
        $data = explode('?', $request->state);
        /**
         * csrf token enviado desde el cliente
         */
        $transport_state = $data[0];
        //echo " estate $transport_state<br>";
        /**
         * identificador unico del cliente
         */
        $client_id = str_replace('id=', '', $data[1]);
        //echo "client_id $client_id <br>";
        /**
         * token temporal valido por 10 Segundos despues de ese tiempo
         * la peticion va ser invalida
         */
        $csrf = str_replace('csrf=', '', $data[2]);
        //echo "csrf $csrf <br>";

        $state = $request->session()->pull('state');

        $codeVerifier = $request->session()->pull('code_verifier');
        //echo "codeVerifier $codeVerifier <br>";
        // echo "code $request->code <br>";

        throw_unless(
            strlen($state) > 0 && $state === $transport_state,
            InvalidArgumentException::class
        );

        $response = Http::withHeaders([
            'X-CSRF-TOKEN' => $csrf,
        ])
            ->asForm()->post('http://auth.dominio.dev/api/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => $client_id,
            'redirect_uri' => 'http://micliente.dominio.dev/callback',
            'code_verifier' => $codeVerifier,
            'code' => $request->code,
        ]);

        return $response->json();

```
## Client credential
Este grant type suele se empleado en entornos controlados donde no represente un peligo al server de authorizacion o al cliente que este en uso, a diferencia de los demas este tipo de credencial no realiza intercambio de datos con el server de authorizacion y se debe a la forma de empleo que este tiene, por ejemplo puede emplearse para que un usuario lee informacion publica, o genenere reservas en un sistema de aerolineas, etc. 
- para mas informacion puedes leer en [Laravel Passport](https://laravel.com/docs/10.x/passport#client-credentials-grant-tokens) su funcionamiento es el mismo y no ha sido modificado.
  
```
use Illuminate\Support\Facades\Http;
 
$response = Http::asForm()->post('http://passport-app.test/oauth/token', [
    'grant_type' => 'client_credentials',
    'client_id' => 'client-id',
    'client_secret' => 'client-secret',
    'scope' => 'your-scope',
]);
 
return $response->json()['access_token'];
```