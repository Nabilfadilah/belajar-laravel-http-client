<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class HttpTest extends TestCase
{
    // get
    public function testGet()
    {
        $response = Http::get("https://enhmm1ik062ud.x.pipedream.net");
        self::assertTrue($response->ok());
    }

    // post
    public function testPost()
    {
        $response = Http::post("https://enhmm1ik062ud.x.pipedream.net");
        self::assertTrue($response->ok());
    }

    // delete
    public function testDelete()
    {
        $response = Http::delete("https://enhmm1ik062ud.x.pipedream.net");
        self::assertTrue($response->ok());
    }

    // response
    public function testResponse()
    {
        $response = Http::get("https://enhmm1ik062ud.x.pipedream.net");

        // response status
        self::assertEquals(200, $response->status());

        // response header
        self::assertNotNull($response->headers());

        // response body
        self::assertNotNull($response->body());

        // json response
        $json = $response->json();
        self::assertTrue($json['success']);
    }

    // query parameter
    public function testQueryParameter()
    {
        // withQueryParameters = query parameter
        $response = Http::withQueryParameters([
            // parameter page dan limit
            'page' => 1,
            'limit' => 10,
        ])->get("https://enhmm1ik062ud.x.pipedream.net");
        self::assertTrue($response->ok());
    }

    // header
    public function testHeader()
    {
        $response = Http::withQueryParameters([
            'page' => 1,
            'limit' => 10,
            // withHeaders = header, dan isinya array 
        ])->withHeaders([
            // array data acceept dan x-request
            'Accept' => 'application/json',
            'X-Request-ID' => '123123123',
        ])->get("https://enhmm1ik062ud.x.pipedream.net");
        self::assertTrue($response->ok());
    }

    // Cookie
    public function testCookie()
    {
        $response = Http::withQueryParameters([
            'page' => 1,
            'limit' => 10,
        ])->withHeaders([
            'Accept' => 'application/json',
            'X-Request-ID' => '123123123',
            // withCookies(array, domain) = menambah cookie request
        ])->withCookies([
            // misal session, userId
            "SessionId" => "3242432423",
            "UserId" => "eko",
        ], "enhmm1ik062ud.x.pipedream.net")->get("https://enhmm1ik062ud.x.pipedream.net");
        self::assertTrue($response->ok());
    }

    // Form post
    public function testFormPost()
    {
        // asForm() = mengirim request dalam bentuk form request 
        $response = Http::asForm()
            ->post( // post(url, form), lalu data dikirim ketikan gunakan function ini
                "https://enhmm1ik062ud.x.pipedream.net",
                [
                    // kirim data nya, key value
                    "username" => "admin",
                    "password" => "admin"
                ]
            );
        self::assertTrue($response->ok());
    }

    // Multipart
    public function testMultipart()
    {
        // asMultipart(), mengirim http jenis multipart seperti file
        $response = Http::asMultipart()
            // attach(key, content, name), untuk mengirim file 
            ->attach("profile", file_get_contents(__DIR__ . '/HttpTest.php'), "profile.jpg")
            // post(url, form), dan untuk buka file, bisa gunakan form post
            ->post("https://enhmm1ik062ud.x.pipedream.net", [
                "username" => "admin",
                "password" => "admin"
            ]);
        self::assertTrue($response->ok());
    }

    // JSON
    public function testJSON()
    {
        // asJson(), mengirim request dalam bentuk JSON 
        $response = Http::asJson()
            // data json bisa dikirim di parameter body milik post(url, body), put(url, body) atau patch(url, body)
            ->post("https://enhmm1ik062ud.x.pipedream.net", [
                "username" => "admin",
                "password" => "admin"
            ]);
        self::assertTrue($response->ok());
        $response->throw();
    }

    // Timeout
    public function testTimeout()
    {
        // timeout(second), untuk menentukan berapa lama waktu timeout nya
        $response = Http::timeout(1)->asJson()
            ->post("https://enhmm1ik062ud.x.pipedream.net", [
                "username" => "admin",
                "password" => "admin"
            ]);
        self::assertTrue($response->ok());
    }

    // public function testRetry()
    // {
    //     $response = Http::timeout(1)->retry(5, 1000)->asJson()
    //         ->post("https://enhmm1ik062ud.x.pipedream.net", [
    //             "username" => "admin",
    //             "password" => "admin"
    //         ]);
    //     self::assertTrue($response->ok());
    // }

    // public function testThrowError()
    // {
    //     $this->assertThrows(function () {
    //         $response = Http::get("https://www.programmerzamannow.com/not-found");
    //         self::assertEquals(404, $response->status());
    //         $response->throw();
    //     }, RequestException::class);
    // }
}
