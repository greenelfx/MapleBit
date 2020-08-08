<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Rules\Country;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProfileController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @OA\Post(
     *     path="/user/profile/store",
     *     tags={"profile"},
     *     summary="Update the authenticated user's profile.",
     *     operationId="store",
     *     @OA\RequestBody(
     *      required=true,
     *         @OA\MediaType(
     *          mediaType="application/x-www-form-urlencoded",
     *          @OA\Schema(
     *              type="object",
     *              @OA\Property(
     *                  property="name",
     *                  description="unique profile name",
     *                  type="string",
     *              ),
     *              @OA\Property(
     *                  property="age",
     *                  description="age",
     *                  type="integer"
     *              ),
     *              @OA\Property(
     *                  property="country",
     *                  description="country",
     *                  type="string"
     *              ),
     *              @OA\Property(
     *                  property="motto",
     *                  description="motto",
     *                  type="string"
     *              ),
     *              @OA\Property(
     *                  property="about",
     *                  description="long form text about the user",
     *                  type="string"
     *              ),
     *          )
     *       )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Profile was successfully updated"
     *     ),
     *     security={
     *         {"bearer": {}}
     *     }
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:20|unique:profiles,name,'.$request->user()->profile->id,
            'age' => 'nullable|integer|min:0|max:100',
            'country' => ['nullable', 'string', new Country],
            'motto' => 'nullable|string|max:140',
            'about' => 'nullable|string|max:40000',
        ]);

        if ($validator->fails()) {
            return ['status' => 'validation', 'errors' => $validator->errors()];
        }

        $request->user()->profile->fill($validator->valid())->save();

        return [
            'status' => 'success',
            'profile' => $request->user()->profile,
        ];
    }

    /**
     * Get a specified profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/user/profile/view/{profile_name}",
     *     tags={"profile"},
     *     summary="gets profile by name",
     *     operationId="get",
     *     @OA\Parameter(
     *        name="profile_name", in="path",required=true, @OA\Schema(type="string")
     *     ), 
     *     @OA\Response(
     *         response=200,
     *         description="specified profile"
     *     ),
     *     security={
     *         {"bearer": {}}
     *     }
     * )
     */      
    public function get(Request $request)
    {
        try {
            $profile = Profile::where('name', $request->profile_name)->firstOrFail();
        } catch(Exception  $e) {
            abort(404, "profile does not exist");
        }

        return [
            'status' => 'success',
            'profile' => $profile,
        ];
    }
    
    /**
     * Get a listing of profiles with an optional search query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/user/profile/list/{profile_name}",
     *     tags={"profile"},
     *     summary="Gets a paginated view of profile names",
     *     operationId="list",
     *     @OA\Parameter(
     *        name="profile_name", in="path",required=false, @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="paginated list of profiles"
     *     ),
     *     security={
     *         {"bearer": {}}
     *     }
     * )
     */
    public function list(Request $request)
    {
        $PAGINATE_COUNT = 15;
        if($request->profile_name) {
            return Profile::where('name', 'like', '%'.$request->profile_name.'%')->paginate($PAGINATE_COUNT);
        }
        return Profile::paginate($PAGINATE_COUNT);
    }    
}
