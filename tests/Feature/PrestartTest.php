<?php

namespace Tests\Feature;
use Tests\TestCase;

class PrestartTest extends TestCase
{
    public function testGetServerInfo()
    {
        $this->get('/api/serverInfo')->assertJsonStructure([
            'status',
            'data' => [
                'server_data'
            ],
        ]);
    }    
}
