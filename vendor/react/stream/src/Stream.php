<?php

namespace React\Stream;

use Evenement\EventEmitter;
use React\EventLoop\LoopInterface;
use InvalidArgumentException;

class Stream extends EventEmitter implements DuplexStreamInterface
{
    public $bufferSize = 4096;
    public $stream;
    protected $readable = true;
    protected $writable = true;
    protected $closing = false;
    protected $loop;
    protected $buffer;

    public function __construct($stream, LoopInterface $loop)
    {
        $this->stream = $stream;
        if (!is_resource($this->stream) || get_resource_type($this->stream) !== "stream") {
             throw new InvalidArgumentException('First parameter must be a valid stream resource');
        }

        stream_set_blocking($this->stream, 0);

        // Use unbuffered read operations on the underlying stream resource.
        // Reading chunks from the stream may otherwise leave unread bytes in
        // PHP's stream buffers which some event loop implementations do not
        // trigger events on (edge triggered).
        // This does not affect the default event loop implementation (level
        // triggered), so we can ignore platforms not supporting this (HHVM).
        if (function_exists('stream_set_read_buffer')) {
            stream_set_read_buffer($this->stream, 0);
        }

        $this->loop = $loop;
        $this->buffer = new Buffer($this->stream, $this->loop);

        $that = $this;

        $this->buffer->on('error', function ($error) use ($that) {
            $that->emit('error', array($error, $that));
            $that->close();
        });

        $this->buffer->on('drain', function () use ($that) {
            $that->emit('drain', array($that));
        });

        $this->resume();
    }

    public function isReadable()
    {
        return $this->readable;
    }

    public function isWritable()
    {
        return $this->writable;
    }

    public function pause()
    {
        $this->loop->removeReadStream($this->stream);
    }

    public function resume()
    {
        if ($this->readable) {
            $this->loop->addReadStream($this->stream, array($this, 'handleData'));
        }
    }

    public function write($data)
    {
        if (!$this->writable) {
            return;
        }

        return $this->buffer->write($data);
    }

    public function close()
    {
        if (!$this->writable && !$this->closing) {
            return;
        }

        $this->closing = false;

        $this->readable = false;
        $this->writable = false;

        $this->emit('end', array($this));
        $this->emit('close', array($this));
        $this->loop->removeStream($this->stream);
        $this->buffer->removeAllListeners();
        $this->removeAllListeners();

        $this->handleClose();
    }

    public function end($data = null)
    {
        if (!$this->writable) {
            return;
        }

        $this->closing = true;

        $this->readable = false;
        $this->writable = false;

        $this->buffer->on('close', array($this, 'close'));

        $this->buffer->end($data);
    }

    public function pipe(WritableStreamInterface $dest, array $options = array())
    {
        Util::pipe($this, $dest, $options);

        return $dest;
    }

    public function handleData($stream)
    {
        $error = null;
        set_error_handler(function ($errno, $errstr, $errfile, $errline) use (&$error) {
            $error = new \ErrorException(
                $errstr,
                0,
                $errno,
                $errfile,
                $errline
            );
        });

        $data = fread($stream, $this->bufferSize);

        restore_error_handler();

        if ($error !== null) {
            $this->emit('error', array(new \RuntimeException('Unable to read from stream: ' . $error->getMessage(), 0, $error), $this));
            $this->close();
            return;
        }

        if ($data !== '') {
            $this->emit('data', array($data, $this));
        }

        if (!is_resource($stream) || feof($stream)) {
            $this->end();
        }
    }

    public function handleClose()
    {
        if (is_resource($this->stream)) {
            fclose($this->stream);
        }
    }

    public function getBuffer()
    {
        return $this->buffer;
    }
}
