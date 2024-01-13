<?php

use function Amp\File\getSize;
use Amp\Http\Server\Request;
use Amp\Http\Server\Response;

use CatPaw\Core\File;
use function CatPaw\Core\stop;
use function CatPaw\Web\failure;

use CatPaw\Web\HttpStatus;
use CatPaw\Web\Interfaces\FileServerInterface;
use CatPaw\Web\Mime;
use CatPaw\Web\Server;

class MyCustomFileServer implements FileServerInterface {
    public static function create(Server $server) {
        return new self($server);
    }
    private function __construct(private Server $server) {
    }

    public function serve(Request $request): Response {
        $path     = urldecode($request->getUri()->getPath());
        $server   = $this->server;
        $fileName = $server->www.$path;

        $file = File::open($fileName, 'r')->try($error);

        if ($error) {
            return failure($error->getMessage());
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

function main() {
    $server = Server::create()->try($error) or stop($error);
    $server->setFileServer(MyCustomFileServer::create($server));
    $server->start()->await()->try($error) or stop($error);
}
