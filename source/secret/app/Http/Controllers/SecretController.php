<?php
namespace App\Http\Controllers;

use App\Model\Secret;
use App\Transformers\SecretTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use Illuminate\Http\Request;


class SecretController extends Controller
{
    public function index(
        Manager $fractal,
        SecretTransformer $secretTransformer,
        Request $request
    ){
        $records = Secret::all();
        $collection = new Collection(
            $records,
            $secretTransformer
        );

        $data = $fractal->createData($collection)
            ->toArray();

        return response()->json($data);
    }

    public function get($id, SecretTransformer $secretTransformer)
    {
        $secret = Secret::find($id);
        $secretTransformed = $secretTransformer->transform($secret);
        return response()->json($secretTransformed);
    }

    public function create(Request $request)
    {
        $this->validate(
            $request,
            [
                'name' => 'required|string|unique:secrets,name',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'location_name' => 'required|string'
            ]
        );

        $secret = Secret::create($request->all());
        if($secret->save() === false)
        {
            // Manage Error
        }
    }
}
