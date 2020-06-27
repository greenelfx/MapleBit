<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Vinkla\Hashids\Facades\Hashids;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  string $category
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/articles/list/{category?}",
     *     tags={"articles"},
     *     summary="Gets a paginated view of articles of optional category (omitting results in all articles)",
     *     operationId="list",
     *     @OA\Parameter(
     *         name="category",
     *         description="optionally specify category to return",
     *         in="path",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="paginated list of articles"
     *     ),
     * )
     */
    public function list($category = null)
    {
        $query = Article::orderBy('created_at');
        if (! empty($category)) {
            $query->where('category', $category);
        }

        return $query->paginate(15);
    }

    /**
     * Store an article in the database. Requires admin or moderator role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @OA\Post(
     *     path="/articles/store",
     *     tags={"articles"},
     *     summary="Store an article in the database. Requires admin or moderator role.",
     *     operationId="store",
     *     @OA\RequestBody(
     *      required=true,
     *         @OA\MediaType(
     *          mediaType="application/x-www-form-urlencoded",
     *          @OA\Schema(
     *              type="object",
     *              required={"title", "content", "category"},
     *              @OA\Property(
     *                  property="title",
     *                  description="article title",
     *                  type="string",
     *              ),
     *              @OA\Property(
     *                  property="content",
     *                  description="article content",
     *                  type="string"
     *              ),
     *              @OA\Property(
     *                  property="category",
     *                  description="article category",
     *                  type="string"
     *              ),
     *              @OA\Property(
     *                  property="slug",
     *                  description="article slug",
     *                  type="string"
     *              ),
     *              @OA\Property(
     *                  property="locked",
     *                  description="if article can be commented on",
     *                  type="boolean"
     *              ),
     *          )
     *       )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Article creation was successful with article data"
     *     ),
     *     security={
     *         {"bearer": {}}
     *     }
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'content' => 'required',
            'category' => 'required|string|alpha_dash',
            'slug' => 'string|alpha_dash|unique:App\Models\Article,slug',
            'locked' => 'boolean',
        ]);

        if ($validator->fails()) {
            return ['status' => 'validation', 'errors' => $validator->errors()];
        }

        $article_data = $validator->valid();

        if (! $request->filled('slug')) {
            // a hack to generate a unique slug if none provided
            $article_data['slug'] = Str::slug($request['title']).'-'.Hashids::encode(Carbon::now()->timestamp);
        }

        $article = Article::create($article_data);

        return ['status' => 'success', 'article' => $article];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/articles/view/{article}",
     *     tags={"articles"},
     *     summary="Gets specified article slug or 404",
     *     operationId="show",
     *     @OA\Parameter(
     *         name="article",
     *         description="article slug",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="article title, content, category, slug, timestamps"
     *     ),
     * )
     */
    public function show(Article $article)
    {
        return $article;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     *
     * @OA\Put(
     *     path="/articles/update/{article}",
     *     tags={"articles"},
     *     summary="Updates the specified article. Requires admin or moderator role.",
     *     operationId="update",
     *     @OA\Parameter(
     *         name="article",
     *         description="article slug",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *      required=true,
     *         @OA\MediaType(
     *          mediaType="application/x-www-form-urlencoded",
     *          @OA\Schema(
     *              type="object",
     *              required={"title", "content", "category"},
     *              @OA\Property(
     *                  property="title",
     *                  description="article title",
     *                  type="string",
     *              ),
     *              @OA\Property(
     *                  property="content",
     *                  description="article content",
     *                  type="string"
     *              ),
     *              @OA\Property(
     *                  property="category",
     *                  description="article category",
     *                  type="string"
     *              ),
     *              @OA\Property(
     *                  property="slug",
     *                  description="article slug",
     *                  type="string"
     *              ),
     *              @OA\Property(
     *                  property="locked",
     *                  description="if article can be commented on",
     *                  type="boolean"
     *              ),
     *          )
     *       )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Article update was successful with updated article data"
     *     ),
     *     security={
     *         {"bearer": {}}
     *     }
     * )
     */
    public function update(Request $request, Article $article)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'string',
            'content' => 'string',
            'category' => 'string|alpha_dash',
            'slug' => 'string|alpha_dash|unique:App\Models\Article,slug,'.$article->slug,
            'locked' => 'boolean',
        ]);

        if ($validator->fails()) {
            return ['status' => 'validation', 'errors' => $validator->errors()];
        }

        $article->fill($validator->valid())->save();

        return ['status' => 'success', 'article' => $article];
    }

    /**
     * Deletes the specified article.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     *
     * @OA\Delete(
     *     path="/articles/delete/{article}",
     *     tags={"articles"},
     *     summary="Deletes the specified article. Requires admin or moderator role.",
     *     operationId="delete",
     *     @OA\Parameter(
     *         name="article",
     *         description="article slug",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Article delete was successful."
     *     ),
     *     security={
     *         {"bearer": {}}
     *     }
     * )
     */
    public function destroy(Article $article)
    {
        $article->delete();

        return ['status' => 'success'];
    }
}
