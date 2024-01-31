<?php
use function Amp\File\getSize;
use function Amp\File\isFile;
use Amp\Http\Server\Request;
use Amp\Http\Server\Response;
use function CatPaw\Core\anyError;
use CatPaw\Core\File;
use CatPaw\Web\HttpStatus;
use CatPaw\Web\Interfaces\FileServerInterface;
use const CatPaw\Web\INTERNAL_SERVER_ERROR;
use CatPaw\Web\Mime;
use const CatPaw\Web\NOT_FOUND;
use CatPaw\Web\Server;

class MyCustomFileServer implements FileServerInterface {
    public static function create(Server $server) {
        return new self($server);
    }
    private function __construct(private Server $server) {
    }

    public function serve(Request $request): Response {
        $path     = urldecode($request->getUri()->getPath());
        $fileName = $this->server->www.$path;
        
        if (!isFile($fileName)) {
            return new Response(status: NOT_FOUND, body:"File $path not found.");
        }

        $file = File::open($fileName, 'r')->try($error);

        if ($error) {
            return new Response(status: INTERNAL_SERVER_ERROR, body:$error->getMessage());
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
    return anyError(function() {
        $server = Server::create(www:'./public')->try($error)
        or yield $error;

        $server->setFileServer(MyCustomFileServer::create($server));

        $server->start()->await()->try($error)
        or yield $error;
    });
}
