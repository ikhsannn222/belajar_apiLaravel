<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Actor;
use Illuminate\Http\Request;
use Validator;

class ActorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $actor = Actor::latest()->get();
        $response = [
            'succes'=>true,
            'message'=>'Data actor',
            'data'=> $actor,
        ];
        return response()->json($response, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate data
        $validator = Validator::make($request->all(), [
            'nama_actor'=>'required|unique:actors',
            'biodata'=>'required',
        ],[
            'nama_actor.required'=>'Masukan nama',
            'nama_actor.unique'=>'nama Sudah digunakan',
            'biodata.required'=>'Masukan biodata',
        ]);

        if($validator->fails()){
            return response()->json([
                'succes'=>false,
                'message'=> 'Silahkan isi dengan benar',
                'data'=> $validator->errors(),
            ], 400);
        } else {
            $actor = new Actor;
            $actor->nama_actor = $request->nama_actor;
            $actor->biodata = $request->biodata;
            $actor->save();
        }

        if($actor) {
            return response()->json([
                'succes'=>true,
                'message'=> 'data berhasil disimpan',
            ], 201);
        } else {
            return response()->json([
                'succes'=>false,
                'message'=>'data gagal disimpan',
            ], 400);
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
        $actor = Actor::find($id);

        if($actor){
            return response()->json([
                'succes'=>true,
                'message'=> 'Detail actor',
                'data'=> $actor,
            ], 200);
        } else {
            return response()->json([
                'succes'=>false,
                'message'=> 'Actor Tidak Di temukan',
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
         // validate data
         $validator = Validator::make($request->all(), [
            'nama_actor'=>'required',
            'biodata'=>'required',
        ],[
            'nama_actor.required'=>'Masukan nama',
            'biodata'=>'Masukan biodata',
        ]);

        if($validator->fails()){
            return response()->json([
                'succes'=>false,
                'message'=> 'Silahkan isi dengan benar',
                'data'=> $validator->errors(),
            ], 401);
        } else {
            $actor = Actor::find($id);
            $actor->nama_actor = $request->nama_actor;
            $actor->biodata = $request->biodata;
            $actor->save();
        }

        if($actor) {
            return response()->json([
                'succes'=>true,
                'message'=> 'data berhasil diperbarui',
            ], 200);
        } else {
            return response()->json([
                'succes'=>false,
                'message'=>'data gagal diperbarui',
            ], 401);
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
        $actor = Actor::find($id);
        if($actor){
        $actor->delete();
        return response()->json([
            'succes'=>true,
            'message'=>'data ' . $actor->nama_actor . ' dan '. $actor->biodata . ' berhasil hapus',
        ], 200);

        } else {
            return response()->json([
            'succes'=>false,
            'message'=>'data tidak di temukan'
            ], 404);
        }

    }
}
