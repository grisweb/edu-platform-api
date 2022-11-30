<?php

namespace App\Services;

use App\Models\User;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Microsoft\Graph\Exception\GraphException;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;

class MsGraphService
{
    protected ?User $user;

    protected Graph $graph;

    protected Request $request;

    /**
     * @throws GuzzleException
     * @throws GraphException
     */
    public function __construct(Request $request)
    {
//        $this->graph = (new Graph())->setAccessToken($request->bearerToken());
//
//        $msUser = $this->graph
//            ->createRequest('GET', '/me')
//            ->setReturnType(Model\User::class)
//            ->execute();

        $msToken = $request->bearerToken();

        $response = Http::acceptJson()->withHeaders([
            'Authorization' => "Bearer ${msToken}"
        ])->get('https://graph.microsoft.com/v1.0/me');

        if ($response->status() === 200) {
            $user = User::where(['ms_id' => $response['id']])->first();
            $this->user = $user;
        } else {
            $this->user = null;
        }
    }

    public function getUser()
    {
        return $this->user;
    }
}
