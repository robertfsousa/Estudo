<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkRequest;
use App\Work;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;

class WorkController extends Controller
{


    /**
     * Retorna uma lista de atividades.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     *
     * @SWG\Get(
     *     path="/work",
     *     description="Retorna uma lista de atividades.",
     *     tags={"Atividades"},
     *
     *     @SWG\Response(response=200, description="successful operation",
     *         @SWG\Schema(
     *                 @SWG\Property(property="id", type="integer", description="Id da Atividade"),
     *                 @SWG\Property(property="name", type="string", description="Nome da Atividade"),
     *                 @SWG\Property(property="time", type="integer", description="Tempo da Atividade"),
     *                 @SWG\Property(property="obs", type="string", description="Observação"),
     *                 @SWG\Property(property="create_at", type="string", description="Data de Criação"),
     *                 @SWG\Property(property="uodate_at", type="string", description="Data de Atualização"),
     *        ),
     *     ),
     *
     *     @SWG\Response(response=404,description="Not Found",@SWG\Schema(ref="#/definitions/Error"))
     * )
     *
     */
    public function index()
    {
        return Work::all();
    }


    /**
     * Salva uma nova Atividade.
     *
     * @param WorkRequest|Request $request
     * @return Work
     *
     * @SWG\Post(
     *     path="/work",
     *     description="Salva uma nova Atividade.",
     *     tags={"Atividades"},
     *     @SWG\Parameter(name="name", in="formData", description="Nome da Atividade", required=true, type="string"),
     *     @SWG\Parameter(name="time", in="formData", description="Tempo da Atividade", required=true, type="integer"),
     *     @SWG\Parameter(name="obs", in="formData", description="Observações", required=false, type="string"),
     *     @SWG\Response(response=200, description="successful operation",
     *         @SWG\Schema(
     *                 @SWG\Property(property="id", type="integer", description="Id da Atividade"),
     *                 @SWG\Property(property="name", type="string", description="Nome da Atividade"),
     *                 @SWG\Property(property="time", type="integer", description="Tempo da Atividade"),
     *                 @SWG\Property(property="obs", type="string", description="Observação"),
     *                 @SWG\Property(property="create_at", type="string", description="Data de Criação"),
     *                 @SWG\Property(property="uodate_at", type="string", description="Data de Atualização"),
     *        ),
     *     ),
     *
     *     @SWG\Response(response=404,description="Not Found",@SWG\Schema(ref="#/definitions/Error"))
     * )
     *
     */
    public function store(Request $request)
    {

        return Work::create($request->all());
    }

    /**
     * Mostra uma Atividade
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     *
     *
     * @SWG\Get(
     *     path="/work/{id}",
     *     description="Mostra uma Atividade.",
     *     tags={"Atividades"},
     *     @SWG\Parameter(name="id", in="path", description="id da Atividade", required=true, type="integer"),
     *
     *     @SWG\Response(response=200, description="successful operation",
     *         @SWG\Schema(
     *                 @SWG\Property(property="id", type="integer", description="Id da Atividade"),
     *                 @SWG\Property(property="name", type="string", description="Nome da Atividade"),
     *                 @SWG\Property(property="time", type="integer", description="Tempo da Atividade"),
     *                 @SWG\Property(property="obs", type="string", description="Observação"),
     *                 @SWG\Property(property="create_at", type="string", description="Data de Criação"),
     *                 @SWG\Property(property="uodate_at", type="string", description="Data de Atualização"),
     *        ),
     *     ),
     *
     *     @SWG\Response(response=404,description="Not Found",@SWG\Schema(ref="#/definitions/Error"))
     * )
     *
     */
    public function show($id)
    {

       $work = Work::find($id);

       return $work ?: response()->json(['code'=>404,'message' => 'Not Found'], 404);
    }

    /**
     * Atualiza uma Atividade.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     *
     * @SWG\Put(
     *     path="/work/{id}",
     *     description="Atualiza uma Atividade.",
     *     tags={"Atividades"},
     *     @SWG\Parameter(name="id", in="path", description="id da Atividade", required=true, type="integer"),
     *
     *     @SWG\Parameter(name="name", in="formData", description="Nome da Atividade", required=true, type="string"),
     *     @SWG\Parameter(name="time", in="formData", description="Tempo da Atividade", required=true, type="integer"),
     *     @SWG\Parameter(name="obs", in="formData", description="Observações", required=false, type="string"),
     *     @SWG\Response(response=200, description="successful operation",
     *         @SWG\Schema(
     *                 @SWG\Property(property="id", type="integer", description="Id da Atividade"),
     *                 @SWG\Property(property="name", type="string", description="Nome da Atividade"),
     *                 @SWG\Property(property="time", type="integer", description="Tempo da Atividade"),
     *                 @SWG\Property(property="obs", type="string", description="Observação"),
     *                 @SWG\Property(property="create_at", type="string", description="Data de Criação"),
     *                 @SWG\Property(property="uodate_at", type="string", description="Data de Atualização"),
     *        ),
     *     ),
     *
     *     @SWG\Response(response=404,description="Not Found",@SWG\Schema(ref="#/definitions/Error"))
     * )
     *
     */
    public function update(Request $request, $id)
    {
        $work = Work::find($id);

        if (!$work) {
            response()->json(['code'=>404,'message' => 'Not Found'], 404);
        }


        $work->fill($request->all());

        if ($work->save()) {
            return $work;
        } else {
            return response()->json(['error' => 'Não foi possivel atualizar'], 404);

        }

    }

    /**
     * Remove uma Atividade.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     *
     * @SWG\Delete(
     *     path="/work/{id}",
     *     description="Remove uma Atividade.",
     *     tags={"Atividades"},
     *     @SWG\Parameter(name="id", in="path", description="id da Atividade", required=true, type="integer"),
     *
     *     @SWG\Response(response=200, description="successful operation"),
     *
     *     @SWG\Response(response=404,description="Not Found",@SWG\Schema(ref="#/definitions/Error"))
     * )
     *
     */
    public function destroy($id)
    {

        $work = Work::find($id);

        if (!$work) {
            return response()->json(['code'=>404,'message' => 'Not Found'], 404);
        }

        if ($work->delete()) {
            return response()->json('Deletado com Sucesso');
        } else {
            return response()->json(['error' => 'Não foi possivel deletar'], 404);
        }

    }

}
