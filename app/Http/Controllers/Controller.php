<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/*basePath="/api",*/
/*host="www.meupedido.online",*/
/**
 * @SWG\Swagger(
 *     basePath="/api",
 *     schemes={"http"},

 *     produces={"application/json"},
 *     consumes={"application/json"},
 *     @SWG\Info(
 *         version="1.0",
 *         title="Ciclos de Estudo",
 *         description="Gerenciar os Estudos Online, usando a tecnica em ciclos",
 *         @SWG\Contact(name="Robert Ferraz", url="https://plus.google.com/+RobertFerrazSousa")
 *     ),
 *     @SWG\Definition(
 *         definition="Error",
 *         required={"code", "message"},
 *         @SWG\Property(
 *             property="code",
 *             type="integer",
 *             format="int32"
 *         ),
 *         @SWG\Property(
 *             property="message",
 *             type="string"
 *         )
 *     )
 * )
 */

/**
 * @SWG\Tag(
 *   name="work",
 *   description="Tudo sobre as Atividades",
 * )
 *
 */


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;



}
