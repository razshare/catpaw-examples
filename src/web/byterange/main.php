<?php

use function Amp\async;
use Amp\File\File;

use function Amp\File\getSize;
use function Amp\File\openFile;
use Amp\Http\HttpStatus;
use Amp\Http\Server\Response;

use CatPaw\Web\Attributes\Header;
use CatPaw\Web\Attributes\Produces;
use function CatPaw\Web\duplex;
use CatPaw\Web\Exceptions\InvalidByteRangeQueryException;
use CatPaw\Web\Interfaces\ByteRangeWriterInterface;
use CatPaw\Web\Server;
use CatPaw\Web\Services\ByteRangeService;

function main() {
    $server = Server::create();
    $server->router->get(
        '/',
        #[Produces("audio/mp4")]
        function(
            #[Header("range")] false | array $range,
            ByteRangeService $service
        ) {
            $filename = "public/videoplayback.mp4";
            $length   = getSize($filename);
            try {
                return $service->response(
                    rangeQuery: $range[0] ?? "",
                    headers   : [
                        "Content-Type"   => "audio/mp4",
                        "Content-Length" => $length,
                    ],
                    writer    : new class($filename) implements ByteRangeWriterInterface {
                        private File $file;

                        public function __construct(private string $filename) {
                        }

                        public function start():void {
                            $this->file = openFile($this->filename, "r");
                        }


                        public function data(callable $emit, int $start, int $length):void {
                            $this->file->seek($start);
                            $data = $this->file->read(null, $length);
                            $emit($data);
                        }


                        public function end():void {
                            $this->file->close();
                        }
                    }
                );
            } catch (InvalidByteRangeQueryException) {
                [$reader, $writer] = duplex();
                
                $file = openFile($filename, "r");
                
                async(function() use ($file, $writer) {
                    while ($chunk = $file->read()) {
                        $writer->write($chunk);
                    }
                });

                return new Response(
                    status: HttpStatus::OK,
                    headers: [
                        "Accept-Ranges"  => "bytes",
                        "Content-Type"   => "audio/mp4",
                        "Content-Length" => $length,
                    ],
                    body: $reader,
                );
            }
        }
    );
    $server->start();
}