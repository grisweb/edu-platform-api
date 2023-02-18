<?php

namespace App\Services;

use App\Models\User;
use Exception;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MsGraphService
{
    const MS_GRAPH_BASE_PATH = 'https://graph.microsoft.com/v1.0';

    protected ?User $user;

    protected ?string $msToken;

    protected PendingRequest $http;

    /**
     * @param  Request  $request
     */
    public function __construct(Request $request)
    {
        $msToken = $request->bearerToken();
        $this->msToken = $msToken;

        $this->http = Http::baseUrl(self::MS_GRAPH_BASE_PATH)->acceptJson()->withHeaders([
            'Authorization' => "Bearer ${msToken}"
        ]);

        $response = $this->http->get('/me');

        if ($response->status() === 200) {
            $user = User::where(['ms_id' => $response['id']])->first();
            $this->user = $user;
        } else {
            $this->user = null;
        }
    }

    public function getUser(): User|null
    {
        return $this->user;
    }

    /**
     * @throws Exception
     */
    public function findUsers(string $query)
    {
        $response = $this->http->withHeaders([
            'ConsistencyLevel' => 'eventual'
        ])->get('/users', [
            '$search' => '"displayName:'.$query.'" OR "mail:'.$query.'"'
        ]);

        if ($response->status() !== 200) {
            throw new HttpClientException('Error find', $response->status());
        }

        return $response['value'];
    }

    /**
     * @param  string  $id
     * @return PromiseInterface|Response
     * @throws HttpClientException
     */
    public function getUserById(string $id): PromiseInterface|Response
    {
        $response = $this->http->get('/users/'.$id);

        if ($response->status() !== 200) {
            throw new HttpClientException('Error find user by id!', $response->status());
        }

        return $response;
    }
}
