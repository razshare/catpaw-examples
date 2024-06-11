<?php
use function Amp\File\getSize;
use function Amp\File\isFile;
use Amp\Http\Server\Request;
use Amp\Http\Server\Response;
use function CatPaw\Core\asFileName;

use CatPaw\Core\Container;

use CatPaw\Core\File;
use CatPaw\Web\HttpStatus;
use CatPaw\Web\Interfaces\FileServerInterface;
use CatPaw\Web\Interfaces\ServerInterface;

use const CatPaw\Web\INTERNAL_SERVER_ERROR;
use CatPaw\Web\Mime;
use const CatPaw\Web\NOT_FOUND;

class MyCustomFileServer implements FileServerInterface {
    public static function create(ServerInterface $server) {
        return new self($server);
    }
    private function __construct(private ServerInterface $server) {
    }

    public function serve(Request $request): Response {
        $path     = urldecode($request->getUri()->getPath());
        $fileName = $this->server->getStaticsLocation().$path;
        
        if (!isFile($fileName)) {
            return new Response(status: NOT_FOUND, body:"File $path not found.");
        }

        $file = File::open($fileName, 'r')->unwrap($error);

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

function main(ServerInterface $server) {
    Container::provide(FileServerInterface::class, MyCustomFileServer::create($server));
    return $server->withStaticsLocation(asFileName(__DIR__, '../../../public'))->start();
}
