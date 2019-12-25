<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePhoto;
use App\Photo;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller{
    public function __construct()
    {
        $this->middleware('auth')->except([
            'index',
            'download'
        ]);
    }

    /**
     * @param StorePhoto $request
     * @return Response
     * @throws Exception
     */
    public function create(StorePhoto $request)
    {
        $extension = $request->photo->extension();

        $photo = new Photo();

        $photo->filename = $photo->id . '.' . $extension;

        Storage::disk('public')->putFileAs('', $request->photo, $photo->filename, 'public');

        DB::beginTransaction();

        try
        {
            Auth::user()->photos()->save($photo);
            DB::commit();
        } catch(Exception $exception)
        {
            DB::rollBack();
            Storage::disk('public')->delete($photo->filename);
            throw $extension;
        }

        return response($photo, 201);
    }

    public function index()
    {
        return Photo::with(['owner'])->orderBy(Photo::CREATED_AT, 'desc')->paginate();
    }

    public function download(Photo $photo)
    {
        if( ! Storage::disk('public')->exists($photo->filename))
        {
            abort(404);
        }

        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $photo->filename . '"',
        ];

        return response(Storage::disk('public')->get($photo->filename), 200, $headers);
    }
}
