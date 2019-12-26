<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Requests\StoreComment;
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
            'download',
            'show'
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
        return Photo::with([
            'owner',
            'likes'
        ])->orderBy(Photo::CREATED_AT, 'desc')->paginate();
    }

    public function download(Photo $photo)
    {
        if( ! Storage::disk('public')->exists($photo->filename))
        {
            abort(404);
        }

        $headers = [
            'Content-Type'        => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $photo->filename . '"',
        ];

        return response(Storage::disk('public')->get($photo->filename), 200, $headers);
    }

    /**
     * @param string $id
     */
    public function show(string $id)
    {
        $photo = Photo::where('id', $id)->with([
            'owner',
            'comments.author',
            'likes',
        ])->first();
        return $photo ?? abort(404);
    }

    /**
     * @param Photo $photo
     * @param StoreComment $request
     * @return Response
     */
    public function addComment(Photo $photo, StoreComment $request)
    {
        $comment = new Comment();
        $comment->content = $request->get('content');
        $comment->user_id = Auth::user()->id;
        $photo->comments()->save($comment);

        $new_comment = Comment::where('id', $comment->id)->with('author')->first();
        return response($new_comment, 201);
    }

    /**
     * @param string $id
     * @return array
     */
    public function like(string $id)
    {
        $photo = Photo::where('id', $id)->with('likes')->first();

        if( ! $photo)
        {
            abort(404);
        }

        $photo->likes()->detach(Auth::user()->id);
        $photo->likes()->attach(Auth::user()->id);

        return ['photo_id' => $id];
    }

    public function unlike(string $id)
    {
        $photo = Photo::where('id', $id)->with('likes')->first();

        if( ! $photo)
        {
            abort(404);
        }

        $photo->likes()->detach(Auth::user()->id);

        return ['photo_id' => $id];
    }
}
