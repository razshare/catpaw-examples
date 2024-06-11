<?php
use function Amp\File\getSize;
use Amp\Http\HttpStatus;
use Amp\Http\Server\Request;
use Amp\Http\Server\Response;
use function CatPaw\Core\anyError;
use function CatPaw\Core\asFileName;
use CatPaw\Core\Container;
use CatPaw\Core\File;
use CatPaw\Web\Interfaces\ByteRangeInterface;
use CatPaw\Web\Interfaces\FileServerInterface;
use CatPaw\Web\Interfaces\ServerInterface;
use const CatPaw\Web\INTERNAL_SERVER_ERROR;
use CatPaw\Web\Mime;

class MyCustomByteRangeFileServer implements FileServerInterface {
    public static function create(
        ServerInterface $server,
        ByteRangeInterface $byteRange
    ) {
        return new self($server, $byteRange);
    }
    private function __construct(
        private ServerInterface $server,
        private ByteRangeInterface $byteRangeService
    ) {
    }

    public function serve(Request $request): Response {
        $server   = $this->server;
        $range    = $this->byteRangeService;
        $fileName = "{$server->getStaticsLocation()}/videoplayback.mp4";


        $response = $range->file(
            rangeQuery: $request->getHeader('range') ?? '',
            fileName: $fileName,
        )->unwrap($error);

        if (!$error) {
            $response->setHeader("Content-Type", Mime::findContentType($fileName));
            return $response;
        }

        $file = File::open($fileName, 'r')->unwrap($error);

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

function main(ServerInterface $server, ByteRangeInterface $byteRange) {
    return anyError(function() use ($server, $byteRange) {
        Container::provide(
            name: FileServerInterface::class,
            value: MyCustomByteRangeFileServer::create($server, $byteRange),
        );

        $server
            ->withStaticsLocation(asFileName(__DIR__, '../../../public'))
            ->start()
            ->try();
    });
}
