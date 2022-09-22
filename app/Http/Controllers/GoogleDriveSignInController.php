<?php

namespace App\Http\Controllers;

use App\Actions\GoogleDriveCallbackAction;
use App\Actions\GoogleDriveSigninAction;
use App\Actions\RefreshGoogleDriveTokenAction;
use App\Models\GoogleDrive;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\MultipartStream;

class GoogleDriveSignInController extends Controller
{

    public function initiateSignin(Request $request)
    {
        return (new GoogleDriveSigninAction())->googleDriveSignin();
    }


    public function getCallbackDetails(Request $request)
    {
        return (new GoogleDriveCallbackAction())->googleDriveCallback($request->code);
    }

    public function getDriveResponseAuth(Request $request)
    {
        return $request;
    }

    public function refreshGoogleAccessToken()
    {
        return 'i am here';
        return (new RefreshGoogleDriveTokenAction());
    }

    public function sampleUpload(Request $request)
    {
        if ($request->file('item')) {

            $file = $request->file('item');
            $filename = uniqid() . $file->getClientOriginalName();
            $mimeType = $file->getClientMimeType();

            try {



                // $data = Http::withHeaders([
                //     "Content-Type" => "multipart/form-data",
                //     "Authorization" => "Bearer {$this->GoogleDrive->access_token}"
                // ])->attach('attachment',  $file->getRealPath(), $filename)->post('https://www.googleapis.com/upload/drive/v3/files?uploadType=media')->json();

                // return $data;
                // return $postBody;


                $handle = fopen($file->getRealPath(), 'rb');
                $fileSize = \File::size($file);
                $binary = fread($handle, $fileSize);
                // $binary = $file->openFile()->fread($file->getSize());
                $client = new Client([
                    'headers' => [
                        "Content-Type" => "multipart/form-data",
                        "Authorization" => "Bearer {$this->GoogleDrive->access_token}"
                    ]
                ]);

                $res = $client->request('POST', 'https://www.googleapis.com/upload/drive/v3/files?uploadType=media', [
                    // 'auth'      => [ env('API_USERNAME'), env('API_PASSWORD') ],
                    'multipart' => [
                        [
                            'name'     => 'FileContents',
                            'contents' => file_get_contents($binary),
                            'filename' => $filename
                        ],
                        // [
                        //     'name'     => 'FileInfo',
                        //     'contents' => json_encode($fileinfo)
                        // ]
                    ],
                ]);

                // $stream = \GuzzleHttp\Psr7\Utils::streamFor($postBody);


                echo   $res->getStatusCode();
                fclose($handle);
                return $res->getBody()->getContents();

                $response = Http::asForm()->withHeaders([
                    "Content-Type" => "multipart/form-data",
                    "Authorization" => "Bearer {$this->GoogleDrive->access_token}"
                ])->post('https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart', [
                    "" =>
                    json_encode(
                        [
                            "name" => $filename,
                            "parents" => array("12HhhBW3iksxvrhl5B6qEw_5p7Yyf10XW"),
                            "mimeType" => "*/*",
                        ]
                    ),
                    "" => [
                        "data" => $file->getRealPath(),
                        "mimeType" => $mimeType
                    ]
                ])->json();
                return $response;

                // $file->getRealPath();
                // $uploadUrl = json_decode($response->getBody()->getContents())->uploadUrl;
                // $binary = $file->openFile()->fread($file->getSize());

                $meta = [
                    "name" => $filename,
                    "parents" => array("12HhhBW3iksxvrhl5B6qEw_5p7Yyf10XW"),
                    "mimeType" => "application/vnd.google-apps.audio",
                ];

                // return json_encode($meta);
                // This is a multipart/related upload.
                $boundary = mt_rand();
                $boundary = str_replace('"', '', $boundary);
                $contentType = 'multipart/form-data; boundary=-----' . $boundary;
                $related = "-----$boundary\r\n";
                // $related .= "Content-Disposition: form-data; name=\"\"\r\n";
                $related .= "\r\n" . json_encode($meta) . "\r\n";
                $related .= "-----$boundary\r\n";
                // $related .= "Content-Disposition: form-data; name=\"\"; filename=\"$filename\"";
                // $related .= "Content-Type: $mimeType\r\n";
                $related .= "Content-Transfer-Encoding: base64\r\n";
                $related .= "\r\n" . base64_encode($file->getRealPath()) . "\r\n";
                $related .= "-----$boundary--";
                $postBody = $related;

                // return $postBody;
                $client = new Client([
                    'headers' => [
                        "Content-Type" => $contentType,
                        "Authorization" => "Bearer {$this->GoogleDrive->access_token}"
                    ]
                ]);

                $stream = \GuzzleHttp\Psr7\Utils::streamFor($postBody);
                $res = $client->request('POST', 'https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart', [
                    'form_params' => [
                        $postBody,
                    ]
                ]);

                echo   $res->getStatusCode();

                return $res->getBody()->getContents();

                $response = Http::withHeaders([
                    "Content-Type" => $contentType,
                    "Authorization" => "Bearer {$this->GoogleDrive->access_token}"
                ])->asForm()->post(
                    'https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart',
                    [
                        $stream

                    ]
                )->json();

                return $response;

                // Log::info($response = $response);
            } catch (Exception $e) {
                // Log::info("Upload Documents : " . $e->getMessage());
                return $e->getMessage();
            }
        }
    }

    public function driveList()
    {
        // MultipartStream
        $response = Http::withHeaders([
            // "Content-Type" => "application/json",
            "Authorization" => "Bearer {$this->GoogleDrive->access_token}"
        ])->acceptJson()->get("https://www.googleapis.com/drive/v3/files/12HhhBW3iksxvrhl5B6qEw_5p7Yyf10XW")->json();

        return $response;
    }



    // $response = Http::asForm()->withHeaders([
    //     "Content-Type" => "multipart/form-data",
    //     "Authorization" => "Bearer {$this->GoogleDrive->access_token}"
    // ])->post('https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart', [
    //     "metadata" =>
    //     json_encode(
    //         [
    //             "name" => $filename,
    //             "parents" => array("12HhhBW3iksxvrhl5B6qEw_5p7Yyf10XW"),
    //             "mimeType" => "application/vnd.google-apps.audio",
    //         ]
    //     ),
    //     "media" => [
    //         "data" => $file->getRealPath(),
    //         "mimeType" => $mimeType
    //     ]
    // ])->json();
    // return $response;
}
