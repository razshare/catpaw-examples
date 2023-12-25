<?php


use function Amp\File\getSize;
use function Amp\File\openFile;
use Amp\Http\HttpStatus;
use Amp\Http\Server\Request;
use Amp\Http\Server\Response;

use CatPaw\Web\Attributes\Produces;

use CatPaw\Web\Exceptions\InvalidByteRangeQueryException;
use CatPaw\Web\Mime;
use CatPaw\Web\Server;
use CatPaw\Web\Services\ByteRangeService;

#[Produces("audio/mp4")]
function serve(
    Response $response,
    Request $request,
    ByteRangeService $range,
    Server $server
) {
    $fileName = "{$server->www}/videoplayback.mp4";
    try {
        $byteRangeResponse = $range->file(
            rangeQuery: $request->getHeader("range") ?? '',
            fileName: $fileName,
        );
        $response->setStatus($byteRangeResponse->getStatus());
        $response->setHeaders((array)$byteRangeResponse->getHeaders());
        $response->setBody($byteRangeResponse->getBody());
    } catch(InvalidByteRangeQueryException) {
        $fileSize = getSize($fileName);
        $response->setStatus(HttpStatus::OK);
        $response->setHeaders([
            "Accept-Ranges"  => "bytes",
            "Content-Type"   => Mime::findContentType($fileName),
            "Content-Length" => $fileSize,
        ]);
        $response->setBody(openFile($fileName, 'r'));
    }
}

function main() {
    $server = Server::create(www: "./public");
    $server->router->get(path: '/', callback: serve(...));
    $server->start();
}