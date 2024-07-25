<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Validator;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kategori = Kategori::latest()->get();
        $response = [
            'succes'=>true,
            'message'=>'Data Kategori',
            'data'=> $kategori,
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
            'nama_kategori'=>'required|unique:kategoris',
        ],[
            'nama_kategori.required'=>'Masukan Kategori',
            'nama_kategori.unique'=>'Kategori Sudah digunakan',
        ]);

        if($validator->fails()){
            return response()->json([
                'succes'=>false,
                'message'=> 'Silahkan isi dengan benar',
                'data'=> $validator->errors(),
            ], 400);
        } else {
            $kategori = new Kategori;
            $kategori->nama_kategori = $request->nama_kategori;
            $kategori->save();
        }

        if($kategori) {
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
        $kategori = Kategori::find($id);

        if($kategori){
            return response()->json([
                'succes'=>true,
                'message'=> 'Detail Kategori',
                'data'=> $kategori,
            ], 200);
        } else {
            return response()->json([
                'succes'=>false,
                'message'=> 'Kategori Tidak Di temukan',
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
            'nama_kategori'=>'required',
        ],[
            'nama_kategori.required'=>'Masukan Kategori',
        ]);

        if($validator->fails()){
            return response()->json([
                'succes'=>false,
                'message'=> 'Silahkan isi dengan benar',
                'data'=> $validator->errors(),
            ], 401);
        } else {
            $kategori = Kategori::find($id);
            $kategori->nama_kategori = $request->nama_kategori;
            $kategori->save();
        }

        if($kategori) {
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
        $kategori = Kategori::find($id);
        if($kategori){
        $kategori->delete();
        return response()->json([
            'succes'=>true,
            'message'=>'data ' .$kategori->nama_kategori . ' Berhasil dihapus'
        ], 200);

        } else {
            return response()->json([
            'succes'=>false,
            'message'=>'data tidak di temukan'
            ], 404);
        }

    }
}
