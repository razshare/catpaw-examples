<?php
use function Amp\File\getSize;
use Amp\Http\HttpStatus;

use Amp\Http\Server\Request;
use Amp\Http\Server\Response;
use function CatPaw\Core\anyError;
use CatPaw\Core\File;
use CatPaw\Web\Interfaces\FileServerInterface;
use const CatPaw\Web\INTERNAL_SERVER_ERROR;
use CatPaw\Web\Mime;
use CatPaw\Web\Server;

use CatPaw\Web\Services\ByteRangeService;

class MyCustomByteRangeFileServer implements FileServerInterface {
    public static function create(Server $server, ByteRangeService $byteRangeService) {
        return new self($server, $byteRangeService);
    }
    private function __construct(private Server $server, private ByteRangeService $byteRangeService) {
    }

    public function serve(Request $request): Response {
        $server   = $this->server;
        $range    = $this->byteRangeService;
        $fileName = "{$server->www}/videoplayback.mp4";


        $response = $range->file(
            rangeQuery: $request->getHeader('range') ?? '',
            fileName: $fileName,
        )->try($error);

        if (!$error) {
            $response->setHeader("Content-Type", Mime::findContentType($fileName));
            return $response;
        }

        $file = File::open($fileName, 'r')->try($error);

        if ($error) {
            return new Response(status: INTERNAL_SERVER_ERROR, body: $error->getMessage());
        }

        return new Response(
            status: HttpStatus::OK,
            headers: [
                "Accept-Ranges"  => "bytes",
                "Content-Type"   => Mime::findContentType($fileName),
                "Content-Length" => getSize($fileName),
            ],
            body: $file->getAmpFile(),
        );
    }
}

function main(ByteRangeService $range) {
    return anyError(function() use ($range) {
        $server = Server::create(www: './public')->try($error)
        or yield $error;

        $server->setFileServer(fileServer: MyCustomByteRangeFileServer::create($server, $range));
        
        $server->start()->await()->try($error)
        or yield $error;
    });
}
