<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    private function isApiOrJson($request)
    {
        return $request->segment(1) === 'api' || $request->ajax() || $request->wantsJson();
    }

    /**
     * Determine if the application is in debug mode.
     *
     * @return Boolean
     */
    public function isDebugMode()
    {
        return config('app.debug');
        //return (boolean)env('APP_DEBUG');
    }


    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {

        /*
         * APP-MOD Modificado para retornar errors em JSON
         */
        //se for api ou json
        if ($this->isApiOrJson($request)) {

            $response = [
                'code'  => Response::HTTP_BAD_REQUEST,//erro default
                'message' => (string)$e->getMessage()
            ];

            //se for http temos um codigo podendo ser adquirido usando getStatusCode()
            if ($e instanceof HttpException) {
                $response['code'] = $e->getStatusCode();
                $response['message'] = Response::$statusTexts[ $e->getStatusCode() ];

                //se nao for http
            } else {

                if ($e instanceof FileNotFoundException) {
                    //'message' => 'Resource not found',
                    $response['code'] = Response::HTTP_NOT_FOUND;
                    $response['message'] = Response::$statusTexts[ Response::HTTP_NOT_FOUND ];

                } elseif ($e instanceof ModelNotFoundException) {
                    dd('asd');
                    $response['code'] = Response::HTTP_NOT_FOUND;
                    $response['message'] = Response::$statusTexts[ Response::HTTP_NOT_FOUND ];
                }

            }

            if ($this->isDebugMode()) {
                $response['debug'] = [
                    'exception' => get_class($e),
                    'trace'     => $e->getTrace()
                ];
            }

            return response()->json($response, $response['code']);
            //return response()->json(['error' => $response], $response['status']);
        }


        //Defender Erros, acontece a qualquer momento
        if ($e instanceof ForbiddenException) {

            Auth::logout();
            if ($this->isApiOrJson($request)) {
                return response('Unauthorized.', 401);//401
            } else {
                flash()->error('Você não tem acesso a esta área!');

                //return redirect()->route('auth.login');
                return redirect('auth/login');
            }
        }


        //Se não for debug os erros precisam ser tratados
        if (!$this->isDebugMode()) {
            //Erros HTTP precisam ser tratados 1 por 1
            if ($this->isHttpException($e)) {
                switch ($e->getStatusCode()) {

                    case 404:// not found
                    case 403:// sem autorização
                    case 503:// servidor indisponivel
                        return parent::render($request, $e);
                        break;
                    default:
                        return redirect()->guest('home');
                        break;
                }
            } else {
                if ($e instanceof FileNotFoundException) {
                    return response()->view('errors.404', array(), 404);
                    //$e = new NotFoundHttpException($e->getMessage(), $e); //aqui precisa do render;
                }
                //FindOrFail mostra página 404 em vez do erro
                if ($e instanceof ModelNotFoundException) {
                    return response()->view('errors.404', array(), 404);
                    //$e = new NotFoundHttpException($e->getMessage(), $e); //aqui precisa do render;
                }

                return redirect()->guest('home');
            }
            //Algo deu errado
            /*if (!$this->isHttpException($e)) {
            //existe tambem varios erros http não tratados que seriam enviados para frase whoops
            //em vez de tratar cada uma enviando para 500, em mando todos para 500 ou no caso home
                $e = new HttpException(500); //mostra página 500 em vez da frase
            }*/
        }


        return parent::render($request, $e);

        //Não é mais necessario na nova versao do Laravel
        /*if ($this->isHttpException($e)) {
            return $this->renderHttpException($e);
        } else {
            return parent::render($request, $e);
        }*/
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest('login');
    }
}
