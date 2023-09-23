<?php

namespace App\Http\Controllers;

use App\Http\Resources\ResponseResource;
use App\Models\User;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $rows = User::orderBy('names', 'asc');
            return response()->json([
                "data" => $rows->get(),
                "date" => new DateTime()
            ]);
        } catch (Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, User::rules());
        try {
            DB::beginTransaction();
            $data = $request->all();
            $model = new User();
            $model->names = $data['names'];
            $model->username = $data['username'];
            $model->password = $data['password'];
            $model->rol = $data['rol'];
            $model->save();
            DB::commit();
            return response()->json([
                "data" => $data,
                "date" => new DateTime()
            ]);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json(['error' => $ex->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $row = User::find($id);
            if (empty($row)) {
                throw new Exception('The selected cargo is invalid.', 404);
            }
            return response()->json([
                "data" => $row,
                "date" => new DateTime()
            ]);
        } catch (Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, User::rules($id));
        try {
            DB::beginTransaction();
            $data = $request->all();
            $row = User::find($id);
            if (empty($row)) {
                throw new Exception('The selected  Cargo is invalid.', 404);
            }
            $row->update($data);
            DB::commit();

            return response()->json([
                "data" => $row,
                "date" => new DateTime()
            ]);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json(['error' => $ex->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $row = User::find($id);
            if (empty($row)) {
                throw new Exception('The selected Cargo is invalid.', 404);
            }
            $row->delete();
            DB::commit();

            return response()->json([
                "data" => $row,
                "date" => new DateTime()
            ]);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json(['error' => $ex->getMessage()], 400);
        }
    }
}
